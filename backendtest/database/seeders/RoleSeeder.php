<?php

namespace Database\Seeders;

use App\Models\User;
use App\Enums\UserType;
use App\Enums\AccountType;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    // David changes
    public function run(): void
    {
        Role::firstOrCreate(['name' => 'Maker']);
        Role::firstOrCreate(['name' => 'Checker']);

        $role = Role::firstOrCreate(['name' => 'SuperAdmin']);
        $role->syncPermissions(Permission::all());

        $maker = Role::where('name', 'Maker')->first();
        $maker->givePermissionTo('create transactions');

        $checker = Role::where('name', 'Checker')->first();
        $checker->givePermissionTo('approve transactions');
        $checker->givePermissionTo('system_pool view');
    }
}
