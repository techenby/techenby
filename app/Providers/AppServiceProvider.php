<?php

namespace App\Providers;

use App\Listeners\SendFaqQuestionToTelegram;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Statamic\Events\SubmissionCreated;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(SubmissionCreated::class, SendFaqQuestionToTelegram::class);
    }
}
