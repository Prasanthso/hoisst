<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReceipesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {

        DB::table('recipes')->insert([
            ['recipesname' => 'Samosa'],
            ['recipesname' => 'Puffs'],
            ['recipesname' => 'Cakes'],
            ['recipesname' => 'Biscuits'],
        ]);
    }
}
