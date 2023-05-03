<?php

namespace App\Http\Controllers;

use App\Models\Film;
use App\Services\FilmService;
use Inertia\Inertia;

class FilmController extends Controller
{
    /**
     * @var FilmService
     */
    private $filmService;

    /**
     * constructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->filmService = new FilmService;
    }

    /**
     * List all films that have upcoming schedules
     *
     * @return Response
     */
    public function index()
    {
        $user = auth()->user();
        $component = $user ? 'Dashboard' : 'Welcome';

        $relationsToEagerLoad = ['schedules' => function ($query) {
            $query->where('starts_at', '>', now()->toDateTimeString());
        }];

        $films = $this->filmService->getFilms(relationsToEagerLoad: $relationsToEagerLoad);

        return Inertia::render($component, [
            'films' => $films,
            'canRegister' => $user ? false : true,
        ]);
    }

    /**
     * Show a single film
     *
     * @param  Film  $film :: The film to show
     * @return Response
     */
    public function show(Film $film)
    {
        $relationsToEagerLoad = ['schedules'];

        $film = $this->filmService->getFilm(id: $film->id, relationsToEagerLoad: $relationsToEagerLoad);

        return Inertia::render('Dashboard', [
            'films' => [$film],
            'canRegister' => false,
        ]);
    }
}
