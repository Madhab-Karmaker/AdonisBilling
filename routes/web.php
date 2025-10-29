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
Route::middleware(['auth', 'role:manager'])->group(function() {
    Route::resource('users', UserController::class);
    Route::resource('services', ServiceController::class);
});

// Receptionist routes
Route::middleware(['auth', 'role:receptionist'])->group(function() {
    Route::get('/bills', [BillController::class, 'index'])->name('bills.index');
    Route::get('/bills/create', [BillController::class, 'create'])->name('bills.create');
    Route::post('/bills', [BillController::class, 'store'])->name('bills.store');
});

// Redirect root to login
Route::get('/', function () {
    return redirect('/login');
});
