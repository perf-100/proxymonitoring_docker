<?php

namespace App\Providers;

use App\Models\Proxy;
use App\Models\TelegramBot;
use App\Policies\ProxyPolicy;
use App\Policies\TelegramBotPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Proxy::class => ProxyPolicy::class,
        TelegramBot::class => TelegramBotPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
