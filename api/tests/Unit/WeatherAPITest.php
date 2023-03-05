<?php

namespace Tests\Unit;

use App\Services\WeatherAPIService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class WeatherAPITest extends TestCase
{
    public function test_weather_api_request()
    {
        // Set up test data
        $latitude = '51.5074';
        $longitude = '-0.1278';
        $apiKey = env('WEATHER_API_KEY');
        $expectedUrl = "https://api.openweathermap.org/data/2.5/weather?lat=$latitude&lon=$longitude&appid=$apiKey&units=metric";

        // Mock the HTTP request and response
        $expectedResponse = '{"coord":{"lon":-0.1278,"lat":51.5074},"weather":[{"id":800,"main":"Clear","description":"clear sky","icon":"01n"}],"base":"stations","main":{"temp":280.13,"feels_like":277.72,"temp_min":278.71,"temp_max":281.48,"pressure":1033,"humidity":64},"visibility":10000,"wind":{"speed":1.03,"deg":212},"clouds":{"all":0},"dt":1613191922,"sys":{"type":1,"id":1414,"country":"GB","sunrise":1613133568,"sunset":1613167553},"timezone":0,"id":2643743,"name":"London","cod":200}';
        Http::fake([
            $expectedUrl => Http::response($expectedResponse, 200)
        ]);

        // Creating  WeatherAPIService instance
        $weatherAPIservice = new WeatherAPIService();

        // Call the getCurrentWeather method
        $weatherData = $weatherAPIservice->getCurrentWeather($latitude, $longitude);

        // Check that the expected URL was called
        Http::assertSent(function ($request) use ($expectedUrl) {
            return $request->url() === $expectedUrl;
        });


        $weatherDataArray = json_decode(json_encode($weatherData), true);

        $this->assertArrayHasKey('coord', $weatherDataArray);
        $this->assertArrayHasKey('weather', $weatherDataArray);
        $this->assertArrayHasKey('main', $weatherDataArray);
    }
    public function test_weather_api_request_for_non_existent_lat_and_long()
    {
        // Set up test data
        $latitude = '212151.5074';
        $longitude = '-13131330.1278';
        $apiKey = env('WEATHER_API_KEY');
        $expectedUrl = "https://api.openweathermap.org/data/2.5/weather?lat=$latitude&lon=$longitude&appid=$apiKey&units=metric";

        // Mock the HTTP request and response
        $expectedResponse = '{}';
        Http::fake([
            $expectedUrl => Http::response($expectedResponse, 200)
        ]);

        // Creating  WeatherAPIService instance
        $weatherAPIservice = new WeatherAPIService();

        // Call the getCurrentWeather method
        $weatherData = $weatherAPIservice->getCurrentWeather($latitude, $longitude);

        // Check that the expected URL was called
        Http::assertSent(function ($request) use ($expectedUrl) {

            return $request->url() === $expectedUrl;
        });
        $weatherDataArray = json_decode(json_encode($weatherData), true);
        $this->assertEmpty($weatherDataArray);
    }
}
