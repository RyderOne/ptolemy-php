<?php

namespace Animal;

interface AnimalInterface
{
    public function fight(AnimalInterface $animal);
    public function decreaseLife($amount);
}