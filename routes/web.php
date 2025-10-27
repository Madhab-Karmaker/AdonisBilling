<?php

// Manager routes
Route::resource('services', App\Http\Controllers\ServiceController::class);

// Receptionist routes
Route::resource('bills', App\Http\Controllers\BillController::class);

