<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Checker',
            'email' => 'checker@check.com',
            'role_id' => Role::firstWhere('name', 'checker')->id,
            'password' => Hash::make('password')
        ]);


        $user = User::factory()->create([
            'name' => 'Marker',
            'email' => 'marker@mark.com',
            'role_id' => Role::firstWhere('name', 'marker')->id,
            'password' => Hash::make('password')
        ]);

        Wallet::create(['user_id' => $user->id ]);
    }
}
