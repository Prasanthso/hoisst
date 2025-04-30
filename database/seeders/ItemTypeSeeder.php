<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('item_type')->insert([
            ['itemtypename' => 'Own'],
            ['itemtypename' => 'Trading'],
            ['itemtypename' => 'Daily'],

        ]);
    }
}
