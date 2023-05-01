<?php

namespace App\Http\Responses;

use Illuminate\Support\Facades\Session;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    /**
     * @return mixed
     */
    public function toResponse($request)
    {
        $targetUrl = Session::get('url.intended', url('/'));
        $parts = parse_url($targetUrl);
        $filmId = null;
        if (isset($parts['query'])) {
            parse_str($parts['query'], $query);
            $filmId = $query['filmId'] ?? null;
        }

        $route = $filmId ? '/bookings/create/'.$filmId : '/dashboard';

        return redirect()->intended($route);
    }
}
