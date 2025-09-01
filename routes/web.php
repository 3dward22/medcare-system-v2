<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;

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
        Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');

        // User management
        Route::get('admin/users', [AdminController::class, 'index'])->name('admin.users.index');
        
        Route::get('/admin/medicines', [MedicineController::class, 'adminIndex'])->name('admin.medicines.index');

        Route::delete('admin/users/{user}', [AdminController::class, 'destroy'])->name('admin.users.destroy');

        // Medicines CRUD
        Route::get('/medicines', [MedicineController::class, 'index'])->name('medicines.index');
        Route::get('/medicines/create', [MedicineController::class, 'create'])->name('medicines.create');
        Route::post('/medicines', [MedicineController::class, 'store'])->name('medicines.store');
        Route::get('/medicines/{medicine}', [MedicineController::class, 'show'])->name('medicines.show');
        Route::put('/medicines/{medicine}', [MedicineController::class, 'update'])->name('medicines.update');
        Route::delete('/medicines/{medicine}', [MedicineController::class, 'destroy'])->name('medicines.destroy');
    });

    // Nurse routes
    Route::prefix('nurse')->name('nurse.')->middleware('role:nurse')->group(function () {
    Route::get('/dashboard', fn() => view('nurse.dashboard'))->name('dashboard');

    // Medicines
    Route::get('/medicines', [MedicineController::class, 'index'])->name('medicines.index');
    
    // Student Records
    Route::get('/students', [AdminController::class, 'studentRecords'])->name('students.index');

    // Appointments
    Route::get('appointments', [AppointmentController::class, 'indexForNurse'])
        ->name('appointments.index');

    Route::post('appointments', [AppointmentController::class, 'store'])
        ->name('appointments.store');

    // Nurse ↔ Student chat
    Route::get('chat/students', [ChatController::class, 'listStudents'])->name('chat.students');
    Route::get('chat/student/{student}', [ChatController::class, 'indexWithStudent'])->name('chat.with_student');
    Route::post('chat/student/{student}', [ChatController::class, 'sendMessage'])->name('chat.send_message');
    
    // Fetch new messages with a specific student (AJAX)
    Route::get('chat/fetch/{student}', [ChatController::class, 'fetchMessages'])->name('nurse.chat.fetch');

// Notifications
    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
});

    // Student routes
    Route::prefix('student')->middleware('role:student')->group(function () {
    Route::get('/dashboard', fn() => view('students.dashboard'))->name('student.dashboard');

    // Appointments
    Route::resource('appointments', AppointmentController::class)
        ->only(['index', 'show', 'store'])
        ->names([
            'index' => 'student.appointments.index',
            'show' => 'student.appointments.show',
            'store' => 'student.appointments.store',
        ]);

    // Chat
    Route::get('chat', [ChatController::class, 'index'])->name('chat.index');
    Route::post('chat', [ChatController::class, 'store'])->name('chat.store');
    
    // Fetch new messages for student
    Route::get('chat/fetch/{student}', [ChatController::class, 'fetchMessages'])->name('student.chat.fetch');

    // Notifications
    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');

    // Medicines read-only
    Route::get('medicines', [MedicineController::class, 'studentIndex'])->name('student.medicine');
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
