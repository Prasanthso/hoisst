<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {

        DB::table('permission_menu')->insert([
            ['permission_category_id'=>'2','menuName' => 'Raw Material'],
            ['permission_category_id' => '2','menuName' => 'Packing Material'],
            ['permission_category_id' => '2','menuName' => 'Overheads'],
            ['permission_category_id' => '2','menuName' => 'Products'],
            ['permission_category_id' => '2','menuName' => 'Category'],
            ['permission_category_id' => '3','menuName' => 'Details & Description'],
            ['permission_category_id' => '3','menuName' => 'Pricing'],
            ['permission_category_id' => '3','menuName' => 'Overall Costing'],
        ]);
    }
}
