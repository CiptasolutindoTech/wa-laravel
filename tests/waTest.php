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
        // $phoneNumber = env('TEST_PHONE_NUM_OTP');
        // if(empty($phoneNumber)){
        //     \trigger_error("Please specify Phone number in env 'TEST_PHONE_NUM_OTP'", \E_USER_ERROR);
        // }
        $wa = '';
        $wa = WA::test();
        fwrite(STDOUT,$wa);
        $this->assertTrue(true);
    }
    public function test_sending_messsage_to_dev()
    {
        $wa = '';
        $wa = WA::dev()->stacktrace((new Exception('Test')))->test();
        $wa->each(function ($item,$key) {
            // fwrite(STDOUT,$item);
            $this->assertTrue(json_decode( $item,true)['message_status']=="Success");
        });
    }
    public function test_sending_repot_to_dev()
    {
        try{
            route("testerrorexception");
        }catch(Exception $e){
            $wa = WA::dev()->report($e);
        }
        $wa->each(function ($item,$key) {
            // fwrite(STDOUT,$item);
            $this->assertTrue(json_decode($item,true)['message_status']=="Success");
        });
    }
    public function test_warning_sending_repot_to_dev()
    {
        try{
            route("testwarningexception");
        }catch(Exception $e){
            $wa = WA::dev()->warning()->report($e);
        }
        $wa->each(function ($item,$key) {
            // fwrite(STDOUT,$item);
            $this->assertTrue(json_decode($item,true)['message_status']=="Success");
        });
    }
    public function test_info_sending_repot_to_dev()
    {
        try{
            route("testinfoexception");
        }catch(Exception $e){
            $wa = WA::dev()->info()->report($e);
        }
        $wa->each(function ($item,$key) {
            // fwrite(STDOUT,$item);
            $this->assertTrue(json_decode($item,true)['message_status']=="Success");
        });
    }
    public function test_error_sending_repot_to_dev()
    {
        try{
            route("testerrorexception2");
        }catch(Exception $e){
            $wa = WA::dev()->error()->report($e);
        }
        $wa->each(function ($item,$key) {
            // fwrite(STDOUT,$item);
            $this->assertTrue(json_decode($item,true)['message_status']=="Success");
        });
    }
}
