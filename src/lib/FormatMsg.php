<?php

namespace Cst\WALaravel\lib;

use Illuminate\Support\Str;

/**
 * Format/form message for wa
 */
class FormatMsg extends Str{
    public static function bold($message) {
        return "*{$message}*";
    }
    public static function italic($message) {
        return "_{$message}_";
    }
    public static function strike($message) {
        return "~{$message}~";
    }
    public static function monospace($message) {
        return "```{$message}```";
    }
}
