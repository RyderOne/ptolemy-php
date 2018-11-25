<?php

namespace PtolemyPHP\Reflexion;

class ReflexionClass
{
    protected $name;
    protected $methods = [];
    protected $properties = [];
    protected $namespace;
    protected $key;

    public function __construct(string $namespace, string $name)
    {
        $this->name = $name;
        $this->namespace = $namespace;
        $this->key = $namespace.'\\'.$name;
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
     * Gets the value of methods.
     *
     * @return mixed
     */
    public function getMethods()
    {
        return $this->methods;
    }

    public function addMethod(ReflexionMethod $method)
    {
        $this->methods[] = $method;
    }

    public function hasMethod($name)
    {
        foreach ($this->methods as $method) {
            if ($method->getName() == $name) {
                return true;
            }
        }
        return false;
    }

    public function getMethodByName($name)
    {
        foreach ($this->methods as $method) {
            if ($method->getName() === $name) {
                return $method;
            }
        }
        return null;
    }

    /**
     * Gets the value of properties.
     *
     * @return mixed
     */
    public function getProperties()
    {
        return $this->properties;
    }

    public function addProperty($property)
    {
        $this->properties[] = $property;
    }

    /**
     * Gets the value of namespace.
     *
     * @return mixed
     */
    public function getNamespace()
    {
        return $this->namespace;
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
            'namespace' => $this->namespace,
            'methods' => [],
        ];

        /** @var ReflexionMethod $method */
        foreach ($this->methods as $method) {
            $json['methods'][] = $method->toJsonArray();
        }

        return $json;
    }
}
