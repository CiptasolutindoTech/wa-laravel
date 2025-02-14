<?php

namespace Cst\WALaravel;

use Illuminate\Support\Str;
use Cst\WALaravel\lib\Cipta;
use Cst\WALaravel\lib\RuangWa;
use Illuminate\Foundation\Inspiring;

 /**
 *
 * @method static \Cst\WALaravel\lib\Connection to(string|array $phone) Set the receiver number.
 * @method static \Illuminate\Http\Client\Response test() Perform a test request and return the response.
 * @method static \Cst\WALaravel\lib\Development dev() Access development-related functionalities or send message to dev.
 * @method static \Illuminate\Http\Client\Response inspire() Send an inspirational quote.
 *
 * @see \Cst\WALaravel\lib\Cipta
 * @see \Cst\WALaravel\lib\Connection
 * @see \Cst\WALaravel\lib\Development
 * @see \Cst\WALaravel\lib\Wasnder
 *
 * Send a message and return the response.
 * @method static \Illuminate\Http\Client\Response msg(string|array $message)
 * For array parameter, the format should be: [receiver=>message, receiver=>message, ...].
 * or : [["to"=>receiver,"msg"=>message],["to"=>receiver,"msg"=>message], ...].
 */
class WA
{
    /**
     * Set WA driver
     *
     * @param string $driver
     */
    public static function driver($driver)
    {
        switch ($driver) {
            case 'cipta':
                return new Cipta;
            case 'ruangWa':
                return new RuangWa;
            default:
                return new Cipta;
        }
    }
    /**
     * Use RuangWa driver
     * @return RuangWa
     */
    public static function ruangWa()
    {
        return new RuangWa;
    }
    /**
     * Use Cipta driver
     * @return Cipta
     */
    public static function cipta()
    {
        return new Cipta;
    }
    public static function __callStatic($method, $parameters)
    {
        return self::driver(config('wa.driver', 'cipta'))->{$method}(...$parameters);
    }
}
