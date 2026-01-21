# Laravel WA Helper

A helper for sending wa for laravel designed for ciptasolutindo and team.
Curently support for laravel 8+ and php 7.3+, This library also support sending wa with ruangwa.
Available driver : cipta , ruangWa

## Installation

1. Add vcs repo to ```composer.json```

    ```json
        "repositories": [
            {
                "type": "vcs",
                "url": "https://github.com/CiptasolutindoTech/wa-laravel"
            }
        ],
    ```

2. Add ```cst/wa-laravel``` to required package in composer json or run in terminal :

    ```bash
        composer require cst/wa-laravel
    ```

3. Add api url,secret and auth optionaly, default phone number for testing and dev number for dev() function

    ```txt
        WA_SERVER_API_URL=https://example.com/api
        WA_APP_KEY=xxxx-xxxx-xxxx-xxxx-xxxx
        WA_AUTH_KEY=xxxxxxx
        TEST_PHONE_NUMBERS=08123456789
        DEV_PHONE_NUMBERS=08123456789
        #for ruangwa
        RUANG_WA_TOKEN=xxxxxxxxxxxxxxxxxxxxxxx

    ```

4. Publish Assets if needed:

    ```bash
        php artisan vendor:publish --tag=wa
    ```

## Usage

Basic usage:

```php
    use Cst\WALaravel\WA; // at the top of the file

    // send message
    WA::to("08123456789")->msg('Hello');
    // send default test message
    WA::to("08123456789")->test();
    // send message to test number
    WA::msg('Hello');
    // send default test message to test number
    WA::test();
    // Multi message
    WA::msg(
        [
            "08123456781"=>"Hello Person 1",
            "08123456781"=>"Hello Person 2",
            ...
        ]);
    WA::msg(
        [
            ["to"=>"08123456781","msg"=>"Hello Person 1"],
            ["to"=>"08123456781","msg"=>"Hello Person 2"],
            ...
        ]);
    WA::to(["08123456781","08123456782","08123456783"])
        ->msg("Hello Everyone");

```

## Using [ruangWa](https://ruangwa.id/)

Before using any function you can specify driver like :

```php

  use Cst\WALaravel\WA; // at the top of the file

    // send message
    WA::driver('ruangWa')->to("08123456789")->msg('Hello');
    // send default test message
    WA::ruangWa()->to("08123456789")->test();
    // send message to test number
    WA::cipta()->msg('Hello');
    // send default test message to test number
    WA::driver('cipta')->test();

```

Or On env set the default driver to:

```env

WA_DRIVER=ruangWa

```