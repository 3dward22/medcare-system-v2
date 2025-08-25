<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\NotificationController;

/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    // Login
    Route::get('login', fn () => view('auth.login'))->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
    
    // Registration
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
        Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');

        Route::get('admin/users', [AdminController::class, 'index'])->name('admin.users.index');
        Route::delete('admin/users/{user}', [AdminController::class, 'destroy'])->name('admin.users.destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | Nurse Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:nurse')->group(function () {
    // Nurse Dashboard
    Route::get('/nurse/dashboard', fn () => view('nurse.dashboard'))->name('nurse.dashboard');

    // Medicines CRUD
    Route::get('/medicines', [MedicineController::class, 'index'])->name('medicines.index');
    Route::get('/medicines/create', [MedicineController::class, 'create'])->name('medicines.create');
    Route::post('/medicines', [MedicineController::class, 'store'])->name('medicines.store');
    Route::get('/medicines/{medicine}', [MedicineController::class, 'show'])->name('medicines.show');
    Route::put('/medicines/{medicine}', [MedicineController::class, 'update'])->name('medicines.update');
    Route::delete('/medicines/{medicine}', [MedicineController::class, 'destroy'])->name('medicines.destroy');
            //add more routes for notififs ,chat , student records and appointments
    // Optional: Appointments management if nurses handle them
    Route::resource('appointments', AppointmentController::class)
        ->only(['index', 'show', 'update']); // adjust as needed
});
    
    /*
    |--------------------------------------------------------------------------
    | Student Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:student')->group(function () {
        // Student Dashboard
        Route::get('/student/dashboard', fn () => view('students.dashboard'))->name('student.dashboard');

        // Appointments
        Route::resource('appointments', AppointmentController::class)->only(['index', 'show', 'store']);

        // Chat
        Route::get('chat', [ChatController::class, 'index'])->name('chat.index');
        Route::post('chat', [ChatController::class, 'store'])->name('chat.store');

        // Notifications
        Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');

        // Medicines (read-only for students)
        Route::get('student/medicines', [MedicineController::class, 'studentIndex'])->name('student.medicine');


    });
});

/*
|--------------------------------------------------------------------------
| Default Route
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => redirect()->route('login'));
