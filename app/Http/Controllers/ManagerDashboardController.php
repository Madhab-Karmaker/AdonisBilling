<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Service;
use App\Models\Bill;

class ManagerDashboardController extends Controller
{
    // Display the manager dashboard
    public function index()
    {
        // Get the currently logged-in user
        $user = auth()->user();

        // Count total users in the same salon
        $totalUsers = User::where('salon_id', $user->salon_id)->count();

        // Count total services in the same salon
        $totalServices = Service::where('salon_id', $user->salon_id)->count();

        // Count total bills created today for the salon
        $todayBills = Bill::where('salon_id', $user->salon_id)
            ->whereDate('created_at', today())
            ->count();

        // Sum today's revenue (total_amount) for the salon
        $todayRevenue = Bill::where('salon_id', $user->salon_id)
            ->whereDate('created_at', today())
            ->sum('total_amount');

        // Pass all data to the manager dashboard view
        return view('dashboard.manager', compact(
            'user',
            'totalUsers',
            'totalServices',
            'todayBills',
            'todayRevenue'
        ));
    }
}
