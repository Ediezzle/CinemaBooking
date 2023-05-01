<?php

namespace App\Http\Controllers;

use App\Models\Film;
use App\Services\FilmService;
use Inertia\Inertia;
use Illuminate\Http\Request;

class FilmController extends Controller
{    
    /**
     *
     * @var FilmService
     */
    private $filmService;
    
    public function __construct()
    {
        $this->filmService = new FilmService;
    }

    public function index()
    {
        $user = auth()->user();
        $component = $user ? 'Dashboard' : 'Welcome' ;

        $relationsToEagerLoad = ['schedules' => function ($query)  {
            $query->where('starts_at', '>', now()->toDateTimeString());
        }];

        $films = $this->filmService->getFilms(relationsToEagerLoad: $relationsToEagerLoad);

        return Inertia::render($component, [
            'films' => $films,
            'canRegister' => $user ? false : true,
        ]);
    }

    public function show(Film $film)
    {
        $relationsToEagerLoad = ['schedules'];

        $film = $this->filmService->getFilm(id: $film->id, relationsToEagerLoad: $relationsToEagerLoad);

        return Inertia::render('Dashboard', [
            'films' => [$film],
            'canRegister' => false
        ]);
    }
}
