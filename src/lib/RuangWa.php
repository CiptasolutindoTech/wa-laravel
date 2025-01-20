<?php
namespace Cst\WALaravel\lib;
use Illuminate\Support\Facades\Http;

class RuangWa extends Connection {
    public $driver='ruangWa';
    /**
    * Get the value of driver
    */
    public function url(){
        if($this->serverUrl??empty(config("wa.ruang_wa_url"))){
            throw new \Exception('Whatsapp api url cant be empty');
        }
        return $this->serverUrl??config("wa.ruang_wa_url");
      }
}
