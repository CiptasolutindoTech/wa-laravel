<?php
namespace Cst\WALaravel\lib;
use Illuminate\Support\Facades\Http;

class RuangWa extends Connection {
    protected $driver='waSender';
    /**
    * Get the value of driver
    */
    protected function url(){
        if(empty(config("wa.ruang_wa_url"))){
            throw new \Exception('Whatsapp api url cant be empty');
        }
        return config("wa.ruang_wa_url");
      }
}
