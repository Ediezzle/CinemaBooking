<?php

namespace App\Http\Controllers;

use App\Http\Middleware\DeleteBooking;
use Exception;
use Inertia\Inertia;
use App\Models\Booking;
use Illuminate\Http\Request;
use App\Services\FilmService;
use App\Services\BookingService;

class BookingController extends Controller
{    
    /**
     *
     * @var BookingService
     */
    private $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->middleware(DeleteBooking::class)->only('destroy');
        $this->bookingService = $bookingService;
    }

    /**
     * Display a listing of the resource.
     */
    public function upcoming()
    {
        $bookings = $this->bookingService->getBookings(filters: [
            'userId' => auth()->user()->id,
            'status' => 'upcoming',
        ]);

        return Inertia::render('Booking/Bookings', [
            'bookings' => $bookings,
            'status' => 'upcoming',
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
    public function store(Request $request)
    {
        try {
            $booking = $this->bookingService->makeBooking(
                scheduleId: $request->scheduleId,
                numOfTickets: $request->numOfTickets,
            );

        } catch (Exception $e) {
            report($e);
            return redirect()->back()->with('notification', [
                'status' => 'failure',
                'message'=> 'Something went wrong! Please try again or contact support.',
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
    public function past()
    {
        $bookings = $this->bookingService->getBookings(filters: [
            'userId' => auth()->user()->id,
            'status' => 'past',
        ]);

        return Inertia::render('Booking/Bookings', [
            'bookings' => $bookings,
            'status' => 'past',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function cancelled()
    {
        $bookings = $this->bookingService->getBookings(filters: [
            'userId' => auth()->user()->id,
            'status' => 'cancelled',
        ]);

        return Inertia::render('Booking/Bookings', [
            'bookings' => $bookings,
            'status' => 'cancelled',
        ]);
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
    public function destroy(Booking $booking)
    {
        $notification = [
            'status' => 'failure',
            'message'=> 'Something went wrong! Please try again or contact support.'
        ];

        try {
            $this->bookingService->deleteBooking($booking);
            $notification['status'] = 'success';
            $notification['message'] = 'Booking deleted successfully!';
        } catch (Exception $e) {
            report($e);
        }

        return redirect()->back()->with('notification', $notification);
    }
}
