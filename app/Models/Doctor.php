<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'specialization',
        'bio',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'doctor_service');
    }

    public function timeSlots()
    {
        return $this->hasMany(TimeSlot::class);
    }

    /** Слоты, которые ещё не начались (для записи). */
    public function futureTimeSlots()
    {
        return $this->hasMany(TimeSlot::class)
            ->future()
            ->orderBy('starts_at');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
