<?php

namespace PtolemyPHP;

class Utility
{
    private static $debugMode = false;

    public static function setDebugMode(bool $mode)
    {
        self::$debugMode = $mode;
    }

    public static function dump(...$args)
    {
        if (self::$debugMode) {
            dump(...$args);
        }
    }
}