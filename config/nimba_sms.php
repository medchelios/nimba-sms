<?php

return [
    'base_url' => env('NIMBA_SMS_BASE_URL', 'https://api.nimbasms.com'),
    'token' => env('NIMBA_SMS_TOKEN'),
    'default_sender_name' => env('NIMBA_SMS_DEFAULT_SENDER_NAME', 'NIMBA'),
    'timeout' => env('NIMBA_SMS_TIMEOUT', 30),
    'rate_limit' => env('NIMBA_SMS_RATE_LIMIT', 100),
];
