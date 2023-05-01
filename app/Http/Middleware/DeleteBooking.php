<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Booking;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DeleteBooking
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $booking = $request->route('booking');
        if($booking->user_id != auth()->user()->id) {
            return to_route('bookings.upcoming')->with(
                'notification', 
                [
                    'status' => 'failure',
                    'message' => 'You are not authorized to delete this booking!'
                ]
            );
        }

        if(! $booking->is_cancelable) {
            return to_route('bookings.upcoming')->with(
                'notification', 
                [
                    'status' => 'failure',
                    'message' => 'Bookings can only be cancelled up to an hour before film time!'
                ]
            );
        }
        return $next($request);
    }
}
