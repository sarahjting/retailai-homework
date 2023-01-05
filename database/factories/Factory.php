<?php

namespace Database\Factories;

abstract class Factory
{
    abstract static function new(): Factory; // we don't implement it here for better IDE autocompletion
    abstract function create();
    abstract function createMany(int $number);
}
