<?php

// Manager routes
Route::middleware(['auth', 'role:manager'])->group(function() {
    Route::get('/services', [ServiceController::class, 'index']);
    Route::get('/services/create', [ServiceController::class, 'create']);
    Route::post('/services', [ServiceController::class, 'store']);
});

// Receptionist routes
Route::middleware(['auth', 'role:receptionist'])->group(function() {
    Route::get('/bills', [BillController::class, 'index']);
    Route::get('/bills/create', [BillController::class, 'create']);
    Route::post('/bills', [BillController::class, 'store']);
});
