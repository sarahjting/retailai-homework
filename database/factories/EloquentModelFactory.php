<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class EloquentModelFactory extends Factory
{
    abstract static function new(): Factory;
    abstract function create(): Model;

    public function createMany(int $number): Collection {
        $res = new Collection();
        for ($i = 0; $i < $number; $i ++) {
            $res[] = $this->create();
        }
        return $res;
    }
}
