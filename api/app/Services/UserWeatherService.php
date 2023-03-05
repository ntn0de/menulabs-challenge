<?php

namespace App\Services;


use App\Models\User;
use Illuminate\Support\Facades\Cache;

class UserWeatherService
{
    private $weatherAPIService;

    public function __construct(WeatherAPIService $weatherAPIService)
    {
        $this->weatherAPIService = $weatherAPIService;
    }

    public function getUsersWithWeatherData()
    {

        $users = Cache::remember('users', 60 * 60, function () {
            return User::all();
        });

        return $users->map(function ($user) {
            $weather = self::getWeatherData($user);

            $user->weather = $weather;
            return $user;
        });
    }
    public function getUserWithWeather($id)
    {
        $user = Cache::remember('user-' . $id, 60 * 60, function () use ($id) {
            return User::findOrFail($id);
        });
        $weather = self::getWeatherData($user);
        $user->weather = $weather;
        return $user;
    }

    public function getWeatherData($user)
    {
        return Cache::remember('weather-' . $user->longitude . '-' . $user->latitude, 60 * 60, function () use ($user) {
            return  self::fetchWeatherData($user);
        });
    }
    private function fetchWeatherData($user)
    {
        $weather_data = $this->weatherAPIService->getCurrentWeather($user->latitude, $user->longitude);
        if ($weather_data)
            return [
                'weather_data' => $weather_data,
                'updated_time' => now()
            ];
        return null;
    }
    public function storeWeatherData($user)
    {
        if (Cache::has('weather-' . $user->longitude . '-' . $user->latitude))
            Cache::forget('weather-' . $user->longitude . '-' . $user->latitude);

        return self::getWeatherData($user);
    }

    public function forceUpdateWeatherData($id)
    {
        $user = Cache::remember('user-' . $id, 60 * 60, function () use ($id) {
            return User::findOrFail($id);
        });
        Cache::forget('weather-' . $user->longitude . '-' . $user->latitude);

        $weather = Cache::remember('weather-' . $user->longitude . '-' . $user->latitude, 60 * 60, function () use ($user) {
            return  self::fetchWeatherData($user);
        });
        $user->weather = $weather;
        return $user;
    }
}
