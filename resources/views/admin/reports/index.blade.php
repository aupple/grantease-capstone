    @extends('layouts.admin-layout')

    @section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold mb-6">📊 Reports & Monitoring</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    @php
        $reportCards = [
            [
                'title' => 'List of All Applicants',
                'desc' => 'Shows all applicants and their current status.',
                'route' => 'admin.reports.applicants'
            ],
            [
                'title' => 'Monitoring of All Scholars',
                'desc' => 'Track scholars, their statuses, and compliance.',
                'route' => 'admin.reports.monitoring'
            ]
        ];
    @endphp

    @foreach ($reportCards as $card)
        <div class="bg-white/20 backdrop-blur-md border border-white/20 shadow-lg rounded-2xl p-6 transition hover:shadow-xl">
            <h2 class="text-lg font-semibold mb-2 text-gray-800">{{ $card['title'] }}</h2>
            <p class="text-sm text-gray-700 mb-3">{{ $card['desc'] }}</p>
            <a href="{{ route($card['route']) }}"
               class="inline-block bg-blue-900 text-white text-sm px-4 py-2 rounded-md shadow-md hover:bg-blue-700/90 transition font-semibold">
                Open
            </a>
        </div>
    @endforeach
</div>
    </div>
    @endsection
