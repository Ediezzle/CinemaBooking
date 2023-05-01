<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Film extends Model
{
    use HasFactory;

    // public function getIsBookableAttribute(): bool
    // {
    //     $authUser = auth()->user();

    //     $isBookable = $this->schedules->count() > 0
    //         && $this->schedules->sum('seats_remaining') > 0;

    //     if ($authUser) {
    //         $userHasBooked = $authUser?->bookings()
    //             ->where('film_id', $this->id)
    //             ->count() > 0;
    //         $isBookable = $isBookable && ! $userHasBooked;
    //     }
    // }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }
}
