<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Booking;
use App\Models\Schedule;
use Illuminate\Http\Request;
use App\Services\FilmService;
use App\Services\BookingService;

class BookingController extends Controller
{
    public function __construct()
    {
        // $this->middleware(['auth', 'verified']);
    }
    /**
     * Display a listing of the resource.
     */
    public function upcoming(BookingService $bookingService)
    {
        $bookings = $bookingService->getBookings(filters: [
            'userId' => auth()->user()->id,
            'status' => 'upcoming',
        ]);

        return Inertia::render('Booking/UpcomingBookings', [
            'bookings' => $bookings,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, int $filmId)
    {
        $data = ['film' => (new FilmService)->getFilmForBooking(id: $filmId)];

        if($request->has('status')) {
            $data['status'] = $request->status;
        }

        if($request->has('message')) {
            $data['message'] = $request->message;
        }

        return Inertia::render('Booking/CreateBooking', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, BookingService $bookingService)
    {
        try {
            $booking = $bookingService->makeBooking(
                scheduleId: $request->scheduleId,
                numOfTickets: $request->numOfTickets,
            );

        } catch (Exception $e) {
            return redirect()->back()->with('notification', [
                'status' => 'failure',
                'message'=> $e->getMessage(),
            ]);
        }

        return to_route('bookings.upcoming')->with('notification', [
            'status' => 'success',
            'message'=> 'Booking successful! Your Reference number is ' . $booking->reference_number,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
