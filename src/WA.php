<?php
namespace App\Helpers\WA;

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Str;


class WA {
    protected $to;
    protected $driver;
    function __construct($to) {
        $this->to = $to;
        $this->driver = env('WA_DRIVER','cipta');
    }
    /**
     * Send mesage
     *
     * @param string $message
     * @return \Illuminate\Http\Client\Response
     */
    public function send($message){
        // return self::phone($phones);
        $driver = $this->driver??'cipta';

        $connec = new WAConnection(self::phone($this->to),$driver);
       return $connec->post($message);
    }
    /**
     * Send mesage
     *
     * @param string $message
     * @return \Illuminate\Http\Client\Response
     */
    public function msg($message){
       return $this->send($message);
    }
    public function phone($phones){
        $phones=str_replace('-','',$phones);
        if(Str::is('+*', $phones)){
            $phones=str_replace('+','',$phones);
        }elseif (Str::is('08*', $phones)) {
            $phones = Str::replaceFirst('0','62', $phones);
        }
        if(strlen($phones)<10){
            throw new \Exception("Phone Number Invalid");
        }
        return $phones;
    }
    /**
     * Set WA driver
     *
     * @param string $driver
     * @return WA
     */
    public function driver($driver){
        $this->driver=$driver;
       return  $this;
    }
    /**
     * WA Number
     *
     * @param mixed $phone
     * @return WA
     */
    public static function to($phone){
       return  new self($phone);
    }
    public static function qr($driver='cipta'){
        $connec = new WAConnection(null,$driver);
        return $connec->qr();
    }
    /**
     * Send defaut test mesage to test number
     * Test number can be configured from environment variable using
     *
     * @param string $message
     * @return \Illuminate\Http\Client\Response
     */
    public static function test($message=null){
        if(is_null($message)){
            $message="
            Tes Whatapp Api - ".env('APP_NAME')."
            Laravel Version - ".app()->version()."
            PHP Version - ".phpversion()."
            ";
        }
       return self::to(env('TEST_PHONE_NUM_OTP'))->send($message);
    } /**
    * Send inspiring mesage
    *
    * @param string $message
    * @return \Illuminate\Http\Client\Response
    */
   public function inspire(){
        return self::send(Inspiring::quote());
   }
}
