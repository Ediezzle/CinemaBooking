<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Theatre extends Model
{
    use HasFactory;

    protected $appends = ['upcoming_schedules'];

    public function cinema(): BelongsTo
    {
        return $this->belongsTo(Cinema::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function getUpcomingSchedulesAttribute()
    {
        return $this->schedules()
            ->where('starts_at', '>', now())
            ->orderBy('starts_at')
            ->get();
    }
}
