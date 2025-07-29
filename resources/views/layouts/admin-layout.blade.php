<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-gray-100 font-sans antialiased">
    <div class="min-h-screen flex">

      <!-- ✅ Sidebar -->
<aside class="w-64 bg-white shadow-md hidden md:block">
    <!-- GrantEase Admin Logo Box -->
   <div class="bg-gray-50 px-1 py-1 flex items-center justify-center h-20 border-b border-gray-200">
    <img src="{{ asset('images/logo.png') }}" alt="GrantEase Logo" class="max-h-full max-w-full object-contain">
</div>


    <!-- Sidebar Navigation -->
    <nav class="p-6 space-y-4 text-sm bg-[#0a1f44] text-white h-full">

        <!-- Dashboard -->
        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center gap-2 font-medium hover:bg-blue-700 p-2 rounded {{ request()->routeIs('admin.dashboard') ? 'bg-blue-800' : '' }}">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2"
                 viewBox="0 0 24 24">
                <path d="M3 12l2-2m0 0l7-7 7 7m-9 2v6m4-6v6m5-6h2a2 2 0 012 2v7a2 2 0 01-2 2h-2.5m-13 0H5a2 2 0 01-2-2v-7a2 2 0 012-2h2"
                      stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Dashboard
        </a>

        <!-- Applications -->
        <a href="{{ route('admin.applications') }}"
           class="flex items-center gap-2 font-medium hover:bg-blue-700 p-2 rounded {{ request()->routeIs('admin.applications') ? 'bg-blue-800' : '' }}">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2"
                 viewBox="0 0 24 24">
                <path d="M9 17v-6h13m-2 0l-5-5-5 5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Applications
        </a>

        <!-- Scholars -->
        <a href="{{ route('admin.scholars') }}"
           class="flex items-center gap-2 font-medium hover:bg-blue-700 p-2 rounded {{ request()->routeIs('admin.scholars') ? 'bg-blue-800' : '' }}">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2"
                 viewBox="0 0 24 24">
                <path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Scholars
        </a>

        <!-- Reports -->
        <a href="{{ route('admin.reports.index') }}"
           class="flex items-center gap-2 font-medium hover:bg-blue-700 p-2 rounded {{ request()->routeIs('admin.reports.index') ? 'bg-blue-800' : '' }}">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2"
                 viewBox="0 0 24 24">
                <path d="M11 17a2.5 2.5 0 005 0m-5 0a2.5 2.5 0 00-5 0m5 0v4m-6-4H5a2 2 0 01-2-2V5a2 2 0 012-2h14a2 2 0 012 2v10a2 2 0 01-2 2h-2"
                      stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Reports
        </a>

        <!-- Divider -->
        <hr class="border-t border-blue-300 my-4">

        <!-- Logout -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="flex items-center gap-2 font-medium hover:bg-blue-700 p-2 rounded w-full text-white">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24">
                    <path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1"
                          stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Logout
            </button>
        </form>
    </nav>
</aside>

        <!-- ✅ Main Content Area -->
        <main class="flex-1">

            <!-- ✅ Top Navbar with Profile Dropdown -->
            <header class="bg-[#ffbf00] text-white shadow px-4 py-3 flex justify-between items-center">
                <div class="text-lg font-bold text-gray-700">Admin Panel</div>

                <div class="relative">
                    <button onclick="toggleDropdown()" class="flex items-center space-x-2 focus:outline-none">
    <span class="text-sm font-medium">{{ auth()->user()->first_name }}</span>
    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->first_name) }}" class="w-8 h-8 rounded-full" alt="Profile">
</button>


                    <!-- ✅ Dropdown -->
                    <div id="dropdown" class="absolute right-0 mt-2 w-48 bg-white border rounded shadow-lg hidden">
                        <a href="{{ route('admin.profile.edit') }}" class="block px-4 py-2 text-sm hover:bg-gray-100">Edit Profile</a>
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
