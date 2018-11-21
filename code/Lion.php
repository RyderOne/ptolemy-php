<?php

namespace Animal;

class Lion implements AnimalInterface
{
    protected $size;
    protected $life = 30;
    protected $attack = 5;

    /**
     * Gets the value of size.
     *
     * @return mixed
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Sets the value of size.
     *
     * @param mixed $size the size
     *
     * @return self
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    public function decreaseLife($amount)
    {
        $this->life -= $amount;
    }

    public function fight(AnimalInterface $animal)
    {
        $animal->decreaseLife($this->getAttack());
    }

    /**
     * Gets the value of attack.
     *
     * @return mixed
     */
    public function getAttack()
    {
        return $this->attack;
    }
}
