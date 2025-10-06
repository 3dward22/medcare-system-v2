<?php 

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\AppointmentController as AdminAppointmentController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\GuardianSmsController;

/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('login', fn() => view('auth.login'))->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
});

// ✅ OTP verification routes for admin/nurse (guest users)
Route::get('/otp-verify', [OtpController::class, 'show'])->name('otp.verify');
Route::post('/otp-verify', [OtpController::class, 'verify'])->name('otp.verify.post');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Logout
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    /*
    |--------------------------------------------------------------------------
    | Admin Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Admin appointment management
        Route::get('admin/appointments', [AdminAppointmentController::class, 'index'])
            ->name('admin.appointments.all');
        Route::get('admin/appointments/today', [AdminAppointmentController::class, 'today'])
            ->name('admin.appointments.today');
        Route::get('admin/appointments/week', [AdminAppointmentController::class, 'week'])
            ->name('admin.appointments.week');

        // User management
        Route::get('admin/users', [AdminController::class, 'index'])->name('admin.users.index');
        Route::delete('admin/users/{user}', [AdminController::class, 'destroy'])->name('admin.users.destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | Nurse Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('nurse')->name('nurse.')->middleware('role:nurse')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/students', [AdminController::class, 'studentRecords'])->name('students.index');

        // Nurse appointments
        Route::get('appointments', [AppointmentController::class, 'indexForNurse'])->name('appointments.index');
        Route::post('appointments', [AppointmentController::class, 'store'])->name('appointments.store');
        Route::put('appointments/{appointment}', [AppointmentController::class, 'update'])->name('appointments.update');
        Route::get('appointments/today-json', [AppointmentController::class, 'todayAppointmentsJson'])
            ->name('appointments.today-json');

        // Notifications page
        Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
    });

    /*
    |--------------------------------------------------------------------------
    | Student Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('student')->middleware('role:student')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('student.dashboard');

        Route::resource('appointments', AppointmentController::class)
            ->only(['index', 'show', 'store'])
            ->names([
                'index' => 'student.appointments.index',
                'show' => 'student.appointments.show',
                'store' => 'student.appointments.store',
            ]);
        Route::delete('appointments/{appointment}', [AppointmentController::class, 'destroy'])
            ->name('student.appointments.destroy');

        // Notifications page
        Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
    });

    /*
    |--------------------------------------------------------------------------
    | Guardian SMS Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/guardian-sms', [GuardianSmsController::class, 'index'])
        ->name('guardian.sms.index');
    Route::post('/guardian-sms/send', [GuardianSmsController::class, 'send'])
        ->name('guardian.sms.send');

    /*
    |--------------------------------------------------------------------------
    | Notifications Polling API (for all authenticated users)
    |--------------------------------------------------------------------------
    */
    Route::get('/notifications/check', [NotificationController::class, 'check'])
        ->name('notifications.check');

    /*
    |--------------------------------------------------------------------------
    | Guardian Notify (Link with Appointments)
    |--------------------------------------------------------------------------
    */
    Route::post('/appointments/{id}/notify-guardian', [AppointmentController::class, 'notifyGuardian'])
        ->name('appointments.notifyGuardian');

    /*
    |--------------------------------------------------------------------------
    | Report Generation
    |--------------------------------------------------------------------------
    */
    Route::get('reports/monthly', [ReportController::class, 'generateMonthlyReport'])
        ->name('reports.monthly');
});

/*
|--------------------------------------------------------------------------
| Default Route
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    if (Auth::check()) {
        $role = strtolower(Auth::user()->role);
        return match ($role) {
            'admin' => redirect()->route('dashboard'),
            'nurse' => redirect()->route('nurse.dashboard'),
            'student' => redirect()->route('student.dashboard'),
            default => redirect()->route('login'),
        };
    }
    return redirect()->route('login');
});
