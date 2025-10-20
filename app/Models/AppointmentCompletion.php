<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentCompletion extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'completed_datetime',
        'temperature',
        'blood_pressure',
        'heart_rate',
        'findings',
        'additional_notes',
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
