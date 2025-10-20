@extends('layouts.admin-layout')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold mb-6">Reports & Monitoring</h1>

        {{-- DOST Section --}}
        <h2 class="text-xl font-bold mb-3 text-blue-700">DOST Reports</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
            @php
                $dostReports = [
                    [
                        'title' => 'List of All Applicants',
                        'desc' => 'Shows all applicants and their current status.',
                        'route' => 'admin.reports.applicants',
                    ],
                    [
                        'title' => 'Monitoring of Scholars',
                        'desc' => 'Track DOST scholars, their statuses, and compliance.',
                        'route' => 'admin.reports.monitoring',
                    ],
                ];
            @endphp

            @foreach ($dostReports as $card)
                <div
                    class="bg-white/20 backdrop-blur-md border border-white/20 shadow-lg rounded-2xl p-6 transition hover:shadow-xl">
                    <h2 class="text-lg font-semibold mb-2 text-gray-800">{{ $card['title'] }}</h2>
                    <p class="text-sm text-gray-700 mb-3">{{ $card['desc'] }}</p>
                    <a href="{{ route($card['route']) }}"
                        class="inline-block bg-blue-900 text-white text-sm px-4 py-2 rounded-md shadow-md hover:bg-blue-700/90 transition font-semibold">
                        Open
                    </a>
                </div>
            @endforeach
        </div>

        {{-- CHED Section --}}
        <h2 class="text-xl font-bold mb-3 text-green-700">CHED Reports</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @php
                $chedReports = [
                    [
                        'title' => 'Monitoring of Scholars',
                        'desc' => 'Monitor CHED scholars and view their compliance and performance.',
                        'route' => 'admin.reports.ched-monitoring',
                    ],
                ];
            @endphp

            @foreach ($chedReports as $card)
                <div
                    class="bg-white/20 backdrop-blur-md border border-white/20 shadow-lg rounded-2xl p-6 transition hover:shadow-xl">
                    <h2 class="text-lg font-semibold mb-2 text-gray-800">{{ $card['title'] }}</h2>
                    <p class="text-sm text-gray-700 mb-3">{{ $card['desc'] }}</p>
                    <a href="{{ route($card['route']) }}"
                        class="inline-block bg-green-700 text-white text-sm px-4 py-2 rounded-md shadow-md hover:bg-green-600 transition font-semibold">
                        Open
                    </a>
                </div>
            @endforeach
        </div>
    </div>
@endsection
