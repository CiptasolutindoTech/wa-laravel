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

    'wa_api_url' => env('WA_SERVER_API_URL'),

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
    | Whatsapp app and auth key for wacipta
    |--------------------------------------------------------------------------
    |
    | Phone number for testing and/or dev can accept array or string
    |
    */

    'wa_app_token' => env('WA_APP_KEY'),
    'wa_auth_token' => env('WA_AUTH_KEY'),
    /*
    |--------------------------------------------------------------------------
    | Whatsapp token for client
    |--------------------------------------------------------------------------
    |
    | Token for client side for qr, can be disabled
    |
    */

    'disable_wa_client_token' => env('DISABLE_WA_CLIENT_TOKEN',false),
    'wa_client_token' => env('WA_CLIENT_TOKEN'),
];
