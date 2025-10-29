<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

User::create([
    'name' => 'Admin Manager',
    'email' => 'manager@salon.com',
    'password' => Hash::make('password'),
    'role' => 'manager',
    'salon_id' => 1
]);

User::create([
    'name' => 'Receptionist',
    'email' => 'receptionist@salon.com',
    'password' => Hash::make('password'),
    'role' => 'receptionist',
    'salon_id' => 1
]);
