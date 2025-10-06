<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuardianSmsLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'student_id',
        'guardian_name',
        'guardian_phone',
        'message',
        'sent_at',
    ];

    public function appointment() {
        return $this->belongsTo(Appointment::class);
    }

    public function student() {
        return $this->belongsTo(User::class, 'student_id');
    }
}
