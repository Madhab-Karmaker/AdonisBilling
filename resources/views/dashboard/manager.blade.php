@extends('layouts.app')

@push('styles')
<style>
    /* Keep all your previous CSS here */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #f8f9fa;
        color: #333;
    }
    
    .header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1rem 2rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
    }
    
    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .header h1 {
        font-size: 1.8rem;
        font-weight: 600;
    }
    
    .user-info {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .user-info span {
        font-size: 0.9rem;
        opacity: 0.9;
    }
    
    .logout-btn {
        background: rgba(255,255,255,0.2);
        color: white;
        border: 1px solid rgba(255,255,255,0.3);
        padding: 0.5rem 1rem;
        border-radius: 5px;
        text-decoration: none;
        transition: background 0.3s ease;
    }
    
    .logout-btn:hover {
        background: rgba(255,255,255,0.3);
    }
    
    .welcome-card {
        background: white;
        padding: 2rem;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
    }
    
    .welcome-card h2 {
        color: #333;
        margin-bottom: 0.5rem;
    }
    
    .welcome-card p {
        color: #666;
        font-size: 1.1rem;
    }
    
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
        margin-bottom: 2rem;
    }
    
    .dashboard-card {
        background: white;
        padding: 2rem;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
    }
    
    .dashboard-card:hover {
        transform: translateY(-5px);
    }
    
    .dashboard-card h3 {
        color: #333;
        margin-bottom: 1rem;
        font-size: 1.3rem;
    }
    
    .dashboard-card p {
        color: #666;
        margin-bottom: 1.5rem;
        line-height: 1.6;
    }
    
    .card-actions {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }
    
    .btn {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 5px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    }
    
    .btn-secondary {
        background: #6c757d;
        color: white;
    }
    
    .btn-secondary:hover {
        background: #5a6268;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-top: 2rem;
        padding-bottom: 10px;
    }
    
    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        text-align: center;
    }
    
    .stat-number {
        font-size: 2rem;
        font-weight: bold;
        color: #667eea;
        margin-bottom: 0.5rem;
    }
    
    .stat-label {
        color: #666;
        font-size: 0.9rem;
    }
</style>
@endpush

@section('content')

<div class="container">
    <div class="welcome-card">
        <h2>Welcome back, {{ $user->name }}!</h2>
        <p>You have full administrative access to manage users, services, and view reports.</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number">{{ $totalUsers }}</div>
            <div class="stat-label">Total Users</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $totalServices }}</div>
            <div class="stat-label">Total Services</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $todayBills }}</div>
            <div class="stat-label">Today's Bills</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">${{ $todayRevenue ?? 0 }}</div>
            <div class="stat-label">Today's Revenue</div>
        </div>
    </div>

    <div class="dashboard-grid">
        <!-- User Management Card -->
        <div class="dashboard-card">
            <h3>👥 User Management</h3>
            <p>Manage receptionists and other staff members. Add new users, edit permissions, and monitor access.</p>
            <div class="card-actions">
                <a href="{{ route('users.index') }}" class="btn btn-primary">View Users</a>
                <a href="{{ route('users.create') }}" class="btn btn-secondary">Add User</a>
            </div>
        </div>

        <!-- Service Management Card -->
        <div class="dashboard-card">
            <h3>💼 Service Management</h3>
            <p>Create and manage salon services, set pricing, and organize service categories.</p>
            <div class="card-actions">
                <a href="{{ route('manager.services.index') }}" class="btn btn-primary">View Services</a>
                <a href="{{ route('manager.services.create') }}" class="btn btn-secondary">Add Service</a>
            </div>
        </div>

        <!-- Reports & Analytics Card -->
        <div class="dashboard-card">
            <h3>📊 Reports & Analytics</h3>
            <p>View comprehensive reports on billing, revenue, and salon performance metrics.</p>
            <div class="card-actions">
                <a href="#" class="btn btn-primary">View Reports</a>
                <a href="#" class="btn btn-secondary">Export Data</a>
            </div>
        </div>

        <!-- Salon Settings Card -->
        <div class="dashboard-card">
            <h3>🏢 Salon Settings</h3>
            <p>Configure salon information, business hours, and system preferences.</p>
            <div class="card-actions">
                <a href="#" class="btn btn-primary">Settings</a>
                <a href="#" class="btn btn-secondary">Profile</a>
            </div>
        </div>
    </div>

    
</div>
@endsection
