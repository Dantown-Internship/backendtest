<?php

namespace Database\Seeders;

use App\Models\SystemPoolBalance;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DefaultUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user =   User::create([
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'password'=>Hash::make('12345678')
        ]);
        $user->wallet()->create();
        $role = Role::where('name', 'SuperAdmin')->first();
        $user->syncRoles($role);

        $maker =   User::create([
            'name' => 'Maker User',
            'email' => 'maker@gmail.com',
            'password'=>Hash::make('12345678')
        ]);
        $maker->wallet()->create();
        $role = Role::where('name', 'Maker')->first();
        $maker->syncRoles($role);

        $checker =   User::create([
            'name' => 'Checker User',
            'email' => 'checker@gmail.com',
            'password'=>Hash::make('12345678')
        ]);
        $checker->wallet()->create();
        $role = Role::where('name', 'Checker')->first();
        $checker->syncRoles($role);


        SystemPoolBalance::firstOrCreate(['pool_balance' => 5500500]);
    }
}
