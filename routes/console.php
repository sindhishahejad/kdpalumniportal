<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule Weekly Digest to run automatically every Monday at 08:00 AM
Schedule::command('newsletter:send')
    ->weeklyOn(1, '08:00')
    ->withoutOverlapping()
    ->runInBackground();
