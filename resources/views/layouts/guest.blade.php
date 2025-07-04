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
    <!-- Full background wrapper -->
    <div class="min-h-screen flex flex-col items-center justify-center bg-cover bg-center py-8 px-4"
         style="background-image: url('{{ asset('images/bg-campus.jpg') }}');">

        <!-- Slot only (no card here) -->
        {{ $slot }}

    </div>
</body>
</html>
