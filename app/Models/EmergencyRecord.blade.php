<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmergencyRecord extends Model
{
    protected $fillable = [
        'student_id',
        'reported_by',
        'reported_at',
        'temperature',
        'blood_pressure',
        'heart_rate',
        'symptoms',
        'diagnosis',
        'treatment',
        'additional_notes',
        'guardian_notified',
        'guardian_notified_at',
    ];

    protected $casts = [
        'reported_at' => 'datetime',
        'guardian_notified' => 'boolean',
        'guardian_notified_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function nurse(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_by');
    }
}
