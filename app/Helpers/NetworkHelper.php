<?php

namespace App\Helpers;

use App\Models\Companies\v1\GeneralSettings;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class NetworkHelper
{
    public static function isConnected($url = 'https://www.google.com')
    {
        return Cache::remember('internet_connection', 2, function () use ($url) {
            try {
                $response = Http::timeout(3)->get($url);
                return $response->successful();
            } catch (\Exception $e) {
                return false;
            }
        });
    }

    public static function getAccessToken()
    {
        $token = config('general_settings.sync_token');

        if ($token) {
            return $token;
        }

        return self::requestNewToken();
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
            throw new \Exception('Failed to get token');
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

        $response = self::executeCurl($url, $token, $httpCode);

        // token expired
        if ($httpCode == 401) {
            // ambil token baru
            $token = self::requestNewToken();
            // retry connect
            $response = self::executeCurl($url, $token, $httpCode);
        }

        return json_decode($response, true);
    }

    private static function executeCurl($url, $token, &$httpCode)
    {
        $COMPANY_ID = Session::get('_company_id');

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $token,
                'company-id: ' . $COMPANY_ID,
                'Accept: application/json'
            ],
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
        $COMPANY_ID = Session::get('_company_id');
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
            throw new \Exception($error);
        }

        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        $result = json_decode($response, true);
        
        Cache::put('server_token', $result['access_token'], now()->addDay());

        return $result;
    }
}