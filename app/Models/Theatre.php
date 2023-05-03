<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Theatre extends Model
{
    use HasFactory;

    protected $appends = ['upcoming_schedules'];

    public function cinema(): BelongsTo
    {
        return $this->belongsTo(Cinema::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    public function getUpcomingSchedulesAttribute(): Collection
    {
        return $this->schedules()
            ->where('starts_at', '>', now())
            ->orderBy('starts_at')
            ->get();
    }
}
