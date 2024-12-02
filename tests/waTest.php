<?php

namespace Tests\Feature;

use Cst\WALaravel\WA;
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
        config()->set('wa.api_url', env("WA_SERVER_API_URL"));
        config()->set('wa.test_numbers', env("TEST_PHONE_NUMBERS"));
        config()->set('wa.test_message', "Test WA ".env('APP_NAME'));
     }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        // $phoneNumber = env('TEST_PHONE_NUM_OTP');
        // if(empty($phoneNumber)){
        //     \trigger_error("Please specify Phone number in env 'TEST_PHONE_NUM_OTP'", \E_USER_ERROR);
        // }
        $wa = '';
        $wa = WA::test();
        fwrite(STDOUT,$wa);
        $this->assertTrue(true);
    }
}
