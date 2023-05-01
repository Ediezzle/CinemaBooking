<?php

namespace App\Services;

use App\Models\Film;
use App\Models\Booking;
use App\Models\Schedule;
use Illuminate\Support\Carbon;

class ScheduleService
{
    public function createSchedule(int $filmId, Carbon $startsAt, int $theatreId): array
    {
        $result = [
            'status' => 'failure',
            'schedule' => null,
            'reason' => 'Theatre with id '.$theatreId.' already has 2 films for the current day!',
        ];

        $scheduleExists = Schedule::where([
            'film_id' => $filmId,
            'theatre_id' => $theatreId,
        ])
        ->where('starts_at', '>=', $startsAt->toDateTimeString())
        ->exists();

        if ($scheduleExists) {
            $result['reason'] = 'Schedule already exists!';
            return $result;
        }

        //check if theatre doesn't already have 2 films for the current day that haven't yet started
        $numOfFilmsForTheatre = Schedule::where([
            'theatre_id' => $theatreId,
        ])->where('starts_at', '>=', $startsAt->toDateTimeString())->count();

        if ($numOfFilmsForTheatre >= (int)config('cinemabooking.max_num_of_current_movies_per_theatre') ) {
            return $result;
        }
        
        // $checkOne = Schedule::where([
        //     'theatre_id' => $theatreId, 
        //     'starts_at' => $startsAt->toDateTimeString()
        // ])->count();

        // if ($checkOne >= 1) {
        //     $result['reason'] = 'Theatre with id '.$theatreId.' already has a film at the same time!';
        //     return $result;
        // }

        // $checkTwo = Schedule::where('theatre_id', $theatreId)
        //     ->where('film_id', $filmId)
        //     ->whereDate('starts_at', $startsAt->toDateString())
        //     ->count();

        // if ($checkTwo >= 1) {
        //     $result['reason'] = 'Theatre with id '.$theatreId.' is already showing the same film on the same day!';
        //     return $result;
        // }

        $schedule = Schedule::create([
            'film_id' => $filmId,
            'starts_at' => $startsAt,
            'theatre_id' => $theatreId,
        ]);

        return ['status' => 'success', 'schedule' => $schedule, 'reason' => ''];
    }

    public function getUpcomingSchedulesForFilm(int $filmId, array $relationsToEagerLoad = [], array $filters = [])
    {
        $forBooking = false;

        if (isset($filters['forBooking'])) {
            $forBooking = $filters['forBooking'];
        }
        unset($filters['forBooking']);

        $query = Schedule::query()->where('film_id', $filmId)
            ->where('starts_at', '>=', now()->toDateString());

        if (! empty($filters)) {
            foreach ($filters as $filter) {
                $query = $query->where($filter['column'], $filter['operator'], $filter['value']);
            }
        }

        if (! empty($relationsToEagerLoad)) {
            $query = $query->with($relationsToEagerLoad);
        }

        $schedules = $query->get();

        if($forBooking) {
            $schedules = $schedules->filter(function($schedule){
                return $schedule->seats_remaining > 0;
            });
        }
       
        return $schedules;
    }
}
