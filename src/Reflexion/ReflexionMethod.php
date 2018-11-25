<?php

namespace PtolemyPHP\Reflexion;

class ReflexionMethod
{
    protected $name;
    protected $relations = [];
    protected $rawRelations = [];
    protected $arguments = [];
    protected $class;
    protected $key;

    public function __construct(ReflexionClass $class, string $name)
    {
        $this->class = $class;
        $this->name = $name;
        $this->key = $class->getKey().'->'.$name.'()';
        $this->addArgument($this->class->getKey(), 'this');
    }

    /**
     * Gets the value of name.
     *
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Gets the value of relations.
     *
     * @return mixed
     */
    public function getRelations()
    {
        return $this->relations;
    }

    public function addRelation(ReflexionMethod $relation)
    {
        $this->relations[] = $relation;
    }

    public function addRawRelation(array $argument, string $methodName)
    {
        $this->rawRelations[] = [
            'classKey' => $argument['classKey'],
            'methodName' => $methodName
        ];
    }

    public function getRawRelations()
    {
        return $this->rawRelations;
    }

    public function addArgument(string $classKey, string $name)
    {
        $this->arguments[] = [
            'classKey' => $classKey,
            'name' => $name
        ];
    }

    public function getArgumentByName($name)
    {
        foreach ($this->arguments as $argument) {
            if ($argument['name'] === $name) {
                return $argument;
            }
        }

        return null;
    }

    /**
     * Gets the value of class.
     *
     * @return ReflexionClass
     */
    public function getClass()
    {
        return $this->class;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function toJsonArray()
    {
        $json = [
            'name' => $this->name,
            'key' => $this->key,
            'relations' => [],
        ];

        /** @var ReflexionMethod $method */
        foreach ($this->relations as $method) {
            if (!isset($json['relations'][$method->getKey()])) {
                $json['relations'][$method->getKey()] = 0;
            }
            $json['relations'][$method->getKey()]++;
        }

        return $json;
    }
}
