# NIMBA SMS Laravel Package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/tmoh/nimba-sms.svg?style=flat-square)](https://packagist.org/packages/tmoh/nimba-sms)
[![Total Downloads](https://img.shields.io/packagist/dt/tmoh/nimba-sms.svg?style=flat-square)](https://packagist.org/packages/tmoh/nimba-sms)

Laravel package for NIMBA SMS API integration.

> 📖 **Official Documentation**: [NIMBA SMS API Documentation](https://developers.nimbasms.com/#section/Introduction)

## Installation

```bash
composer require tmoh/nimba-sms
```

## Configuration

Publish config:

```bash
php artisan vendor:publish --provider="Tmoh\NimbaSms\NimbaSmsServiceProvider" --tag="config"
```

Add to `.env`:

```env
NIMBA_SMS_BASE_URL=https://api.nimbasms.com
NIMBA_SMS_TOKEN=Basic xxxxx
NIMBA_SMS_DEFAULT_SENDER_NAME=NIMBA
NIMBA_SMS_TIMEOUT=30
```

## Usage

```php
use Tmoh\NimbaSms\Facades\NimbaSms;

try {
    // Send SMS
    $response = NimbaSms::sendSms('623123456', 'Hello World!');
    
    // Get account info
    $account = NimbaSms::getAccounts();
    
    // Get sender names
    $senderNames = NimbaSms::getSenderNames();
    
    // Get webhooks
    $webhooks = NimbaSms::getWebhooks();
    
    // Get purchases
    $purchases = NimbaSms::getPurchases();
    
} catch (Exception $e) {
    echo $e->getMessage();
}
```

## License

MIT