<?php
namespace Cst\WALaravel\lib;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Http;

class Connection {
  protected $driver;
  protected $authToken;
  protected $appToken;
  protected $serverUrl;
  public $to;
  function __construct($to=null,$driver='cipta') {
      $this->driver = $driver;
      $this->to = $to;
  }
  protected function url(){
    if(empty(($this->serverUrl??config("wa.api_url")))){
        throw new \Exception('Whatsapp api url cant be empty');
    }
    return $this->serverUrl??config("wa.api_url");
  }
  protected function sendUrl(){
    if($this->driver==='cipta'){
      return $this->url().'/create-message';
    }else if($this->driver ==='ruangWa'){
      return $this->url().'/send_message';
    }else{
      throw new \Exception('Invalid Whatsapp Driver');
    }
  }
  protected function qrUrl(){
    if($this->driver==='cipta'){
      return $this->url().'/qr';
    }else if($this->driver ==='ruangWa'){
      return $this->url().'/send_message';
    }else{
      throw new \Exception('Invalid Whatsapp Driver');
    }
  }
  public function appToken(){
    switch ($this->driver) {
        case 'cipta':
            $configName = 'app_token';
            break;
        case 'ruangWa':
            $configName = 'ruang_wa_token';
            break;
        default:
            $configName = 'app_token';
        break;
    }
    if(empty(($this->appToken??config("wa.{$configName}")))){
        throw new \Exception("Whatsapp app token can't be empty");
    }
    return $this->appToken??config("wa.{$configName}");
  }
  public function authToken(){
    if(empty(($this->authToken??config("wa.auth_token")))){
        throw new \Exception("Whatsapp auth token can't be empty");
    }
    return $this->authToken??config('wa.auth_token');
  }
  protected function connect(){
    if($this->driver==='cipta'){
      return new HTTP();
    }else if($this->driver ==='ruangWa'){
      return HTTP::withHeaders([ 'Accept'=>'application/json','Content-Type'=>'application/x-www-form-urlencoded']);
    }else{
      throw new \Exception('Invalid Whatsapp Driver');
    }
  }
  protected function body($message){
    if($this->driver==='cipta'){
      return $this->auth()->merge(['message'=>$message,'to'=>$this->to])->toArray();
    }else if($this->driver ==='ruangWa'){
      return $this->auth()->merge(['message'=>$message,'number'=>$this->to])->toArray();
    }else{
      throw new \Exception('Invalid Whatsapp Driver');
    }
  }
  private function auth(){
    if($this->driver==='cipta'){
      return collect(['appkey'=>$this->appToken(),'authkey'=>$this->authToken()]);
    }else if($this->driver ==='ruangWa'){
      return collect(['token'=>$this->appToken()]);
    }else{
      throw new \Exception('Invalid Whatsapp Driver');
    }
  }
  protected function post($message){
        if(empty($this->to)){
            throw new \Exception("Phone number can't be empty");
        }
        return HTTP::post($this->sendUrl(),$this->body($message));
  }
  public function qr(){
    if($this->driver==='cipta'){
      return HTTP::withToken($this->authToken())->post($this->qrUrl(),['id'=> $this->appToken()]);
    }else if($this->driver ==='ruangWa'){
      return false;
    }else{
      throw new \Exception('Invalid Whatsapp Driver');
    }
  }
public function to($phone){
    return tap($this,function() use($phone){
        $this->to = $this->formatPhone($phone);
    });
}
 public function formatPhone($phones){
    $phones=str_replace(['-',' ','/'],'',$phones);
    if(Str::is('+*', $phones)){
        $phones=str_replace('+','',$phones);
    }elseif (Str::is('08*', $phones)) {
        $phones = Str::replaceFirst('0','62', $phones);
    }
    if(strlen($phones)<10){
        throw new \Exception("Phone Number Invalid : {$phones}");
    }
    return $phones;
}

  /**
   * Get the value of driver
   */
  public function getDriver()
  {
    return $this->driver;
  }

  /**
   * Set the value of driver
   *
   * @return  self
   */
  public function setDriver($driver)
  {
    return tap($this,function() use($driver){
        $this->driver = trim($driver);
    });
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
    /**
     * Send mesage
     *
     * @param string $message
     * @return \Illuminate\Http\Client\Response
     */
    public function send($message){
       return $this->post($message);
    }
    /**
     * Send test mesage
     *
     * @param string $message
     * @return \Illuminate\Http\Client\Response
     */
    public function test(){
        if(empty($this->to)){
            $this->to =$this->formatPhone(config("wa.test_numbers"));
        }
       return $this->msg(config('wa.test_message'));
    }
    public function inspire(){
        if(empty($this->to)){
            $this->to =$this->formatPhone(config("wa.test_numbers"));
        }
       return $this->msg(Inspiring::quote());
    }
    public function dev() {
        return new Development($this);
    }
    public function toDev() {
        return $this->dev();
    }

  /**
   * Set the value of authToken
   *
   * @return  self
   */
  public function setAuthToken($authToken)
  {
    $this->authToken = $authToken;

    return $this;
  }

  /**
   * Set the value of appToken
   *
   * @return  self
   */
  public function setAppToken($appToken)
  {
    $this->appToken = $appToken;

    return $this;
  }

  /**
   * Set the value of serverUrl
   *
   * @return  self
   */
  public function setServerUrl($serverUrl)
  {
    $this->serverUrl = $serverUrl;

    return $this;
  }
}
