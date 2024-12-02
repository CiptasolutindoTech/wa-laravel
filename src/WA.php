<?php
namespace Cst\WALaravel;

use Illuminate\Support\Str;
use Cst\WALaravel\lib\Cipta;
use Cst\WALaravel\lib\RuangWa;
use Illuminate\Foundation\Inspiring;


/**
 * @method static Cst\WALaravel\lib\Connection to(string $contentType)
 * @method static \Illuminate\Http\Client\Response test()
 * @method static \Illuminate\Http\Client\Response inspire()
 * @method static \Illuminate\Http\Client\Response msg(string $message)
*
 * @see \Cst\WALaravel\lib\Cipta
 * @see \Cst\WALaravel\lib\Connection
 * @see \Cst\WALaravel\lib\Wasnder
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
