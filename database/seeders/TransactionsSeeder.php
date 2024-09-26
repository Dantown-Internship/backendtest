<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransactionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Transaction::create([
            'amount' => 1000,
            'description' => 'Test Seeder',
            'type' => 'debit',
            'user_id' => User::firstWhere('role_id', Role::firstWhere('name', 'marker')->id )->id
        ]);
    }
}
