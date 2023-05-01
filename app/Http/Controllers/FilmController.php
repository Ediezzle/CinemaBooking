<?php

namespace App\Http\Controllers;

use App\Models\Film;
use App\Services\FilmService;
use Inertia\Inertia;
use Illuminate\Http\Request;

class FilmController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $component = $user ? 'Dashboard' : 'Welcome' ;

        $relationsToEagerLoad = ['schedules' => function ($query)  {
            $query->where('starts_at', '>', now()->toDateTimeString());
        }];

        $films = (new FilmService)->getFilms(relationsToEagerLoad: $relationsToEagerLoad);

        return Inertia::render($component, [
            'films' => $films,
            'canRegister' => $user ? false : true,
        ]);
    }
}
