<?php

// ============================================================
// File: app/Services/PrintBridgeService.php
// ============================================================
// Service untuk mengirim ESC/POS job ke PrintBridge browser extension
// yang berjalan di client Windows.
//
// Flow:
//   Laravel (server) → HTTP POST → PrintBridge JS Agent (localhost:8765)
//   PrintBridge → Native Messaging → host.js → Windows RAW Print → Thermal Printer
// ============================================================

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\DummyPrintConnector;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;

class PrintBridgeService
{
    protected string $bridgeUrl;
    protected string $defaultPrinter;

    public function __construct()
    {
        $this->bridgeUrl     = config('printbridge.url', 'http://127.0.0.1:8000');
        $this->defaultPrinter = config('printbridge.printer', 'POS-80');
    }

    /**
     * Kirim raw ESC/POS bytes ke PrintBridge.
     * $data bisa berupa: string binary | base64 string | resource
     */
    public function sendRaw(string $data, ?string $printer = null): bool
    {
        $printer ??= $this->defaultPrinter;

        // Pastikan data binary, bukan base64
        $binary = base64_decode($data, true) !== false && !$this->isBinary($data)
            ? base64_decode($data)
            : $data;

        try {
            $response = Http::timeout(5)->post("{$this->bridgeUrl}/print", [
                'printer'  => $printer,
                'data'     => base64_encode($binary),
                'encoding' => 'base64',
            ]);

            if (!$response->successful()) {
                Log::warning('PrintBridge: HTTP error', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
                return false;
            }

            return true;
        } catch (\Exception $e) {
            Log::error('PrintBridge: Connection failed', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Buat Printer instance dengan DummyConnector, jalankan callback,
     * lalu kirim bytes ke PrintBridge.
     *
     * Contoh:
     *   $bridge->printWith(function(Printer $p) {
     *       $p->setEmphasis(true);
     *       $p->text("Hello!\n");
     *       $p->cut();
     *   });
     */
    public function printWith(callable $callback, ?string $printer = null): bool
    {
        $connector = new DummyPrintConnector();
        $p = new Printer($connector);

        try {
            $callback($p);
            $p->close();
        } catch (\Exception $e) {
            Log::error('PrintBridge: ESC/POS build error', ['error' => $e->getMessage()]);
            return false;
        }

        $data = $connector->getData();
        return $this->sendRaw($data, $printer);
    }

    /**
     * Print struk sederhana (teks saja).
     */
    public function printText(string $text, ?string $printer = null): bool
    {
        return $this->printWith(function (Printer $p) use ($text) {
            $p->text($text);
            $p->feed(4);
            $p->cut();
        }, $printer);
    }

    /**
     * Cek apakah PrintBridge agent sedang aktif di client.
     */
    public function isOnline(): bool
    {
        try {
            $response = Http::timeout(2)->get("{$this->bridgeUrl}/status");
            return $response->successful();
        } catch (\Exception) {
            return false;
        }
    }

    private function isBinary(string $str): bool
    {
        return preg_match('/[^\x20-\x7E\x09\x0A\x0D]/', $str);
    }
}

// ============================================================
// File: config/printbridge.php
// ============================================================
// return [
//     'url'     => env('PRINTBRIDGE_URL', 'http://localhost:8765'),
//     'printer' => env('PRINTBRIDGE_PRINTER', 'POS-80'),
// ];

// ============================================================
// File: .env additions
// ============================================================
// PRINTBRIDGE_URL=http://localhost:8765
// PRINTBRIDGE_PRINTER=EPSON TM-T82

// ============================================================
// CONTOH PENGGUNAAN di Controller
// ============================================================
//
// use App\Services\PrintBridgeService;
// use Mike42\Escpos\Printer;
//
// class SalesInvoiceController extends Controller
// {
//     public function printReceipt(Request $request, PrintBridgeService $bridge)
//     {
//         $invoice = SalesInvoice::findOrFail($request->id);
//
//         $success = $bridge->printWith(function (Printer $p) use ($invoice) {
//             // Header
//             $p->setJustification(Printer::JUSTIFY_CENTER);
//             $p->setEmphasis(true);
//             $p->text("PT TRISENTA INTERIOR\n");
//             $p->setEmphasis(false);
//             $p->text("Kawasan Berikat Bandung\n");
//             $p->text("================================\n");
//
//             // Body
//             $p->setJustification(Printer::JUSTIFY_LEFT);
//             $p->text("No: {$invoice->number}\n");
//             $p->text("Tgl: {$invoice->date->format('d/m/Y H:i')}\n");
//             $p->text("--------------------------------\n");
//
//             foreach ($invoice->items as $item) {
//                 $p->text("{$item->name}\n");
//                 $p->text("  {$item->qty} x " . number_format($item->price) . "\n");
//             }
//
//             $p->text("================================\n");
//             $p->setEmphasis(true);
//             $p->text("TOTAL: " . number_format($invoice->total) . "\n");
//             $p->setEmphasis(false);
//
//             // Footer
//             $p->feed(2);
//             $p->setJustification(Printer::JUSTIFY_CENTER);
//             $p->text("Terima Kasih!\n");
//             $p->feed(4);
//             $p->cut();
//         });
//
//         return response()->json(['success' => $success]);
//     }
// }
