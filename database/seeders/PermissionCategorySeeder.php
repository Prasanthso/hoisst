<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {

        DB::table('permission_category')->insert([
            ['categoryName' => 'Dashboard'],
            ['categoryName' => 'Masters'],
            ['categoryName' => 'Recipe'],
            ['categoryName' => 'Recipe Pricing'],
            ['categoryName' => 'Report'],
            ['categoryName' => 'Permission'],
            ['categoryName' => 'Role'],
        ]);
    }
}
