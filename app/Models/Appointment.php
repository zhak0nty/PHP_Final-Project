<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    public const STATUS_SCHEDULED = 'scheduled';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_COMPLETED = 'completed';

    /** Minimum full days before visit start when a client may delete a scheduled appointment. */
    public const CLIENT_DELETE_MIN_DAYS_BEFORE = 2;

    protected $fillable = [
        'doctor_id',
        'client_id',
        'service_id',
        'time_slot_id',
        'status',
        'guest_name',
        'guest_email',
        'guest_phone',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
        ];
    }

    public function isGuest(): bool
    {
        return $this->client_id === null;
    }

    public function isExpired(): bool
    {
        return $this->expires_at !== null && $this->expires_at->isPast();
    }

    public function canBeDeletedByClient(): bool
    {
        if ($this->status !== self::STATUS_SCHEDULED) {
            return false;
        }

        $startsAt = $this->timeSlot?->starts_at;

        if ($startsAt === null) {
            return false;
        }

        return now()->addDays(self::CLIENT_DELETE_MIN_DAYS_BEFORE)->lessThanOrEqualTo($startsAt);
    }

    /** Scope: only appointments that still "hold" the slot (scheduled and not expired). */
    public function scopeActiveSlot($query)
    {
        return $query->where('status', self::STATUS_SCHEDULED)
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            });
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function timeSlot()
    {
        return $this->belongsTo(TimeSlot::class);
    }
}

