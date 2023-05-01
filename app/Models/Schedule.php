<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'film_id',
        'theatre_id',
        'starts_at',
    ];

    protected $appends = ['seats_remaining'];

    public function theatre(): BelongsTo
    {
        return $this->belongsTo(Theatre::class);
    }

    public function getSeatsRemainingAttribute(): int
    {
        $numOfBookingsForSchedule = Booking::where('schedule_id', $this->id)
            ->count();
        return intval(config('cinemabooking.max_num_of_seats_per_theatre') - $numOfBookingsForSchedule);
    }

    public function film(): BelongsTo
    {
        return $this->belongsTo(Film::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
