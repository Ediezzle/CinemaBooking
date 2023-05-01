<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Xylis\FakerCinema\Provider\Movie;
use Xylis\FakerCinema\Provider\Person;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Film>
 */
class FilmFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = \Faker\Factory::create();
        $faker->addProvider(new Movie($faker));
        $faker->addProvider(new Person($faker));

        return [
            'title' => $faker->movie,
            'summary' => $faker->overview,
            'year' => $faker->year,
            'director' => $faker->director,
            'starring' => implode(',', $faker->actors),
            'genre' => $faker->movieGenre,
            'duration' => $faker->runtime,
            'rating' => $faker->numberBetween(1, 5),
        ];
    }
}
