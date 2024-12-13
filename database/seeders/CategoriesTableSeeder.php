<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->insert([
            ['categoryname' => 'Raw Materials'],
            ['categoryname' => 'Packing Materials'],
            ['categoryname' => 'Overheads'],
            ['categoryname' => 'Products'],
        ]);
    }
}
