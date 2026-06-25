<?php

namespace App\Helpers;

use App\Models\GeneralSettings;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class NetworkHelper
{
    public static function isConnected($url = 'https://www.google.com')
    {
        if (Cache::has('internet_connection')) {
            return Cache::get('internet_connection');
        }

        try {
            $response = Http::timeout(30)
                ->connectTimeout(10)
                ->retry(3, 2000)
                ->get($url);

            $is_online = $response->successful();

            // kalau online, cache 30 detik
            // kalau offline, cache 5 detik saja supaya cepat recover
            Cache::put('internet_connection', $is_online, $is_online ? 30 : 5);

            return $is_online;
        } catch (\Exception $e) {
            Cache::put('internet_connection', false, 5);
            return false;
        }
    }

    public static function getAccessToken()
    {
        $token = config('device_token.token');

        if ($token) {
            return $token;
        }

        return null;
    }

    public static function requestNewToken()
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => config('services.admin_credentials.server_url') . '/api/login',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode([
                'email' => config('services.admin_credentials.email'),
                'password' => config('services.admin_credentials.password'),
            ]),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Accept: application/json'
            ],
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        $result = json_decode($response, true);

        if (!isset($result['access_token'])) {
            return [
                'status' => 'error',
                'message' => 'Failed to get token'
            ];
        }

        $token = $result['access_token'];
        
        GeneralSettings::updateOrCreate(
            ['key' => 'access_token'],
            [
                'value' => $token,
                'is_hidden' => 1,
            ]
        );

        return $token;
    }

    public static function curlWithToken($url, $server_token = false)
    {   
        if ($server_token) {
            $token = Cache::get('server_token');
        } else {
            $token = self::getAccessToken();
        }

        $response = self::executeCurl($url, $token, $httpCode, $server_token);
        $decoded = json_decode($response, true);

        // trap non-2xx http code
        if ($httpCode < 200 || $httpCode >= 300) {
            \Log::warning('curlWithToken: HTTP error', [
                'url' => $url,
                'http_code' => $httpCode,
                'response' => $decoded ?? $response,
            ]);
        }

        return $decoded;
    }

    private static function executeCurl($url, $token, &$httpCode, $server = false)
    {
        $headers = [
            'Authorization: Bearer ' . $token,
            'Accept: application/json'
        ];

        if (!$server) {
            $COMPANY_ID = self::getCompanyId();
            $headers[] = 'company-id: ' . $COMPANY_ID;
        }

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTPHEADER => $headers,
        ]);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        return $response;
    }

    public static function postWithToken($url, $payload)
    {
        $token = self::getAccessToken();

        $response = self::executePost($url, $payload, $token, $httpCode);

        if ($httpCode === 401) {
            $token = self::requestNewToken();
            $response = self::executePost($url, $payload, $token, $httpCode);
        }

        return json_decode($response, true);
    }

    private static function executePost($url, $payload, $token, &$httpCode)
    {
        $COMPANY_ID = self::getCompanyId();
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $token,
                'company-id: ' . $COMPANY_ID,
                'Content-Type: application/json',
                'Accept: application/json'
            ],
        ]);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        return $response;
    }

    public static function loginToServer($params)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => config('services.admin_credentials.server_url') . '/api/login',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode([
                'email' => $params['email'] ?? '',
                'password' => $params['password'] ?? '',
            ]),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Accept: application/json'
            ],
        ]);

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            $error = curl_error($curl);
            curl_close($curl);

            return [
                'status' => 'error',
                'message' => 'Curl error: ' . $error
            ];
        }

        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        $result = json_decode($response, true);

        if (!isset($result['access_token']) || !$result['access_token']) {
            return $result;
        }

        Cache::put('server_token', $result['access_token'], now()->addDay());

        return $result;
    }

    private static function getCompanyId()
    {
        return config('company_id') ?? Session::get('_company_id') ?? (request()->header('company-id') ? request()->header('company-id')[0] : null);
    }
}