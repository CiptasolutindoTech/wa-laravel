# Laravel WA Helper

A helper for sending wa for laravel designed for ciptasolutindo and team.
Curently support for laravel 8+ and php 7.3+

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

2. Add ```devlagret/wa-laravel``` to required package or run :

    ```bash
        composer require devlagret/wa-laravel
    ```

3. Add api url,secret and auth optionaly you can add default phone number for testing

    ```txt
        WA_SERVER_API_URL=https://example.com/api
        WA_APP_KEY=xxxx-xxxx-xxxx-xxxx-xxxx
        WA_AUTH_KEY=xxxxxxx
        TEST_PHONE_NUMBERS=08123456789
    ```
