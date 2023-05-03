<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomException;
use App\Http\Middleware\DeleteBooking;
use App\Models\Booking;
use App\Services\BookingService;
use App\Services\FilmService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BookingController extends Controller
{
    /**
     * @var BookingService
     */
    private $bookingService;

    /**
     * constructor
     *
     *
     * @return void
     */
    public function __construct(BookingService $bookingService)
    {
        $this->middleware(DeleteBooking::class)->only('destroy');
        $this->bookingService = $bookingService;
    }

    /**
     * Display a list of upcoming bookings
     *
     * @return Response
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
     * Show the form for making a new booking
     *
     * @param Request
     * @param  int  $filmId :: The id of the film to book
     * @return Response
     */
    public function create(Request $request, int $filmId)
    {
        $data = ['film' => (new FilmService)->getFilmForBooking(id: $filmId)];

        if ($request->has('status')) {
            $data['status'] = $request->status;
        }

        if ($request->has('message')) {
            $data['message'] = $request->message;
        }

        return Inertia::render('Booking/CreateBooking', $data);
    }

    /**
     * Save a new booking
     *
     *
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'scheduleId' => 'required|exists:schedules,id|integer|gte:1',
                'numOfTickets' => 'required|integer|min:1',
            ]);
            $booking = $this->bookingService->makeBooking(
                scheduleId: $request->scheduleId,
                numOfTickets: $request->numOfTickets,
            );

        } catch (CustomException $e) {
            return redirect()->back()->with('notification', [
                'status' => 'failure',
                'message' => $e->getMessage(),
            ]);
        }

        return to_route('bookings.upcoming')->with('notification', [
            'status' => 'success',
            'message' => 'Booking successful! Your Reference number is '.$booking->reference_number,
        ]);
    }

    /**
     * List past bookings
     *
     * @return Response
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
     * List cancelled bookings
     *
     * @return Response
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
     * Cancel a booking
     *
     * @param  Booking  $booking :: The booking to be cancelled
     */
    public function destroy(Booking $booking)
    {
        $this->bookingService->deleteBooking($booking);

        $notification = [
            'status' => 'success',
            'message' => 'Booking cancelled successfully!',
        ];

        return redirect()->back()->with('notification', $notification);
    }
}
