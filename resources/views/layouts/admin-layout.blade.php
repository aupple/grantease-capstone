<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans antialiased">
    <div class="min-h-screen flex">

        <!-- ✅ Sidebar -->
        <aside class="w-64 bg-white shadow-md hidden md:block">
            <div class="p-6 text-lg font-bold border-b">
                GrantEase Admin
            </div>
            <nav class="p-4 space-y-2 text-sm">
                <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded hover:bg-blue-100 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 font-semibold' : '' }}">
                    🏠 Dashboard
                </a>
                <a href="{{ route('admin.applications') }}" class="block px-3 py-2 rounded hover:bg-blue-100 {{ request()->routeIs('admin.applications') ? 'bg-blue-50 font-semibold' : '' }}">
                    📑 Applications
                </a>
                <a href="{{ route('admin.scholars') }}" class="block px-3 py-2 rounded hover:bg-blue-100 {{ request()->routeIs('admin.scholars') ? 'bg-blue-50 font-semibold' : '' }}">
                    🎓 Scholars
                </a>
                <a href="{{ route('admin.reports') }}" class="block px-3 py-2 rounded hover:bg-blue-100 {{ request()->routeIs('admin.reports') ? 'bg-blue-50 font-semibold' : '' }}">
                    📊 Reports
                </a>
            </nav>
        </aside>

        <!-- ✅ Main Content Area -->
        <main class="flex-1">

            <!-- ✅ Top Navbar with Profile Dropdown -->
            <header class="bg-white shadow px-4 py-3 flex justify-between items-center">
                <div class="text-lg font-semibold text-gray-700">
                    Admin Panel
                </div>

                <div class="relative">
                    <button onclick="toggleDropdown()" class="flex items-center space-x-2 focus:outline-none">
    <span class="text-sm font-medium">{{ auth()->user()->first_name }}</span>
    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->first_name) }}" class="w-8 h-8 rounded-full" alt="Profile">
</button>


                    <!-- ✅ Dropdown -->
                    <div id="dropdown" class="absolute right-0 mt-2 w-48 bg-white border rounded shadow-lg hidden">
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm hover:bg-gray-100">Edit Profile</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100">Logout</button>
                        </form>
                    </div>
                </div>
            </header>

            <section class="p-6">
                @yield('content')
            </section>
        </main>
    </div>

    <!-- ✅ Toggle Script -->
    <script>
        function toggleDropdown() {
            const dropdown = document.getElementById('dropdown');
            dropdown.classList.toggle('hidden');
        }

        // Optional: close dropdown if clicked outside
        window.addEventListener('click', function(e) {
            const dropdown = document.getElementById('dropdown');
            if (!e.target.closest('button') && !e.target.closest('#dropdown')) {
                dropdown.classList.add('hidden');
            }
        });
    </script>

    <!-- ✅ This enables scripts from @push('scripts') -->
    @stack('scripts')
</body>
</html>

</body>
</html>
