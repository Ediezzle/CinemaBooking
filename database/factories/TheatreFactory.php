<?php

namespace Database\Factories;

use App\Models\Cinema;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Theatre>
 */
class TheatreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $cinema = Cinema::firstOrCreate([
            'name' => 'Cinema One',
        ]);

        return [
            'name' => 'Theatre '.$this->faker->unixTime,
            'cinema_id' => $cinema->id,

        ];
    }
}
