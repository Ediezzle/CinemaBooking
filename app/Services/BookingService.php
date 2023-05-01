<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Schedule;
use App\Exceptions\CustomException;

class BookingService
{  
    public function makeBooking(int $scheduleId, int $numOfTickets)
    {
        $schedule = Schedule::find($scheduleId);

        if($schedule->starts_at < now()->toDateTimeString())
        {
            throw new CustomException('Cannot book a schedule that has already started.');
        }

        $booking = Booking::create([
            'user_id' => auth()->user()->id,
            'schedule_id' => $scheduleId,
            'reference_number' => $this->generateReferenceNumber(),
            'number_of_tickets' => $numOfTickets
        ]);

        $booking->save();
        return $booking;
    }

    private function generateReferenceNumber() 
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $referenceNumber = '';
        
        for ($i = 0; $i < 8; $i++) {
          $referenceNumber .= $characters[rand(0, strlen($characters) - 1)];
        }

        while(Booking::where('reference_number', $referenceNumber)->exists())
        {
            $referenceNumber = $this->generateReferenceNumber();
        }
        
        return $referenceNumber;
      }

      public function getBookings(array $filters = [])
      {
        $bookings = Booking::query()
            ->when(isset($filters['userId']), function ($query, $userId) {
                $query->where('user_id', $userId);
            })
            ->when(isset($filters['status']), function ($query, $status) {
                if ($status === 'upcoming') {
                    $query->whereHas('schedule', function ($query) {
                        $query->where('starts_at', '>', now()->toDateTimeString());
                    });
                } else if($status === 'past') {
                    $query->whereHas('schedule', function ($query) {
                        $query->where('starts_at', '<', now()->toDateTimeString());
                    });
                }
            })
            ->with(['schedule' => function ($query) {
                $query->with(['film', 'theatre' => function ($query) {
                    $query->with('cinema');
                }]);
            }])
            ->when(isset($filters['status']) && $filters['status'] === 'cancelled', function ($query) {
                $query->withTrashed();
            })
            ->orderBy('id', 'desc')
            ->get();

        return $bookings;
      }
}