<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GrantEase Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Main Dashboard Styles -->
    <link rel="stylesheet" href="{{ asset('css/applicant/home.css') }}">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
          integrity="sha512-papbV9yZ45aRyB5VkkmjGZnEbiu3A9o1DN+LZo0QqPfUtsU/CzkcvK5YEDCHM3gIKBqWQJqR8MdZwJuw8Is9XQ=="
          crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Page-specific CSS -->
    @stack('styles')
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <h2 class="logo">GrantEase</h2>
            <nav>
                <ul>
                    <li class="{{ request()->is('applicant/home') ? 'active' : '' }}">
                        <i class="fa fa-home"></i> Dashboard
                    </li>
                    <li><i class="fa fa-pen"></i> Apply</li>
                    <li><i class="fa fa-list"></i> Application Status</li>
                </ul>
            </nav>
            <a href="/logout" class="logout"><i class="fa fa-sign-out-alt"></i> Logout</a>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="topbar">
                <h1>Dashboard</h1>
                <div class="icons">
                    <i class="fa fa-bell"></i>
                    <i class="fa fa-user-circle"></i>
                </div>
            </header>

            <!-- Dynamic Page Content -->
            @yield('content')
        </main>
    </div>

    <!-- Main JS File -->
    <script src="{{ asset('js/applicant/home.js') }}" defer></script>

    <!-- Page-specific Scripts -->
    @stack('scripts')
</body>
</html>
