<?php

use App\Http\Controllers\BillController;

// Manager routes
Route::resource('services', App\Http\Controllers\ServiceController::class);

// Receptionist routes

Route::get('/bills', [BillController::class, 'index'])->name('bills.index');
Route::get('/bills/create', [BillController::class, 'create'])->name('bills.create');
Route::post('/bills', [BillController::class, 'store'])->name('bills.store');
