<?php

namespace Tmoh\NimbaSms\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array sendSms(string $recipient, string $message, ?string $senderName = null)
 * @method static array getAccounts()
 * @method static array getSenderNames()
 * @method static array getWebhooks()
 */
class NimbaSms extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'nimba-sms';
    }
}
