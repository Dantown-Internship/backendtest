<?php

namespace Database\Factories;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition()
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'type' => $this->faker->randomElement(['credit', 'debit']),
            'amount' => $this->faker->randomFloat(2, 1, 1000),
            'description' => $this->faker->sentence,
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
        ];
    }
}
