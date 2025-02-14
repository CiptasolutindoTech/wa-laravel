<?php

namespace Tests\Feature;

use Cst\WALaravel\WA;
use Exception;
use Orchestra\Testbench\TestCase;
use Orchestra\Testbench\Bootstrap\LoadConfiguration;
use Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables;

class devWaTest extends TestCase
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
        config()->set('wa.dev_numbers', env("DEV_PHONE_NUMBERS"));
        config()->set('wa.api_url', env("WA_SERVER_API_URL"));
        config()->set('wa.app_dev_token', env('WA_DEV_APP_KEY'));
        config()->set('wa.auth_dev_token', env('WA_DEV_AUTH_KEY'));
        config()->set('wa.dev_driver', "cipta");

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
    public function test_driver()
    {
        $wa = WA::getDriver();
        // fwrite(STDOUT,$wa);
        $this->assertTrue($wa=="cipta");
    }
    public function test_dev_msg()
    {
        $wa = WA::dev()->send("test message wa library, dev chanel");
        $wa->each(function ($item,$key) {
            fwrite(STDOUT,gettype($item));
            fwrite(STDOUT,$item);
            // $this->assertTrue(true);
            $this->assertTrue(json_decode($item,true)['message_status']=="Success");
        });
    }
}
