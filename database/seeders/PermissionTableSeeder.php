<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('permissions')->insert(
            ['name' => 'Product Add-Edit', 'guard_name' => 'api'],
            ['name' => 'Product Delete', 'guard_name' => 'api'],
            ['name' => 'Product View', 'guard_name' => 'api'],
            ['name' => 'Product Category Add-Edit', 'guard_name' => 'api'],
            ['name' => 'Product Category Delete', 'guard_name' => 'api'],
            ['name' => 'Product Category View', 'guard_name' => 'api'],
        );
    }
}
