<?php

namespace App\Helpers;

use App\Models\GeneralSettings;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

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
            CURLOPT_URL => config('sync.server_url') . '/api/login',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode([
                'email' => config('sync.email'),
                'password' => config('sync.password'),
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

        // simpan ke DB
        GeneralSettings::updateOrCreate(
            ['key' => 'access_token'],
            [
                'value' => $token,
                'is_hidden' => 1,
            ]
        );

        return $token;
    }

    public static function curlWithToken($url)
    {
        $token = self::getAccessToken();

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
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $token,
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
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $token,
                'Content-Type: application/json',
                'Accept: application/json'
            ],
        ]);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        return $response;
    }
}