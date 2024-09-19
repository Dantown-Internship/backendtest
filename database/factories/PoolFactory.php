<?php

namespace Database\Factories;

use App\Models\Pool;
use Illuminate\Database\Eloquent\Factories\Factory;

class PoolFactory extends Factory
{
    protected $model = Pool::class;

    public function definition()
    {
        return [
            'balance' => $this->faker->randomFloat(2, 0, 10000),
        ];
    }
}
