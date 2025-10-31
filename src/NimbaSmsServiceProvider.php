<?php

namespace Tmoh\NimbaSms;

use Illuminate\Support\ServiceProvider;

class NimbaSmsServiceProvider extends ServiceProvider
{
    private const AUTH_PREFIX = 'Basic ';

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/config/nimba_sms.php', 'nimba_sms');

        $this->app->bind('nimba-sms', function () {
            $serviceId = config('nimba_sms.service_id');
            $serviceSecret = config('nimba_sms.service_secret');

            if (empty($serviceId) || empty($serviceSecret)) {
                throw new \RuntimeException('NIMBA_SMS_SERVICE_ID and NIMBA_SMS_SERVICE_SECRET must be configured');
            }

            $credentials = $serviceId . ':' . $serviceSecret;
            $token = self::AUTH_PREFIX . base64_encode($credentials);

            return new NimbaSmsService(
                new NimbaSmsClient(
                    config('nimba_sms.base_url'),
                    $token,
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
                __DIR__ . '/config/nimba_sms.php' => config_path('nimba_sms.php'),
            ], 'config');
        }
    }
}
