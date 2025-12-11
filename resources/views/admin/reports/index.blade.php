@extends('layouts.admin-layout')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold mb-6">Reports & Monitoring</h1>

        {{-- DOST Section --}}
        <h2 class="text-xl font-bold mb-3 text-black-700">DOST Reports</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
            @php
                $dostReports = [
                    [
                        'title' => 'List of All Applicants',
                        'desc' => 'Shows all applicants and their current status.',
                        'route' => 'admin.reports.applicants',
                        'export_route' => 'reports.dost.export', // ✅ Added export route
                    ],
                    [
                        'title' => 'Monitoring of Scholars',
                        'desc' => 'Track DOST scholars, their statuses, and compliance.',
                        'route' => 'admin.reports.monitoring',
                        'export_route' => 'reports.dost.export', // ✅ Added export route
                    ],
                ];
            @endphp

            @foreach ($dostReports as $card)
                <div
                    class="bg-white/20 backdrop-blur-md border border-white/20 shadow-lg rounded-2xl p-6 transition hover:shadow-xl">
                    <h2 class="text-lg font-semibold mb-2 text-gray-800">{{ $card['title'] }}</h2>
                    <p class="text-sm text-gray-700 mb-3">{{ $card['desc'] }}</p>
                    
                    {{-- ✅ Updated: Added flex container with both buttons --}}
                    <div class="flex gap-2">
                        <a href="{{ route($card['route']) }}"
                            class="inline-block bg-blue-900 text-white text-sm px-4 py-2 rounded-md shadow-md hover:bg-blue-700/90 transition font-semibold">
                            Open
                        </a>
                        
                        {{-- ✅ NEW: Export Button --}}
                        <a href="{{ route($card['export_route']) }}"
                            class="inline-flex items-center gap-2 bg-green-600 text-white text-sm px-4 py-2 rounded-md shadow-md hover:bg-green-700 transition font-semibold">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"/>
                            </svg>
                            Export
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- CHED Section --}}
        <h2 class="text-xl font-bold mb-3 text-black-700">CHED Reports</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @php
                $chedReports = [
                    [
                        'title' => 'Monitoring of Scholars',
                        'desc' => 'Monitor CHED scholars and view their compliance and performance.',
                        'route' => 'admin.reports.ched-monitoring',
                        'export_route' => 'reports.ched.export', // ✅ Added export route
                    ],
                ];
            @endphp

            @foreach ($chedReports as $card)
                <div
                    class="bg-white/20 backdrop-blur-md border border-white/20 shadow-lg rounded-2xl p-6 transition hover:shadow-xl">
                    <h2 class="text-lg font-semibold mb-2 text-gray-800">{{ $card['title'] }}</h2>
                    <p class="text-sm text-gray-700 mb-3">{{ $card['desc'] }}</p>
                    
                    {{-- ✅ Updated: Added flex container with both buttons --}}
                    <div class="flex gap-2">
                        <a href="{{ route($card['route']) }}"
                            class="inline-block bg-blue-900 text-white text-sm px-4 py-2 rounded-md shadow-md hover:bg-blue-700/90 transition font-semibold">
                            Open
                        </a>
                        
                        {{-- ✅ NEW: Export Button --}}
                        <a href="{{ route($card['export_route']) }}"
                            class="inline-flex items-center gap-2 bg-green-600 text-white text-sm px-4 py-2 rounded-md shadow-md hover:bg-green-700 transition font-semibold">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"/>
                            </svg>
                            Export
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
```

---

## What Changed?

### Visual Layout (Each Card):
```
┌─────────────────────────────────────┐
│ List of All Applicants              │
│ Shows all applicants and their...   │
│                                     │
│ [Open]  [Export ⬇]                 │
└─────────────────────────────────────┘