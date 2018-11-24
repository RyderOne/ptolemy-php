<?php

namespace PtolemyPHP\Store;

use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Param;
use PtolemyPHP\Reflexion\ReflexionClass;
use PtolemyPHP\Reflexion\ReflexionMethod;

class CallStore
{
    public static $currentMethodName;
    public static $currentClassKey;
    public static $currentNamespace;

    public static $classes = [];

    public static function startNamespace($namespace)
    {
        // When a visitor enter a namespace, update the current one so the next class entering can have it
        self::$currentNamespace = $namespace;
    }

    public static function endNamespace()
    {
        // When a visitor enter a namespace, update the current one so the next class entering can have it
        self::$currentNamespace = null;
    }

    public static function startClass($name)
    {
        // We need to store the class in our internal store, we may have reference to it later
        $classKey = self::$currentNamespace.'\\'.$name;

        if (!isset(self::$classes[$classKey])) {
            self::$classes[$classKey] = (new ReflexionClass())
                ->setNamespace(self::$currentNamespace)
                ->setName($name)
            ;
        }

        self::$currentClassKey = $classKey;
    }

    public static function endClass()
    {
        self::$currentClassKey = null;
    }

    public static function startMethod($name, $params)
    {
        $class = self::getCurrentClass();

        if (!$class instanceof ReflexionClass) {
            return;
        }

        if (!$class->hasMethod($name)) {
            $method = (new ReflexionMethod())
                ->setName($name)
                ->setClass($class)
            ;
            $class->addMethod($method);

            /** @var Param $param */
            foreach ($params as $param) {
                if ($param->type instanceof FullyQualified) {
                    $method->addArgument([
                        'name' => $param->name,
                        'type' => $param->type->toString()
                    ]);
                }
            }
        }

        self::$currentMethodName = $name;
    }

    public static function endMethod()
    {
        self::$currentMethodName = null;
    }

    public static function handleMethodCall($varName, $methodName)
    {
        /** @var ReflexionMethod $method */
        $method = self::getCurrentMethod();

        if ($method === null) {
            return;
        }

        $argument = $method->getArgumentByName($varName);
        if ($argument !== null) {
            $method->addRawRelation($argument['type'], $methodName);
        }
    }

    public static function addProperty($name)
    {
        $class = self::getCurrentClass();

        if (!$class instanceof ReflexionClass) {
            return;
        }

        $class->addProperty($name);
    }

    /**
     * @return mixed
     * @throws ReflexionClass|\Exception If no class have been entered yet
     */
    public static function getCurrentClass()
    {
        return self::getClassByKey(self::$currentClassKey);
    }

    public static function getClassByKey($classKey)
    {
        if (!isset(self::$classes[$classKey])) {
            return null;
        }

        return self::$classes[$classKey];
    }

    /**
     * @return mixed
     * @throws ReflexionMethod|\Exception If no method have been entered yet
     */
    public static function getCurrentMethod()
    {
        $currentClass = self::getCurrentClass();
        if ($currentClass === null) {
            return null;
        }

        return $currentClass->getMethodByName(self::$currentMethodName);
    }

    public static function resolveRawRelations()
    {
        /** @var ReflexionClass $reflexionClass */
        foreach (self::$classes as $reflexionClass) {
            /** @var ReflexionMethod $reflexionMethod */
            foreach ($reflexionClass->getMethods() as $reflexionMethod) {
                /** @var array $rawRelation */
                foreach ($reflexionMethod->getRawRelations() as $rawRelation) {
                    $targetClass = self::getClassByKey($rawRelation['classKey']);
                    if (!$targetClass instanceof ReflexionClass) {
                        continue;
                    }

                    $targetMethod = $targetClass->getMethodByName($rawRelation['methodName']);
                    if (!$targetMethod instanceof ReflexionMethod) {
                        continue;
                    }

                    $reflexionMethod->addRelation($targetMethod);
                }
            }
        }
    }

    public static function dumpRelations()
    {
        /** @var ReflexionClass $reflexionClass */
        foreach (self::$classes as $reflexionClass) {
            /** @var ReflexionMethod $reflexionMethod */
            foreach ($reflexionClass->getMethods() as $reflexionMethod) {
                /** @var ReflexionMethod $relation */
                foreach ($reflexionMethod->getRelations() as $relation) {
                    self::dumpRelation($reflexionMethod, $relation);
                }
            }
        }
    }

    private static function dumpRelation(ReflexionMethod $caller, ReflexionMethod $callee)
    {
        dump($caller->getClass()->getKey().'->'.$caller->getName().'() ===> '.$callee->getClass()->getKey().'->'.$callee->getName().'()');
    }

    public static function toJsonArray()
    {
        $json = [];
        /** @var ReflexionClass $reflexionClass */
        foreach (self::$classes as $reflexionClass) {
            $json[] = $reflexionClass->toJsonArray();
        }

        return $json;
    }
}
