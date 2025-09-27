<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DashboardController;

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
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    //all appoiments
    Route::get('admin/appointments', [AppointmentController::class, 'allAppointments'])
    ->name('admin.appointments.all');

    // User management
    Route::get('admin/users', [AdminController::class, 'index'])->name('admin.users.index');
    Route::delete('admin/users/{user}', [AdminController::class, 'destroy'])->name('admin.users.destroy');

    // ✅ Admin appointment filters
    Route::get('admin/appointments/today', [AppointmentController::class, 'today'])->name('admin.appointments.today');
    Route::get('admin/appointments/week', [AppointmentController::class, 'week'])->name('admin.appointments.week');

    // ✅ NEW ROUTE: View all student appointments for this week
    Route::get('admin/appointments/week/students', [AppointmentController::class, 'studentAppointmentsThisWeek'])
        ->name('admin.appointments.week.students');
    });


    /*
    |--------------------------------------------------------------------------
    | Nurse Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('nurse')->name('nurse.')->middleware('role:nurse')->group(function () {
        // Nurse dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Student Records
        Route::get('/students', [AdminController::class, 'studentRecords'])->name('students.index');

        // Appointments
        Route::get('appointments', [AppointmentController::class, 'indexForNurse'])->name('appointments.index');
        Route::post('appointments', [AppointmentController::class, 'store'])->name('appointments.store');

        // Notifications
        Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
    });

    /*
    |--------------------------------------------------------------------------
    | Student Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('student')->middleware('role:student')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('student.dashboard');

        // Appointments
        Route::resource('appointments', AppointmentController::class)
            ->only(['index', 'show', 'store'])
            ->names([
                'index' => 'student.appointments.index',
                'show' => 'student.appointments.show',
                'store' => 'student.appointments.store',
            ]);

        // Notifications
        Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
    });

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
