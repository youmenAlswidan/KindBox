<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run()
    {
        DB::table('roles')->insert([
            ['id' => 1, 'role_name' => 'customer'],
            ['id' => 2, 'role_name' => 'driver'],
            ['id' => 3, 'role_name' => 'shop_manager'],
        ]);
    }
}