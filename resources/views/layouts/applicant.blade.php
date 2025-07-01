<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Laravel'))</title>

    <!-- Fonts & Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')

    <style>
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
        }
        .dashboard-wrapper {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, #001e60, #003087);
            color: #fff;
            padding: 30px 20px;
            box-shadow: 2px 0 8px rgba(0, 0, 0, 0.05);
        }
        .sidebar .logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 2rem;
        }
        .sidebar .logo img {
            width: 50px;
            height: 50px;
            object-fit: contain;
        }
        .sidebar .logo span {
            font-size: 1.5rem;
            font-weight: bold;
        }
        .sidebar nav ul {
            list-style: none;
            padding: 0;
        }
        .sidebar nav ul li {
            margin: 0.5rem 0;
            border-radius: 8px;
        }
        .sidebar nav ul li a {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 10px 15px;
            border-left: 4px solid transparent;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }
        .sidebar nav ul li a:hover {
            background-color: rgba(255, 255, 255, 0.1);
            border-left: 4px solid #93c5fd;
        }
        .sidebar nav ul li i {
            margin-right: 12px;
            width: 20px;
            text-align: center;
        }
        .main-content {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }
        .top-bar {
            background-color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem;
            border-bottom: 1px solid #e5e7eb;
        }
        .top-bar .title {
            font-size: 1.25rem;
            font-weight: bold;
            color: #001e60;
        }
        .user-menu {
            position: relative;
        }
        .dropdown-toggle {
            background: none;
            border: none;
            font-weight: 600;
            color: #001e60;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .dropdown-toggle i {
            transition: transform 0.3s ease;
        }
        .dropdown-toggle.open i {
            transform: rotate(180deg);
        }
        .dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            top: 2.5rem;
            background-color: white;
            border: 1px solid #ccc;
            border-radius: 8px;
            min-width: 160px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            z-index: 999;
        }
        .dropdown-menu a,
        .dropdown-menu form button {
            display: block;
            width: 100%;
            padding: 0.75rem 1rem;
            text-align: left;
            background: none;
            border: none;
            color: #111827;
            cursor: pointer;
        }
        .dropdown-menu a:hover,
        .dropdown-menu form button:hover {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>
    <div class="dashboard-wrapper">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="logo">
                <img src="{{ asset('images/ustp.png') }}" alt="Logo">
                <span>GrantEase</span>
            </div>
            <nav>
                <ul>
                    <li><a href="{{ route('applicant.dashboard') }}"><i class="fa fa-home"></i> Dashboard</a></li>
                    <li><a href="{{ route('applicant.apply') }}"><i class="fa fa-edit"></i> Apply</a></li>
                    <li><a href="{{ route('applicant.status') }}"><i class="fa fa-clipboard-check"></i> Application Status</a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="main-content">
            <div class="top-bar">
                <div class="title">@yield('title', 'Applicant Dashboard')</div>
                <div class="user-menu">
                    <button id="dropdown-toggle" class="dropdown-toggle" onclick="toggleDropdown()">
                        {{ auth()->user()->name }} <i class="fa fa-chevron-down"></i>
                    </button>
                    <div id="dropdown-menu" class="dropdown-menu">
                        <a href="{{ route('profile.edit') }}"><i class="fa fa-user"></i> Profile</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"><i class="fa fa-sign-out-alt"></i> Logout</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Page Content -->
            <main class="p-6">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')

    <script>
        function toggleDropdown() {
            const menu = document.getElementById('dropdown-menu');
            const toggleBtn = document.getElementById('dropdown-toggle');
            const isOpen = menu.style.display === 'block';
            menu.style.display = isOpen ? 'none' : 'block';
            toggleBtn.classList.toggle('open', !isOpen);
        }

        document.addEventListener('click', function (e) {
            const toggle = document.getElementById('dropdown-toggle');
            const menu = document.getElementById('dropdown-menu');
            if (!toggle.contains(e.target) && !menu.contains(e.target)) {
                menu.style.display = 'none';
                toggle.classList.remove('open');
            }
        });
    </script>
</body>
</html>
