<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Pool;
use Spatie\Permission\Models\Role;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        Role::create(['name' => 'maker']);
        Role::create(['name' => 'checker']);

        $maker = User::create([
            'name' => 'Mark Tenny',
            'email' => 'mark@example.com',
            'password' => bcrypt('password'),
        ]);
        $maker->assignRole('maker');

        $checker = User::create([
            'name' => 'Emma Loveday',
            'email' => 'loveday@example.com',
            'password' => bcrypt('password'),
        ]);
        $checker->assignRole('checker');

        Pool::create([
            'balance' => 0.00,
        ]);
    }
}
