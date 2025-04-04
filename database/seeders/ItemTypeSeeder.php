<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class ItemTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('item_type')->insert([
            ['itemtypename' => 'Daily'],
            ['itemtypename' => 'Own'],
            ['itemtypename' => 'Trading']
        ]);
    }
}
