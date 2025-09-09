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
<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex items-center justify-center bg-cover bg-center bg-no-repeat" 
         style="background-image: url('/images/school.jpg');">

        <!-- GLASSMORPHISM BOX -->
        <div class="w-full max-w-md bg-white/10 backdrop-blur-lg rounded-2xl shadow-2xl p-10 text-center border border-white/20">
            
            <!-- Dynamic Header -->
            @if (request()->routeIs('login'))
                <h1 class="text-4xl font-bold text-white drop-shadow-md">Welcome Back!</h1>
            @elseif (request()->routeIs('register'))
                <h1 class="text-4xl font-bold text-white drop-shadow-md">Create an account</h1>
            @endif

            <p class="mt-2 text-white/90">Scholarship Management System</p>

            <!-- Form Slot -->
            <div class="mt-8 text-left">
                {{ $slot }}
            </div>
        </div>
    </div>
</body>
</html>
