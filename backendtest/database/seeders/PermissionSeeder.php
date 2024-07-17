<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $i = 0;

        $permissionArray[$i]['name']       = 'create transactions';
        $i++;
        $permissionArray[$i]['name']       = 'approve transactions';
        $i++;
        $permissionArray[$i]['name']       = 'system_pool view';

        foreach($permissionArray as $key=>$value){
            Permission::firstOrCreate([
                'name'=>$value['name']
            ]);
         }


    }
}

