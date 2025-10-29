<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Salon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a sample salon first
        $salon = Salon::create([
            'name' => 'Beauty Palace Salon',
            'address' => '123 Main Street, City, State',
            'phone' => '+1-555-0123',
            'email' => 'info@beautypalace.com',
        ]);

        // Create manager user
        User::create([
            'name' => 'John Manager',
            'email' => 'manager@salon.com',
            'password' => Hash::make('password123'),
            'role' => 'manager',
            'salon_id' => $salon->id,
        ]);

        // Create receptionist user
        User::create([
            'name' => 'Jane Receptionist',
            'email' => 'receptionist@salon.com',
            'password' => Hash::make('password123'),
            'role' => 'receptionist',
            'salon_id' => $salon->id,
        ]);

        // Create another receptionist
        User::create([
            'name' => 'Sarah Wilson',
            'email' => 'sarah@salon.com',
            'password' => Hash::make('password123'),
            'role' => 'receptionist',
            'salon_id' => $salon->id,
        ]);
    }
}
