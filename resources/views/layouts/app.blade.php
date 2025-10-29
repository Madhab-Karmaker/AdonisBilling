<?php 
    use Illuminate\Support\Facades\Auth;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ Auth::user()->salon?->name ?? 'My' }} Billing App</title>
    <!-- Bootstrap CSS for simplicity -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
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

            <!-- Navbar Links -->
            <div class="collapse navbar-collapse" id="navbarMenu">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('services.index') }}">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('bills.index') }}">Bills</a>
                    </li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-link nav-link">Logout</button>
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
