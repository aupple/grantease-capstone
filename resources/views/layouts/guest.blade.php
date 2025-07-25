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
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-cover bg-center bg-no-repeat" style="background-image: url('/images/school.jpg');">

        <!-- Branding OUTSIDE the box -->
        <div class="text-center mb-6">
    <img src="{{ asset('images/logo.png') }}" alt="GrantEase Logo" class="mx-auto w-[450px]">
    <p class="text-sm font-semibold text-gray-500 mt-1">Scholarship Management System</p>


            <div class="mt-3">
                @if (request()->routeIs('register'))
                    <h1 class="text-2xl font-bold text-blue">Create an account</h1>
                     <p class="text-sm text-gray-500 mt-1">Start your scholarship journey today</p>
                @else
                    <h1 class="text-2xl font-bold text-black">Sign in to your account</h1>
                    <p class="text-sm font-semibold text-gray-500 mt-1">Access your scholarship</p>
                @endif
            </div>
        </div>

        <!-- White Form Box -->
        <div class="w-full sm:max-w-md mt-1 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            {{ $slot }}
        </div>
    </div>
</body>
</html>
