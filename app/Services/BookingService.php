<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Schedule;
use App\Traits\StringHelper;
use App\Exceptions\CustomException;

class BookingService
{
    use StringHelper;
    
    /**
     *
     * @param int $scheduleId
     * @param int $numOfTickets
     * 
     * @return Booking
     */
    public function makeBooking(int $scheduleId, int $numOfTickets)
    {
        $schedule = Schedule::find($scheduleId);

        if ($schedule->starts_at < now()->toDateTimeString()) {
            throw new CustomException('Cannot book a schedule that has already started.');
        }

        if ($schedule->seats_remaining < $numOfTickets) {
            throw new CustomException('Cannot book more tickets than the number of seats remaining!');
        }

        $booking = Booking::create([
            'user_id' => auth()->user()->id,
            'schedule_id' => $scheduleId,
            'reference_number' => $this->generateReferenceNumber(),
            'number_of_tickets' => $numOfTickets,
        ]);

        $booking->save();

        return $booking;
    }
    
    /**
     *
     * @return string
     */
    private function generateReferenceNumber()
    {
        $referenceNumber = '';

        while (Booking::where('reference_number', $referenceNumber)->exists()) {
            $referenceNumber = $this->generateRandomAlphaNumericString(8);
        }

        return $referenceNumber;
    }

      public function getBookings(array $filters = [])
      {
          $query = Booking::query();

          if (isset($filters['userId'])) {
              $query = $query->where('user_id', $filters['userId']);
          }

          if (isset($filters['status'])) {
              if ($filters['status'] == 'upcoming') {
                  $query = $query->whereHas('schedule', function ($query) {
                      $query->where('starts_at', '>=', now()->toDateTimeString());
                  });
              } elseif ($filters['status'] == 'past') {
                  $query = $query->whereHas('schedule', function ($query) {
                      $query->where('starts_at', '<', now()->toDateTimeString());
                  });
              } elseif ($filters['status'] == 'cancelled') {
                  $query = $query->onlyTrashed();
              }
          }

          $bookings = $query->with(['schedule' => function ($query) {
              $query->with(['film', 'theatre' => function ($query) {
                  $query->with('cinema');
              }]);
          }])
              ->orderBy('id', 'desc')
              ->get();

          return $bookings;
      }

      /**
       * @param  int|Booking  $booking
       * 
       * @return void
       */
      public function deleteBooking($booking)
      {
          $booking = $booking instanceof Booking ? $booking : Booking::find($booking);
          $booking->delete();
      }
}
