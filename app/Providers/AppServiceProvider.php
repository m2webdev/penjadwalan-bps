<?php

namespace App\Providers;

use App\Services\Impl\TelegramMessagesService;
use App\Services\MessagesService;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function provides()
    {
        return [
            MessagesService::class
        ];
    }
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(MessagesService::class, TelegramMessagesService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
