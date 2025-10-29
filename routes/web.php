<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\ServiceController;

// Authenticated routes
Route::middleware(['auth'])->group(function() {
    Route::resource('users', UserController::class);
}); 
// Manager routes
Route::resource('services', ServiceController::class);

// Receptionist routes
Route::get('/bills', [BillController::class, 'index'])->name('bills.index');
Route::get('/bills/create', [BillController::class, 'create'])->name('bills.create');
Route::post('/bills', [BillController::class, 'store'])->name('bills.store');


Route::get('/login', function() {
    return view('auth.login');
})->name('login');
