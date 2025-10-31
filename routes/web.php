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
    Route::resource('services', ServiceController::class)->names([
        'index' => 'manager.services.index',
        'create' => 'manager.services.create',
        'store' => 'manager.services.store',
        'show' => 'manager.services.show',
        'edit' => 'manager.services.edit',
        'update' => 'manager.services.update',
        'destroy' => 'manager.services.destroy',
    ]);

    Route::resource('users', UserController::class)->names([
        'index' => 'manager.users.index',
        'create' => 'manager.users.create',
        'store' => 'manager.users.store',
        'show' => 'manager.users.show',
        'edit' => 'manager.users.edit',
        'update' => 'manager.users.update',
        'destroy' => 'manager.users.destroy',
    ]);

    Route::get('bills', [BillController::class, 'index'])->name('manager.bills.index');
});

Route::middleware(['auth', 'role:receptionist'])->prefix('receptionist')->group(function() {
    Route::resource('services', ServiceController::class)->names([
        'index' => 'receptionist.services.index',
        'create' => 'receptionist.services.create',
        'store' => 'receptionist.services.store',
        'show' => 'receptionist.services.show',
        'edit' => 'receptionist.services.edit',
        'update' => 'receptionist.services.update',
        'destroy' => 'receptionist.services.destroy',
    ]);

    Route::get('bills', [BillController::class, 'index'])->name('receptionist.bills.index');
    Route::get('bills/create', [BillController::class, 'create'])->name('receptionist.bills.create');
    Route::post('bills', [BillController::class, 'store'])->name('receptionist.bills.store');
});
