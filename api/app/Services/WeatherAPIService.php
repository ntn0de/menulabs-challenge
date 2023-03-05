<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WeatherAPIService
{
    private $apiKey;
    private $baseUrl;

    public function __construct()
    {
        $this->apiKey = env('WEATHER_API_KEY');
        $this->baseUrl = env('WEATHER_API_URL');
    }

    public function getCurrentWeather($latitude, $longitude)
    {
        try {
            $response = Http::get($this->baseUrl . 'weather', [
                'lat' => $latitude,
                'lon' => $longitude,
                'appid' => $this->apiKey,
                'units' => 'metric'
            ]);

            if (!$response->ok()) {
                return null;
            }

            return json_decode($response->getBody()->getContents());
        } catch (\Exception $e) {
            return null;
        }
    }
}
