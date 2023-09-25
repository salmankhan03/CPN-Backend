<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $roles = [
            ['name' => 'Admin', 'guard_name' => ''],
            ['name' => 'Manager', 'guard_name' => '']
        ];

        DB::table('roles')->insert($roles);
    }
}
