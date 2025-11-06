<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ManagerDashboardController;
use App\Http\Controllers\ReceptionistDashboardController; 
use App\Http\Controllers\StaffController;

// Authentication routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard routes (protected by auth middleware)
Route::middleware(['auth'])->group(function() {
    // Manager Dashboard
    Route::get('/dashboard/manager', [ManagerDashboardController::class, 'index'])
        ->name('dashboard.manager');

    // Receptionist Dashboard
    Route::get('/dashboard/receptionist', [ReceptionistDashboardController::class, 'index'])
        ->name('dashboard.receptionist');
});

// Manager-only routes
Route::middleware(['auth', 'role:manager'])->prefix('manager')->group(function() {
    Route::resource('users', UserController::class);
    Route::resource('services', ServiceController::class)->names([
        'index' =>  'manager.services.index',
        'show' =>   'manager.services.show',
        'create' => 'manager.services.create',
        'store' =>  'manager.services.store',
        'edit' =>   'manager.services.edit',
        'update' => 'manager.services.update',
        'destroy' =>'manager.services.destroy',
    ]);
    Route::resource('bills', BillController::class)->names([
        'index' =>  'manager.bills.index',
        'show' =>   'manager.bills.show',
        'create' => 'manager.bills.create',
        'store' =>  'manager.bills.store',
        'edit' =>   'manager.bills.edit',
        'update' => 'manager.bills.update',
        //'destroy' =>'receptionist.bills.destroy',
    ]);
    Route::get('bills/{bill}/receipt', [BillController::class, 'receipt'])->name('manager.bills.receipt');
    Route::resource('staffs', StaffController::class)->names([
        'index' =>  'manager.staffs.index',
        'show' =>   'manager.staffs.show',
        'create' => 'manager.staffs.create',
        'store' =>  'manager.staffs.store',
        'edit' =>   'manager.staffs.edit',
        'update' => 'manager.staffs.update',
        'destroy' =>'manager.staffs.destroy',
    ]);

});

// Receptionist routes
Route::middleware(['auth', 'role:receptionist'])->prefix('receptionist')->group(function() {
    Route::resource('bills', BillController::class)->names([
        'index' =>  'receptionist.bills.index',
        'show' =>   'receptionist.bills.show',
        'create' => 'receptionist.bills.create',
        'store' =>  'receptionist.bills.store',
        'edit' =>   'receptionist.bills.edit',
        'update' => 'receptionist.bills.update',
        //'destroy' =>'receptionist.bills.destroy',
    ]);

    Route::get('bills/{bill}/print', [BillController::class, 'print'])->name('receptionist.bills.print');
    Route::get('bills/{bill}/receipt', [BillController::class, 'receipt'])->name('receptionist.bills.receipt');

    Route::resource('services', ServiceController::class)->names([
        'index' =>  'receptionist.services.index',
        'show' =>   'receptionist.services.show',
        /*'create' => 'receptionist.services.create',
        'store' =>  'receptionist.services.store',
        'edit' =>   'receptionist.services.edit',
        'update' => 'receptionist.services.update',
        'destroy' =>'receptionist.services.destroy',*/
    ]);

    Route:: resource('staffs',StaffController::class)->names([
        'index' =>  'receptionist.staffs.index',
        'show' =>   'receptionist.staffs.show',
        /*'create' => 'receptionist.staffs.create',
        'store' =>  'receptionist.staffs.store',
        'edit' =>   'receptionist.staffs.edit',
        'update' => 'receptionist.staffs.update',
        'destroy' =>'receptionist.staffs.destroy',*/
    ]);

});


// Redirect root to login
Route::get('/', function () {
    return redirect('/login');
});
