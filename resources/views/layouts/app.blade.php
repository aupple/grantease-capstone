<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 flex">

        <!-- ✅ Sidebar -->
        <aside class="w-64 bg-white shadow-md hidden md:block">
            <!-- GrantEase Logo Box -->
            <div
                class="bg-[#0a1f44] text-white border-b border-[#081a38] px-6 py-5 flex items-center justify-center text-2xl font-bold">
                GrantEase
            </div>


            <!-- Sidebar Navigation -->
            <nav class="p-6 space-y-4 text-sm bg-[#0a1f44] text-white h-full">

                <!-- Dashboard Link -->
                <a href="{{ route('applicant.dashboard') }}"
                    class="flex items-center gap-2 font-medium hover:bg-blue-700 p-2 rounded">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path
                            d="M3 12l2-2m0 0l7-7 7 7m-9 2v6m4-6v6m5-6h2a2 2 0 012 2v7a2 2 0 01-2 2h-2.5m-13 0H5a2 2 0 01-2-2v-7a2 2 0 012-2h2"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Dashboard
                </a>

                <!-- Application Status -->
                <a href="{{ route('applicant.application.view') }}"
                    class="flex items-center gap-2 font-medium hover:bg-blue-700 p-2 rounded">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path d="M9 17v-6h13m-2 0l-5-5-5 5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Application Status
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
                            <path
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        Logout
                    </button>
                </form>
            </nav>
        </aside>

        <!-- ✅ Main Content Area -->
        <div class="flex-1 flex flex-col">

            <!-- ✅ Top Bar -->
            <div class="bg-[#ffbf00] shadow border-b border-gray-200 px-6 py-5 flex justify-between items-center">
                <!-- Left: Page Title -->
                <h1 class="text-1.5xl font-bold text - white">
                    {{ $headerTitle ?? 'University of Science and Technology of Southern Philippines' }}
                </h1>

                <!-- Right: Profile Icon -->
                <!-- Right: Profile Icon -->
                <a href="{{ route('profile.edit') }}" class="text-gray-600 hover:text-blue-600" title="Edit Profile">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5.121 17.804A8.966 8.966 0 0112 15c2.485 0 4.735 1.015 6.379 2.646M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </a>

            </div>

            <!-- ✅ Page Content -->
            <main class="flex-1">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>

</html>
