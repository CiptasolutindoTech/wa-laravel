<?php
namespace Devlagret\WALaravel\lib;
use Illuminate\Support\Facades\Http;

class Cipta extends Connection {
   protected $driver='cipta';


   /**
    * Get the value of driver
    */
   public function getDriver()
   {
      return $this->driver;
   }
}
