<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property int $student_id
 * @property \Illuminate\Support\Carbon $requested_datetime
 * @property \Illuminate\Support\Carbon|null $approved_datetime
 * @property string|null $completed_datetime
 * @property string|null $temperature
 * @property string|null $blood_pressure
 * @property string|null $heart_rate
 * @property string|null $additional_notes
 * @property string $status
 * @property int|null $approved_by
 * @property string|null $admin_note
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $student_sms_sent
 * @property int $guardian_sms_sent
 * @property string|null $findings
 * @property-read \App\Models\User|null $approvedBy
 * @property-read \App\Models\User $student
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment whereAdditionalNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment whereAdminNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment whereApprovedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment whereApprovedDatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment whereBloodPressure($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment whereCompletedDatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment whereFindings($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment whereGuardianSmsSent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment whereHeartRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment whereRequestedDatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment whereStudentSmsSent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment whereTemperature($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment whereUserId($value)
 */
	class Appointment extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $appointment_id
 * @property string $completed_datetime
 * @property string|null $temperature
 * @property string|null $blood_pressure
 * @property string|null $heart_rate
 * @property string|null $findings
 * @property string|null $additional_notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Appointment $appointment
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppointmentCompletion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppointmentCompletion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppointmentCompletion query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppointmentCompletion whereAdditionalNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppointmentCompletion whereAppointmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppointmentCompletion whereBloodPressure($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppointmentCompletion whereCompletedDatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppointmentCompletion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppointmentCompletion whereFindings($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppointmentCompletion whereHeartRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppointmentCompletion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppointmentCompletion whereTemperature($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppointmentCompletion whereUpdatedAt($value)
 */
	class AppointmentCompletion extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $appointment_id
 * @property int|null $student_id
 * @property string|null $guardian_name
 * @property string|null $guardian_phone
 * @property string $message
 * @property int|null $sent_by_id
 * @property string|null $sent_by_role
 * @property string|null $sent_by
 * @property string|null $sent_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Appointment|null $appointment
 * @property-read \App\Models\User|null $student
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GuardianSmsLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GuardianSmsLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GuardianSmsLog query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GuardianSmsLog whereAppointmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GuardianSmsLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GuardianSmsLog whereGuardianName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GuardianSmsLog whereGuardianPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GuardianSmsLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GuardianSmsLog whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GuardianSmsLog whereSentAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GuardianSmsLog whereSentBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GuardianSmsLog whereSentById($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GuardianSmsLog whereSentByRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GuardianSmsLog whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GuardianSmsLog whereUpdatedAt($value)
 */
	class GuardianSmsLog extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $date_of_birth
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient whereDateOfBirth($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient whereUpdatedAt($value)
 */
	class Patient extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string $role
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $otp
 * @property string|null $otp_expires_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereOtp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereOtpExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

