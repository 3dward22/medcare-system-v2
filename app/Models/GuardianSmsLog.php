<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuardianSmsLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'guardian_name',
        'guardian_phone',
        'message',
        'sent_by',
        'sent_by_id',
        'sent_by_role',
    ];

    public function appointment() {
        return $this->belongsTo(Appointment::class);
    }

    public function student() {
        return $this->belongsTo(User::class, 'student_id');
    }
}
