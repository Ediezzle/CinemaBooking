<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Booking;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CreateBooking
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // check if user has a similar existing booking
        $userHasBooking = Booking::where('filmId', $request->get('filmId'))
            ->where('userId', $request->user()->id)
            ->where('schedule_id', $request->get('scheduleId'))
            ->exists();

        if($userHasBooking) {
            return redirect()->back()->with('error', 'You already have a booking for this film and shedule');
        }

        return $next($request);
    }
}
