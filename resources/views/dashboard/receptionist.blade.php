@extends('layouts.app')

@section('content')
    <style>
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
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 1rem 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
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
        
        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 2rem;
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
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
        }
        
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        
        .btn-secondary:hover {
            background: #5a6268;
        }
        
        .quick-actions {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        
        .quick-actions h3 {
            color: #333;
            margin-bottom: 1.5rem;
            font-size: 1.3rem;
        }
        
        .quick-actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }
        
        .quick-action-btn {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            padding: 1rem;
            border-radius: 8px;
            text-decoration: none;
            color: #333;
            text-align: center;
            transition: all 0.3s ease;
        }
        
        .quick-action-btn:hover {
            background: #e9ecef;
            border-color: #28a745;
            color: #28a745;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
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
            color: #28a745;
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            color: #666;
            font-size: 0.9rem;
        }
    </style>
    <div class="header">
        <div class="header-content">
            <h1>Receptionist Dashboard</h1>
            <div class="user-info">
                <span>Welcome, {{ $user->name }}</span>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="welcome-card">
            <h2>Welcome back, {{ $user->name }}!</h2>
            <p>You have access to billing and customer management features.</p>
        </div>

        <div class="quick-actions">
            <h3>🚀 Quick Actions</h3>
            <div class="quick-actions-grid">
                <a href="{{ route('receptionist.bills.create') }}" class="quick-action-btn">
                    <div style="font-size: 2rem; margin-bottom: 0.5rem;">💳</div>
                    <div><strong>Create New Bill</strong></div>
                    <div style="font-size: 0.8rem; color: #666;">Start billing process</div>
                </a>
                <a href="{{ route('receptionist.bills.index') }}" class="quick-action-btn">
                    <div style="font-size: 2rem; margin-bottom: 0.5rem;">📋</div>
                    <div><strong>View All Bills</strong></div>
                    <div style="font-size: 0.8rem; color: #666;">Browse recent bills</div>
                </a>
                <a href="#" class="quick-action-btn">
                    <div style="font-size: 2rem; margin-bottom: 0.5rem;">👥</div>
                    <div><strong>Customer Lookup</strong></div>
                    <div style="font-size: 0.8rem; color: #666;">Find customer info</div>
                </a>
                <a href="#" class="quick-action-btn">
                    <div style="font-size: 2rem; margin-bottom: 0.5rem;">📊</div>
                    <div><strong>Today's Summary</strong></div>
                    <div style="font-size: 0.8rem; color: #666;">View daily stats</div>
                </a>
            </div>
        </div>

        <div class="dashboard-grid">
            <div class="dashboard-card">
                <h3>💳 Billing Management</h3>
                <p>Create new bills, process payments, and manage customer transactions efficiently.</p>
                <div class="card-actions">
                    <a href="{{ route('receptionist.bills.create') }}" class="btn btn-primary">New Bill</a>
                    <a href="{{ route('receptionist.bills.index') }}" class="btn btn-secondary">View Bills</a>
                </div>
            </div>

            <div class="dashboard-card">
                <h3>👥 Customer Management</h3>
                <p>Access customer information, view billing history, and manage customer relationships.</p>
                <div class="card-actions">
                    <a href="#" class="btn btn-primary">Customer List</a>
                    <a href="#" class="btn btn-secondary">Add Customer</a>
                </div>
            </div>

            <div class="dashboard-card">
                <h3>📅 Appointments</h3>
                <p>Schedule appointments, manage bookings, and track customer visits.</p>
                <div class="card-actions">
                    <a href="#" class="btn btn-primary">View Schedule</a>
                    <a href="#" class="btn btn-secondary">Book Appointment</a>
                </div>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">0</div>
                <div class="stat-label">Today's Bills</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">$0</div>
                <div class="stat-label">Today's Revenue</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">0</div>
                <div class="stat-label">Pending Bills</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">0</div>
                <div class="stat-label">Total Customers</div>
            </div>
        </div>
    </div>
@endsection
