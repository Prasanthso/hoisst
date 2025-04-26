<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class UserTable extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert(
            [
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('Rms@1234'),
            ],
    );
    }
}
