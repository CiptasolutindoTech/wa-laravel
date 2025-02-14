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
    'dev_url' => env('WA_SERVER_API_URL'),

    /*
    |--------------------------------------------------------------------------
    | WA Driver
    |--------------------------------------------------------------------------
    |
    | Wa driver for auth or normal
    | avaible driver : cipta,ruangwa
    | dev_driver : diver that used for dev() function
    |
    */

    'driver' => env('WA_DRIVER','cipta'),
    'sandbox_driver' => 'cipta',
    'dev_driver' => 'cipta',

    /*
    |--------------------------------------------------------------------------
    | Ruang WA Url
    |--------------------------------------------------------------------------
    |
    | Url of Ruangwa api endpoint
    |
    */

    'ruang_wa_url' => env('RUANG_WA_URL',"https://app.ruangwa.id/api"),

    /*
    |--------------------------------------------------------------------------
    | Ruang WA Token
    |--------------------------------------------------------------------------
    |
    | Value of Ruang WA Token
    |
    | ruang_wa_sandbox_token : used for sanbox
    | ruang_wa_dev_token : used for dev() function
    |
    */

    'ruang_wa_token' => env('RUANG_WA_TOKEN'),
    'ruang_wa_sandbox_token' => env('RUANG_WA_SANDBOX_TOKEN'),
    'ruang_wa_dev_token' => env('RUANG_WA_DEV_TOKEN'),

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
    | Dev contact mode
    |--------------------------------------------------------------------------
    |
    | What used for dev contact to group or numbers
    | dev_mode : groups, numbers or both
    | sparate number with comma for multiple reciever
    |
    */

    'dev_mode' => 'numbers',
    'dev_numbers' => env('DEV_PHONE_NUMBERS'),
    'dev_groups' => '',

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
    | Whatsapp app and auth
    |--------------------------------------------------------------------------
    |
    | Phone number for testing and/or dev can accept array or string
    |
    */

    'app_token' => env('WA_APP_KEY'),
    'auth_token' => env('WA_AUTH_KEY'),
    'app_dev_token' => env('WA_DEV_APP_KEY'),
    'auth_dev_token' => env('WA_DEV_AUTH_KEY'),
    'app_sandbox_token' => env('WA_SANDBOX_APP_KEY'),
    'auth_numbers_token' => env('WA_SANDBOX_AUTH_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Whatsapp token for client
    |--------------------------------------------------------------------------
    |
    | Token for client side for qr, can be disabled
    | Can also set token for dev connection
    |
    */

    'disable_client_token' => env('DISABLE_WA_CLIENT_TOKEN',false),
    'client_token' => env('WA_CLIENT_TOKEN'),
    'client_dev_token' => env('WA_DEV_CLIENT_TOKEN'),

    /*
    |--------------------------------------------------------------------------
    | String Limit for message
    |--------------------------------------------------------------------------
    |
    | Cipta driver has limit of 1000 character
    |
    */
    'string_limit' => 995,
];
