<?php

namespace PtolemyPHP\Store;

use PtolemyPHP\Reflexion\ReflexionClass;
use PtolemyPHP\Reflexion\ReflexionMethod;

class CallStore
{
    public static $calls = [];

    public static $currentClass;
    public static $currentNamespace = '';
    public static $currentMethod;

    public static $classes = [];

    public static function startNamespace($namespace)
    {
        self::$currentNamespace = $namespace;
    }

    public static function startClass($name)
    {
        $key = self::$currentNamespace.'\\'.$name;
        if (!isset(self::$classes[$key])) {
            self::$classes[$key] = (new ReflexionClass())
                ->setNamespace(self::$currentNamespace)
                ->setName($name)
            ;
        }

        self::$currentClass = self::$classes[$key];
    }

    public static function startMethod($name)
    {
        $class = self::getCurrentClass();
        $method = null;

        if (!$class->hasMethod($name)) {
            $method = (new ReflexionMethod())
                ->setName($name)
                ->setClass($class)
            ;
            $class->addMethod($method);
        }

        self::$currentMethod = $name;

        self::setClass($class);
    }

    public static function addProperty($name)
    {
        $class = self::getCurrentClass();
        $class->addProperty($name);

        self::setClass($class);
    }

    public static function getCurrentClass()
    {
        if (self::$currentClass === null) {
            throw new \Exception('Current class not set');
        }

        if (!isset(self::$classes[self::$currentKey])) {
            throw new \Exception(sprintf('Class with key %s not found', self::$currentKey));
        }

        return self::$classes[self::$currentKey];
    }

    /**
     * Return the class or create one if not found, thanks to his $namespace + $name
     */
    public static function getClass($name)
    {
        $key = self::$currentNamespace.'\\'.$name;
        if (!isset(self::$classes[$key])) {
            self::$classes[$key] = (new ReflexionClass())
                ->setNamespace(self::$currentNamespace)
                ->setName($name)
            ;
        }

        return self::$classes[$key];
    }

    public static function setClass(ReflexionClass $class)
    {
        self::$classes[$class->getKey()] = $class;
        return self;
    }
}
