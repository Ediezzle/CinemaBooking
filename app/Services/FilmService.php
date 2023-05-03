<?php

namespace App\Services;

use App\Models\Film;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;
use Xylis\FakerCinema\Provider\Movie;
use Xylis\FakerCinema\Provider\Person;

class FilmService
{
    /**
     * getFilm
     *
     *
     * @return Film
     */
    public function getFilm(int $id, array $filters = [], array $relationsToEagerLoad = [])
    {
        $query = Film::findOrFail($id);
        if (! empty($relationsToEagerLoad)) {
            $query = $query->with($relationsToEagerLoad);
        }

        return $query->first();
    }

    /**
     * @return Film
     */
    public function getFilmForBooking(int $id)
    {
        $film = Film::query()
            ->with(['schedules' => function ($query) {
                $query->where('starts_at', '>', now()->toDateTimeString())
                    // in case they are booking for someone else, we don't want to filter out schedules that the user has already booked
                    // ->where(function($query){
                    //     $query->whereDoesntHave('bookings')
                    //     // filter out schedules that the user has already booked
                    //         ->orWhereHas('bookings', function($query){
                    //             $query->whereNotIn('user_id', [auth()->user()->id]);
                    //         });
                    // })
                    ->with(['theatre.cinema']);
            }])
            ->findOrFail($id);
        // filter out schedules that have no seats remaining
        // commenting this out to cater for cases where one is booking for someone else. Left it here for reference
        // ->filter(
        //     fn($schedule) => $schedule->seats_remaining > 0
        // );

        return $film;
    }

    /**
     * getFilms
     *
     *
     * @return Collection
     */
    public function getFilms(array $filters = [], array $relationsToEagerLoad = [], bool $onlyWithUpcomingSchedules = true)
    {
        $query = Film::query();
        if ($onlyWithUpcomingSchedules) {
            $query = $query->whereHas('schedules', function ($query) {
                $query->where('starts_at', '>', now()->toDateTimeString());
            });
        }

        if (! empty($relationsToEagerLoad)) {
            $query = $query->with($relationsToEagerLoad);
        }

        if (! empty($filters)) {
            $query = $this->applyFilters($query, $filters);
        }

        return $query->get();
    }

    /**
     * @return Builder
     */
    protected function applyFilters(Builder $query, array $filters)
    {
        foreach ($filters as $filter) {
            $query = $query->where($filter['column'], $filter['operator'], $filter['value']);
        }

        return $query;
    }

    /**
     * generateRandomFilms
     *
     *
     * @return Collection
     */
    public function generateRandomFilms(int $numOfFilms)
    {
        $faker = \Faker\Factory::create();
        $faker->addProvider(new Movie($faker));
        $faker->addProvider(new Person($faker));

        $films = Film::factory($numOfFilms)->create(
            [
                'title' => $faker->movie,
                'summary' => $faker->overview,
                'year' => $faker->year,
                'director' => $faker->director,
                'starring' => implode(',', $faker->actors),
                'genre' => $faker->movieGenre,
                'duration' => $faker->runtime,
                'cover_photo' => '/'.Storage::disk('cover-photos')->getConfig()['relative_path'].'/cover_'.$faker->unique()->numberBetween(1, 8).'_photo.jpeg',
                'rating' => $faker->numberBetween(1, 5),
            ]
        );

        return $films;
    }
}
