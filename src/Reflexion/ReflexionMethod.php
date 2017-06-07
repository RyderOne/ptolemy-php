<?php

namespace PtolemyPHP\Reflexion;

class ReflexionMethod
{
    protected $name;
    protected $relations = [];
    protected $arguments = [];
    protected $class;

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
     * Gets the value of relations.
     *
     * @return mixed
     */
    public function getRelations()
    {
        return $this->relations;
    }

    /**
     * Sets the value of relations.
     *
     * @param mixed $relations the relations
     *
     * @return self
     */
    public function setRelations($relations)
    {
        $this->relations = $relations;

        return $this;
    }

    public function addRelation(ReflexionMethod $relation)
    {
        $this->relations[] = $relation;
    }

    /**
     * Gets the value of arguments.
     *
     * @return mixed
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * Sets the value of arguments.
     *
     * @param mixed $arguments the arguments
     *
     * @return self
     */
    public function setArguments($arguments)
    {
        $this->arguments = $arguments;

        return $this;
    }

    /**
     * Gets the value of class.
     *
     * @return mixed
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Sets the value of class.
     *
     * @param mixed $class the class
     *
     * @return self
     */
    public function setClass(ReflexionClass $class)
    {
        $this->class = $class;

        return $this;
    }
}
