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
        $storeid = $request->session()->get('store_id');
        DB::table('categories')->insert([
            ['categoryname' => 'Raw Materials','store_id' => $storeid],
            ['categoryname' => 'Packing Materials','store_id' => $storeid],
            ['categoryname' => 'Overheads','store_id' => $storeid],
            ['categoryname' => 'Products','store_id' => $storeid],
        ]);
    }
}
