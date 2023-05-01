<?php

namespace App\Console\Commands;

use App\Models\Film;
use App\Models\Schedule;
use App\Models\Theatre;
use App\Services\FilmService;
use Exception;
use Illuminate\Console\Command;

class GenerateSchedules extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:schedules';

    /**
     * @var FilmService
     */
    private $filmService;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate schedules for tomorrow for all films in all theatres';

    /**
     * @return void
     */
    public function __construct(FilmService $filmService)
    {
        parent::__construct();
        $this->filmService = $filmService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $start = now();
        $this->info('Started generating schedules for tomorrow at '.$start->toDateTimeString());
        $schedules = $this->processSchedulesCreation();
        $this->info('Completed generating '.$schedules.' schedules for tomorrow in '.now()->diffInSeconds($start).' seconds');
    }

    public function processSchedulesCreation(): int
    {
        $schedulesGenerated = 0;
        $theatres = Theatre::all();

        foreach ($theatres as $theatre) {
            try {
                if ($theatre->upcoming_schedules->count() < intval(config('cinemabooking.max_num_of_current_movies_per_theatre'))) {
                    $scheduleShortfallForTheatre = intval(config('cinemabooking.max_num_of_current_movies_per_theatre') - $theatre->upcoming_schedules->count());

                    $upcomingScheduleFilmIds = $this->getUpComingScheduleFilmIds();

                    $films = Film::whereNotIn('id', $upcomingScheduleFilmIds)->get();

                    if ($films->count() < $scheduleShortfallForTheatre) {
                        $numOfFilmsToGenerate = $scheduleShortfallForTheatre - $films->count();
                        // generate films
                        $films = $this->filmService->generateRandomFilms($numOfFilmsToGenerate);
                    }

                    // in case code didn't jump into above if block we still wanna limit films to shortfall
                    $films = $films->take(intval(config('cinemabooking.max_num_of_current_movies_per_theatre')));

                    if ($films) {
                        // create schedules for theatre
                        foreach ($films as $film) {
                            $lastScheduleForTheatre = $theatre->upcoming_schedules->last();

                            if ($lastScheduleForTheatre) {
                                $filmLength = $lastScheduleForTheatre->film->duration;
                                // if now + length < 20:00 hrs we schedule another for today, otherwise we schedule for tomorrow at 10am
                                $durationArray = explode(':', $filmLength);
                                $hours = $durationArray[0] ?? 0;
                                $minutes = $durationArray[1] ?? 0;
                                $seconds = $durationArray[2] ?? 0;

                                if (now()->addHours($hours)->addMinutes($minutes)->addSeconds($seconds) < now()->setHour(20)) {
                                    $startsAt = now()->setHour(20);
                                } else {
                                    $startsAt = now()->addDay()->setHour(10);
                                }
                            } else {
                                // set start time to an hour from now if it gives less than 20:00 hrs otherwise set to tomorrow at 10am
                                if (now()->addHour() < now()->setHour(20)) {
                                    $startsAt = now()->addHour();
                                } else {
                                    $startsAt = now()->addDay()->setHour(10);
                                }
                            }

                            $this->createSchedule([
                                'film_id' => $film->id,
                                'theatre_id' => $theatre->id,
                                'starts_at' => $startsAt,
                            ]);

                            $schedulesGenerated++;
                        }
                    }
                }
            } catch (Exception $exception) {
                $this->error('Generating schedules for theatre '.$theatre->name.' failed with '.$exception->getMessage().' on line '.$exception->getLine().' in file '.$exception->getFile());
            }
        }

        return $schedulesGenerated;
    }

    public function getUpcomingScheduleFilmIds(): array
    {
        return Schedule::where('starts_at', '>', now()->toDateTimeString())->pluck('film_id')->toArray();
    }

    /**
     * createSchedule
     *
     *
     * @return void
     */
    public function createSchedule(array $scheduleDetails)
    {
        $filmsWithUpcomingSchedules = $this->getUpcomingScheduleFilmIds();

        //we don't want to schedule a film twice even for different theatres
        if (in_array($scheduleDetails['film_id'], $filmsWithUpcomingSchedules)) {
            $film = $this->filmService->generateRandomFilms(1);
            $scheduleDetails['film_id'] = $film->id;
        }

        $schedule = new Schedule($scheduleDetails);
        $schedule->save();
    }
}
