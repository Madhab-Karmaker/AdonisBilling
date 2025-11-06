<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bill;
use App\Models\Service;
use App\Models\User;

class ReceptionistDashboardController extends Controller
{
    public function index() {

        $user = auth()->user();
        $totalUsers = User::where('salon_id', $user->salon_id)->count();
        $totalServices = Service::where('salon_id', $user->salon_id)->count();
        $totalBills = Bill::where('salon_id', $user->salon_id)
            ->whereDate('created_at', today())
            ->count();
        return view('dashboard.receptionist', compact(
            'user',
            'totalUsers',
            'totalServices',
            'totalBills'
        ));

    }
}
