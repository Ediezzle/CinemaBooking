<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CinemaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('cinemas')->insert([[
            'name' => 'Cinema One',
            'created_at' => now(),
            'updated_at' => now(),
        ], [
            'name' => 'Cinema Two',
            'created_at' => now(),
            'updated_at' => now(),
        ]]);
    }
}
