<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'schedule_id',
        'reference_number',
        'number_of_tickets'
    ];

    protected $appends = ['is_cancelable'];

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getIsCancelableAttribute(): bool
    {
        $startsAt = Carbon::parse($this->schedule->starts_at);
        return (now() < $startsAt) && now()->diffInHours($startsAt) > 1;
    }
}
