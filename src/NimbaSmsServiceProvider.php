<?php

namespace Tmoh\NimbaSms;

use Illuminate\Support\ServiceProvider;

class NimbaSmsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/nimba_sms.php', 'nimba_sms');

        $this->app->bind('nimba-sms', function ($app) {
            return new NimbaSmsService(
                new NimbaSmsClient(
                    config('nimba_sms.base_url'),
                    config('nimba_sms.token'),
                    config('nimba_sms.timeout', 30)
                ),
                config('nimba_sms.default_sender_name')
            );
        });
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../config/nimba_sms.php' => config_path('nimba_sms.php'),
            ], 'config');
        }
    }
}
