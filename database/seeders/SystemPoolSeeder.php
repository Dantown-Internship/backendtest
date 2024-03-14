<?php

namespace Database\Seeders;

use App\Models\SystemPool;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SystemPoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $systemPool = [
            [
                'name' => config('constant.system_pool.name'), 
                'balance' => config('constant.system_pool.balance')
            ],
        ];
        SystemPool::insert($systemPool);
    }
}
