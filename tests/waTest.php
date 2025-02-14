<?php

namespace Tests\Feature;

use Cst\WALaravel\WA;
use Exception;
use Orchestra\Testbench\TestCase;
use Orchestra\Testbench\Bootstrap\LoadConfiguration;
use Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables;

class waTest extends TestCase
{
        /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array<int, class-string<\Illuminate\Support\ServiceProvider>>
     */
    protected function getPackageProviders($app)
    {
        return [
            'Cst\WALaravel\WAServiceProvider',
        ];
    }
    protected function getEnvironmentSetUp($app)
    {
        // make sure, our .env file is loaded
        $app->useEnvironmentPath(__DIR__.'/..');
        $app->configPath(__DIR__.'/../src/config');
        $app->bootstrapWith([LoadEnvironmentVariables::class,LoadConfiguration::class]);
        parent::getEnvironmentSetUp($app);
        config()->set('wa.app_token', env("WA_APP_KEY"));
        config()->set('wa.auth_token', env("WA_AUTH_KEY"));
        config()->set('wa.dev_numbers', env("DEV_PHONE_NUMBERS"));
        config()->set('wa.dev_driver', "cipta");
        config()->set('wa.api_url', env("WA_SERVER_API_URL"));
        config()->set('wa.test_numbers', env("TEST_PHONE_NUMBERS"));
        config()->set('app.url', env("APP_URL"));
        config()->set('app.name', env("APP_NAME"));
        config()->set('app.env', env("APP_ENV"));
        config()->set('app.debug', env("APP_DEBUG"));
        config()->set('wa.test_message', "Test WA ".env('APP_NAME'));
     }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_sending_messsage()
    {
        $wa = '';
        $wa = WA::test();
        fwrite(STDOUT,$wa);
        $this->assertTrue(true);
    }
    public function test_sending_messsage_using_msg()
    {
        $wa = WA::msg("test message wa library");
        fwrite(STDOUT,$wa);
        $this->assertTrue(true);
    }
}
