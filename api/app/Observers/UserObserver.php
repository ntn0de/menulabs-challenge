<?php

namespace App\Observers;

use App\Jobs\PullWeatherDataJob;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        PullWeatherDataJob::dispatch($user);
        Cache::forget('users');
    }
}
