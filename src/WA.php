<?php
namespace Devlagret\WALaravel;

use Illuminate\Support\Str;
use Devlagret\WALaravel\lib\Cipta;
use Devlagret\WALaravel\lib\RuangWa;
use Illuminate\Foundation\Inspiring;


/**
 * @method static \Devlagret\WALaravel\lib\Connection to(string $contentType)
 * @method static \Devlagret\WALaravel\lib\Connection test()
 * @method static \Devlagret\WALaravel\lib\Connection msg(string $message)
*
 * @see \Devlagret\WALaravel\lib\Cipta
 * @see \Devlagret\WALaravel\lib\Connection
 * @see \Devlagret\WALaravel\lib\Wasnder
 */

class WA {
    /**
     * Set WA driver
     *
     * @param string $driver
     */
    public static function driver($driver){
        switch ($driver) {
            case 'cipta':
                return new Cipta;
            case 'ruangWa':
                return new RuangWa;
            default:
                return new Cipta;
        }
    }
    public static function __callStatic($method, $parameters)
    {
        return self::driver(config('wa.driver','cipta'))->{$method}(...$parameters);
    }
}
