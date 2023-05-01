<?php

namespace Database\Seeders;

use App\Models\Cinema;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TheatreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cinemas = Cinema::all();
        foreach($cinemas as $cinema) {
            DB::table('theatres')->insert([[
                'name' => 'Theatre One',
                'cinema_id' => $cinema->id,
                'created_at' => now(),
                'updated_at' => now()
            ], [
                'name' => 'Theatre Two',
                'cinema_id' => $cinema->id,
                'created_at' => now(),
                'updated_at' => now()
            ]]);
        }
    }
}
