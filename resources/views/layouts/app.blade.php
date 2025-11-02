<?php 
    use Illuminate\Support\Facades\Auth;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ Auth::user()->salon?->name ?? 'My' }} Billing App</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">

    <style>
        /* Custom Navbar Styles */
        .navbar-custom {
            background-color: #32bbed !important;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .navbar-custom .navbar-brand {
            color: #fff !important;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .navbar-custom .nav-link {
            color: #fff !important;
            transition: color 0.3s, background-color 0.3s;
            border-radius: 4px;
            padding: 8px 15px;
        }

        .navbar-custom .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.15);
            color: #f0f0f0 !important;
        }

        .navbar-custom .btn-link.nav-link {
            color: #fff !important;
        }

        .navbar-toggler {
            border: none;
            background: rgba(255, 255, 255, 0.3);
        }

        .navbar-toggler-icon {
            filter: brightness(0) invert(1);
        }

        body {
            background-color: #f8fafc;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-custom mb-4">
        @php
            $dashboard = '#';
            if(Auth::user()->role == 'manager') {
                $dashboard = route('dashboard.manager');
            } elseif(Auth::user()->role == 'receptionist') {
                $dashboard = route('dashboard.receptionist');
            }
        @endphp
        <div class="container">
            <!-- Brand -->
            <a class="navbar-brand" href="{{ $dashboard }}">
                {{ Auth::user()->salon?->name ?? 'My' }} Billing App
            </a>

            <!-- Toggler -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navbar Links -->
            <div class="collapse navbar-collapse" id="navbarMenu">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item">
                        @if(auth()->user()->role === 'manager')
                            <a class="nav-link" href="{{ route('manager.services.index') }}">Services</a>
                        @elseif(auth()->user()->role === 'receptionist')
                            <a class="nav-link" href="{{ route('receptionist.services.index') }}">Services</a>
                        @endif
                    </li>

                        
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('users.index') }}">Users</a>
                        </li>
                        @php
                            $prefix = auth()->user()->role === 'manager' ? 'manager' : 'receptionist';
                              
                        @endphp

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route($prefix . '.bills.create') }}">New Bill</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route($prefix .'.bills.index') }}">All Bills</a>
                        </li>

                    <li class="nav-item ms-lg-3">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-light btn-sm px-3">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        @yield('content')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
