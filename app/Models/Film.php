<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Film extends Model
{
    use HasFactory;

    protected $appends = ['is_bookable'];
    
    public function getIsBookableAttribute(): bool
    {   
        $schedules = $this->schedules()
            ->with('theatre')
            ->withCount('bookings')
            ->where('starts_at', '>', now())
            ->get()
            ->filter(function($schedule){
                return ($schedule->seats_remaining > 0) 
                    && ($schedule->bookings_count < config('cinemabooking.max_num_of_seats_per_theatre'));
            });

        return $schedules->count() > 0;
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }
}
