<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\AuthController;

// Authentication routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard routes
Route::middleware(['auth'])->group(function() {
    Route::get('/dashboard/manager', [AuthController::class, 'managerDashboard'])->name('dashboard.manager');
    Route::get('/dashboard/receptionist', [AuthController::class, 'receptionistDashboard'])->name('dashboard.receptionist');
});

// Manager-only routes
Route::middleware(['auth', 'role:manager'])->prefix('manager')->group(function() {
    Route::resource('users', UserController::class);
    Route::resource('services', ServiceController::class);
    Route::get('bills', [BillController::class, 'index'])->name('manager.bills.index');
});

// Receptionist routes
Route::middleware(['auth', 'role:receptionist'])->prefix('receptionist')->group(function() {
    Route::get('bills', [BillController::class, 'index'])->name('receptionist.bills.index');
    Route::get('bills/create', [BillController::class, 'create'])->name('receptionist.bills.create');
    Route::post('bills', [BillController::class, 'store'])->name('receptionist.bills.store');
    Route::resource('services', ServiceController::class);
});


// Redirect root to login
Route::get('/', function () {
    return redirect('/login');
});
