<?php

namespace App\Console\Commands;

use App\Jobs\PullWeatherDataJob;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class WeatherDataCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'weatherdata:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This Command updates weather data every one hour';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $users = Cache::remember('users', 60 * 60, function () {
            return User::all();
        });

        $users->map(function ($user) {
            Log::info('Hourly WeatherDataCron started at : ' . now());
            Log::info("Dispatched for : " . $user->id . ' ' . $user->name);
            PullWeatherDataJob::dispatch($user);
        });
    }
}
