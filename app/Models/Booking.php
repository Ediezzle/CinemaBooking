<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'schedule_id',
        'reference_number',
        'number_of_tickets',
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
