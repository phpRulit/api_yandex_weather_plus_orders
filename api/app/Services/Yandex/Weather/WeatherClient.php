<?php

namespace App\Services\Yandex\Weather;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class WeatherClient
{
    /**
     * @var Client
     */
    private $client;

    /**
     * WeatherClient constructor.
     */
    public function __construct()
    {
        $this->client = new Client([ //GuzzleHttp, используем для составления http запроса
            "base_uri" => env("YANDEX_URL_WEATHER"),
            "headers" => ["X-Yandex-API-Key" => env("YANDEX_API_KEY_WEATHER")]
        ]);
    }

    /**
     * Запрос
     *
     * @return string|null
     */
    public function makeRequest(string $uri, string $type = "GET", array $headers = [])
    {
        try {
            $response = $this->client->request($type, $uri, [
                "headers" => $headers
            ]);

            return $response->getBody()->getContents();

        } catch(RequestException $e) {
            Log::error($e->getMessage());
            return null;
        }
    }
}
