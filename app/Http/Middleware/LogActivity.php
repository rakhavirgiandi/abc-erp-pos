<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class LogActivity
{
    /**
     * Field yang tidak boleh ikut disimpan ke log (sensitif / tidak berguna).
     */
    protected array $exceptInput = [
        '_token',
        '_method',
        'password',
        'password_confirmation',
        'token',
        'card_number',
        'cvv',
    ];

    /**
     * Header HTTP yang tidak boleh ikut disimpan ke log (sensitif).
     */
    protected array $exceptHeaders = [
        'authorization',
        'cookie',
        'x-csrf-token',
        'x-xsrf-token',
    ];

    /**
     * Method HTTP yang akan dicatat. GET biasanya di-skip karena
     * cuma "melihat" data, bukan mengubah — sesuaikan kalau perlu.
     */
    protected array $loggedMethods = ['POST', 'PUT', 'PATCH', 'DELETE'];

    /**
     * Field pada body response JSON yang tidak boleh ikut disimpan.
     */
    protected array $exceptResponseFields = [
        'token',
        'access_token',
        'password',
    ];

    /**
     * Batas ukuran body response yang disimpan (byte). Response yang
     * lebih besar dari ini hanya dicatat ukurannya, bukan isinya penuh.
     */
    protected int $maxResponseSize = 5000;

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (in_array($request->method(), $this->loggedMethods)) {
            $this->record($request, $response);
        }

        return $response;
    }

    protected function record(Request $request, Response $response): void
    {
        $status = $response->getStatusCode();
        $isSuccess = $response->isSuccessful();

        $logName = $isSuccess ? 'http_request' : 'http_error';

        Log::channel('activity')->log(
            $isSuccess ? 'info' : 'error',
            $this->describeStatus($status) . ': ' . $request->method() . ' ' . $request->path(),
            [
                'type' => $isSuccess ? 'http_request' : 'http_error',
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'user_id' => $request->user()?->getAuthIdentifier(),
                'user_companies' => config('user_companies.details'),
                'ip' => $request->ip(),
                'status' => $status,
                'input' => $request->except($this->exceptInput),
                'headers' => collect($request->headers->all())
                    ->except($this->exceptHeaders)
                    ->toArray(),
                'response' => $this->captureResponse($response),
            ]
        );
    }

    /**
     * Label singkat untuk deskripsi log berdasarkan kelompok status code.
     */
    protected function describeStatus(int $status): string
    {
        return match (true) {
            $status >= 500 => 'Server error',
            $status === 422 => 'Validation failed',
            $status === 403 => 'Access denied',
            $status === 401 => 'Unauthenticated',
            $status === 404 => 'Not Found',
            $status >= 400 => 'Request failed',
            default => 'Success',
        };
    }

    /**
     * Ambil body response dengan aman. Hanya menyimpan isi penuh untuk
     * response JSON yang tidak terlalu besar; selain itu hanya metadata.
     */
    protected function captureResponse(Response $response): array
    {
        // response redirect (misal setelah form submit) tidak punya body relevan
        if ($response->isRedirection()) {
            return ['type' => 'redirect', 'target' => $response->headers->get('Location')];
        }

        $contentType = $response->headers->get('Content-Type', '');
        $content = $response->getContent();
        $size = strlen($content ?: '');

        // hanya JSON yang di-decode & disimpan isinya; HTML/view/binary cukup metadata
        if (str_contains($contentType, 'application/json')) {
            if ($size > $this->maxResponseSize) {
                return ['type' => 'json', 'size_bytes' => $size, 'truncated' => true];
            }

            $decoded = json_decode($content, true);

            return [
                'type' => 'json',
                'body' => is_array($decoded)
                    ? collect($decoded)->except($this->exceptResponseFields)->toArray()
                    : $decoded,
            ];
        }

        // error dari exception handler Laravel biasanya HTML (halaman error debug/production)
        // — jangan simpan HTML mentah, cukup metadata + status
        if ($response->isServerError() || $response->isClientError()) {
            return [
                'type' => $contentType ?: 'unknown',
                'size_bytes' => $size,
                'note' => 'Body error tidak disimpan penuh (bukan JSON). Cek laravel.log untuk detail.',
            ];
        }

        // HTML (view) atau tipe lain: jangan simpan HTML mentah, cukup ukurannya
        return ['type' => $contentType ?: 'unknown', 'size_bytes' => $size];
    }
}