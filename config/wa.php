<?php
return [
    /*
    |--------------------------------------------------------------------------
    | WhatsApp Api url
    |--------------------------------------------------------------------------
    |
    | Url of ciptasolutindo whatsapp server endpoint
    |
    */

    'api_url' => env('WA_SERVER_API_URL'),

    /*
    |--------------------------------------------------------------------------
    | WA Driver
    |--------------------------------------------------------------------------
    |
    | Wa driver for auth or normal
    | avaible driver : cipta,ruangwa
    |
    */

    'driver' => env('WA_DRIVER','cipta'),
    'sandbox_driver' => 'cipta',

    /*
    |--------------------------------------------------------------------------
    | Ruang WA Url
    |--------------------------------------------------------------------------
    |
    | Url of Ruangwa api endpoint
    |
    */

    'ruang_wa_url' => env('RUANG_WA_URL',"https://app.ruangwa.id/api/"),

    /*
    |--------------------------------------------------------------------------
    | Ruang WA Token
    |--------------------------------------------------------------------------
    |
    | Value of Ruang WA Token
    |
    */

    'ruang_wa_token' => env('RUANG_WA_TOKEN'),

    /*
    |--------------------------------------------------------------------------
    | Phone Number For testing
    |--------------------------------------------------------------------------
    |
    | Phone number for testing and/or dev can accept array or string
    |
    */

    'test_numbers' => env('TEST_PHONE_NUMBERS'),

    /*
    |--------------------------------------------------------------------------
    | Test Message
    |--------------------------------------------------------------------------
    |
    | Default test message
    |
    */

    'test_message' => "Test WA ".env('APP_NAME'),

    /*
    |--------------------------------------------------------------------------
    | Whatsapp app and auth key for wacipta
    |--------------------------------------------------------------------------
    |
    | Phone number for testing and/or dev can accept array or string
    |
    */

    'app_token' => env('WA_APP_KEY'),
    'auth_token' => env('WA_AUTH_KEY'),
    /*
    |--------------------------------------------------------------------------
    | Whatsapp token for client
    |--------------------------------------------------------------------------
    |
    | Token for client side for qr, can be disabled
    |
    */

    'disable_client_token' => env('DISABLE_WA_CLIENT_TOKEN',false),
    'client_token' => env('WA_CLIENT_TOKEN'),
];
