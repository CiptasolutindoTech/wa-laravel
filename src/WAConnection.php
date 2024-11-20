<?php
namespace App\Helpers\WA;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Collection;

class WAConnection {
  protected $driver;
  protected $to;
  function __construct($to=null,$driver='cipta') {
      $this->driver = $driver;
      $this->to = $to;
  }
  private static function url(){
    if(empty(config('app.wa_api_url'))){
        throw new \Exception('Whatsapp api url cant be empty');
    }
    return config('app.wa_api_url');
  }
  private function sendUrl(){
    if($this->driver==='cipta'){
      return $this->url().'/create-message';
    }else if($this->driver ==='waSender'){
      return $this->url().'/send_message';
    }else{
      throw new \Exception('Invalid Whatsapp Driver');
    }
  }
  private function qrUrl(){
    if($this->driver==='cipta'){
      return $this->url().'/qr';
    }else if($this->driver ==='waSender'){
      return $this->url().'/send_message';
    }else{
      throw new \Exception('Invalid Whatsapp Driver');
    }
  }
  private static function appToken(){
    if(empty(config('app.wa_app_token'))){
        throw new \Exception('Whatsapp app token cant be empty');
    }
    return config('app.wa_app_token');
  }
  private static function authToken(){
    if(empty(config('app.wa_auth_token'))){
        throw new \Exception('Whatsapp auth token cant be empty');
    }
    return config('app.wa_auth_token');
  }
  private function connect(){
    if($this->driver==='cipta'){
      return new HTTP();
    }else if($this->driver ==='waSender'){
      return HTTP::withHeaders([ 'Accept'=>'application/json','Content-Type'=>'application/x-www-form-urlencoded']);
    }else{
      throw new \Exception('Invalid Whatsapp Driver');
    }
  }
  private function body($message){
    if($this->driver==='cipta'){
      return $this->auth()->merge(['message'=>$message,'to'=>$this->to])->toArray();
    }else if($this->driver ==='waSender'){
      return $this->auth()->merge(['message'=>$message,'number'=>$this->to])->toArray();
    }else{
      throw new \Exception('Invalid Whatsapp Driver');
    }
  }
  private function auth(){
    if($this->driver==='cipta'){
      return collect(['appkey'=>self::appToken(),'authkey'=>self::authToken()]);
    }else if($this->driver ==='waSender'){
      return collect(['token'=>self::appToken()]);
    }else{
      throw new \Exception('Invalid Whatsapp Driver');
    }
  }
  public function post($message){
        // return self::url().'/create-message';
        // return self::auth()->merge($content)->toArray();
          return HTTP::post($this->sendUrl(),$this->body($message));
  }
  public function qr(){
    if($this->driver==='cipta'){
      return HTTP::withToken($this->authToken())->post($this->qrUrl(),['id'=> $this->appToken()]);
    }else if($this->driver ==='waSender'){
      return false;
    }else{
      throw new \Exception('Invalid Whatsapp Driver');
    }
  }
}
