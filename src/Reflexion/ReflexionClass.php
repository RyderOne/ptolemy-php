<?php

namespace PtolemyPHP\Reflexion;

class ReflexionClass
{
    protected $name;
    protected $methods = [];
    protected $properties = [];
    protected $namespace;

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
     * Sets the value of name.
     *
     * @param mixed $name the name
     *
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
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

    /**
     * Sets the value of methods.
     *
     * @param mixed $methods the methods
     *
     * @return self
     */
    public function setMethods($methods)
    {
        $this->methods = $methods;

        return $this;
    }

    public function addMethod(ReflexionMethod $method)
    {
        $this->methods[] = $method;
        $method->setClass($this);
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

    /**
     * Sets the value of properties.
     *
     * @param mixed $properties the properties
     *
     * @return self
     */
    public function setProperties($properties)
    {
        $this->properties = $properties;

        return $this;
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

    /**
     * Sets the value of namespace.
     *
     * @param mixed $namespace the namespace
     *
     * @return self
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;

        return $this;
    }

    public function getKey()
    {
        return $this->getNamespace().'\\'.$this->getName();
    }

    public function toJsonArray()
    {
        $json = [
            'name' => $this->name,
            'key' => $this->getKey(),
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
