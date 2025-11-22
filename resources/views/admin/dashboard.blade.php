@extends('layouts.admin-layout')

@section('content')
    {{-- <h1 class="text-2xl font-bold mb-6 text-gray-800">Welcome, {{ auth()->user()->first_name }}!</h1> --}}

    <!-- ✅ Scholar Status Overview Title with timestamp only -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-3xl font-bold text-gray-800 mb-1">Scholar Management Overview</h3>
                <p class="text-sm text-gray-600">Summary and statistics for all scholarship applications</p>
            </div>
        </div>

    </div>

    <!-- ✅ 4 Summary Cards with Light Gradient Accent -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8">
        <!-- Total Scholars -->
        <div
            class="relative bg-white/60 backdrop-blur-md p-5 rounded-xl text-center shadow-sm border border-white/30 hover:shadow-md transition-shadow overflow-hidden">
            <div
                class="absolute inset-0 bg-gradient-to-br from-blue-500 to-transparent opacity-60 rounded-xl pointer-events-none">
            </div>
            <div class="relative z-10">
                <div class="flex items-center justify-center mb-2">
                    <svg class="w-6 h-6 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <p class="text-sm text-blue-900 font-semibold mb-1">Total Scholars</p>
                <p class="text-3xl font-bold text-blue-800">{{ $total_scholars }}</p>
            </div>
        </div>

        <!-- Pending -->
        <div
            class="relative bg-white/60 backdrop-blur-md p-5 rounded-xl text-center shadow-sm border border-white/30 hover:shadow-md transition-shadow overflow-hidden">
            <div
                class="absolute inset-0 bg-gradient-to-br from-yellow-500 to-transparent opacity-60 rounded-xl pointer-events-none">
            </div>
            <div class="relative z-10">
                <div class="flex items-center justify-center mb-2">
                    <svg class="w-6 h-6 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="text-sm text-yellow-900 font-semibold mb-1">Pending</p>
                <p class="text-3xl font-bold text-yellow-800">{{ $pending }}</p>
            </div>
        </div>

        <!-- Approved -->
        <div
            class="relative bg-white/60 backdrop-blur-md p-5 rounded-xl text-center shadow-sm border border-white/30 hover:shadow-md transition-shadow overflow-hidden">
            <div
                class="absolute inset-0 bg-gradient-to-br from-green-500 to-transparent opacity-60 rounded-xl pointer-events-none">
            </div>
            <div class="relative z-10">
                <div class="flex items-center justify-center mb-2">
                    <svg class="w-6 h-6 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="text-sm text-green-900 font-semibold mb-1">Approved</p>
                <p class="text-3xl font-bold text-green-800">{{ $approved }}</p>
            </div>
        </div>

        <!-- Rejected -->
        <div
            class="relative bg-white/60 backdrop-blur-md p-5 rounded-xl text-center shadow-sm border border-white/30 hover:shadow-md transition-shadow overflow-hidden">
            <div
                class="absolute inset-0 bg-gradient-to-br from-red-500 to-transparent opacity-60 rounded-xl pointer-events-none">
            </div>
            <div class="relative z-10">
                <div class="flex items-center justify-center mb-2">
                    <svg class="w-6 h-6 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="text-sm text-red-900 font-semibold mb-1">Rejected</p>
                <p class="text-3xl font-bold text-red-800">{{ $rejected }}</p>
            </div>
        </div>
    </div>

    <hr class="my-6 border-t border-gray-200/40">

    <!-- ✅ Two Column Layout: Recent Applications (Left) & Pie Chart (Right) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

        <!-- Left Column: Recent Applications Table (2/3 width) -->
        <div class="lg:col-span-2 bg-white/40 backdrop-blur-md border border-white/30 shadow-md rounded-xl p-6">
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-800">Recent Activities</h3>
                </div>
                <div class="flex items-center gap-2">
                    <form method="GET" action="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
                        <input type="text" name="search" placeholder="Search applications..."
                            value="{{ $search ?? '' }}"
                            class="px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white/50 backdrop-blur-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <button type="submit"
                            class="px-4 py-2 text-sm bg-blue-900 text-white rounded-lg hover:bg-blue-500 transition-colors">
                            Search
                        </button>
                    </form>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100 text-gray-700">
                            <th class="p-4 border-b border-gray-200 font-semibold">Name</th>
                            <th class="p-4 border-b border-gray-200 font-semibold">Program</th>
                            <th class="p-4 border-b border-gray-200 font-semibold">Status</th>
                            <th class="p-4 border-b border-gray-200 font-semibold">Date</th>
                            <th class="p-4 border-b border-gray-200 font-semibold">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white/30 text-gray-800">
                        @foreach ($combined_recent as $item)
                            <tr class="hover:bg-white/50 transition-colors border-b border-gray-100">
                                <td class="p-4 font-medium">
                                    @if (isset($item->program))
                                        {{-- DOST Application: uses user relationship --}}
                                        {{ $item->user->first_name ?? '' }} {{ $item->user->last_name ?? '' }}
                                    @else
                                        {{-- CHED Scholar: has its own name fields in ched_info_table --}}
                                        {{ $item->first_name ?? '' }} {{ $item->last_name ?? '' }}
                                    @endif
                                </td>
                                <td class="p-4">
                                    @if (isset($item->program))
                                        {{-- DOST Application --}}
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                                            DOST
                                        </span>
                                    @else
                                        {{-- CHED Scholar --}}
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-700">
                                            CHED
                                        </span>
                                    @endif
                                </td>
                                <td class="p-4">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                    @if ($item->status === 'approved') bg-green-100 text-green-700 border border-green-400
                    @elseif ($item->status === 'rejected') bg-red-100 text-red-700 border border-red-400
                    @elseif ($item->status === 'pending') bg-yellow-100 text-yellow-700 border border-yellow-400
                    @elseif ($item->status === 'document_verification') bg-blue-100 text-blue-700 border border-blue-400
                    @else bg-gray-100 text-gray-800 border border-gray-200 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                                    </span>
                                </td>
                                <td class="p-4 text-gray-600">{{ $item->created_at->format('M d, Y') }}</td>
                                <td class="p-4">
                                    @if (isset($item->program))
                                        {{-- DOST Link --}}
                                        @if ($item->status === 'approved' && $item->scholar)
                                            <a href="{{ route('admin.scholars.show', $item->scholar->id) }}"
                                                class="inline-flex items-center px-3 py-1 font-semibold text-sm text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-md transition-colors">
                                                View
                                            </a>
                                        @else
                                            <a href="{{ route('admin.applications.show', $item->application_form_id) }}"
                                                class="inline-flex items-center px-3 py-1 font-semibold text-sm text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-md transition-colors">
                                                View
                                            </a>
                                        @endif
                                    @else
                                        {{-- CHED Link --}}
                                        <a href="{{ route('admin.ched.show', $item->id) }}"
                                            class="inline-flex items-center px-3 py-1 font-semibold text-sm text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-md transition-colors">
                                            View
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ✅ Right Column: Pie Chart & Legend (1/3 width, clean design) -->
        <div class="bg-white/30 backdrop-blur-md border border-white/30 shadow-lg rounded-2xl p-6 ring-1 ring-white/20">

            <h4 class="text-lg font-bold text-gray-800 mb-4 text-center">Scholars Status</h4>

            <!-- 📊 Doughnut Chart -->
            <div class="flex justify-center mb-5">
                <div class="w-52 h-52">
                    <canvas id="scholarPieChart"></canvas>
                </div>
            </div>

            <!-- 🟢 Legend -->
            <ul class="space-y-2 text-sm text-gray-700 bg-white/20 backdrop-blur-sm rounded-lg p-4 border border-white/10">
                @php
                    $colors = [
                        '#4CAF50',
                        '#FF9800',
                        '#03A9F4',
                        '#9C27B0',
                        '#F44336',
                        '#FFC107',
                        '#00BCD4',
                        '#607D8B',
                        '#795548',
                        '#E91E63',
                        '#8BC34A',
                        '#2196F3',
                        '#CDDC39',
                        '#009688',
                        '#673AB7',
                    ];
                    $i = 0;
                @endphp

                @foreach ($scholarStatuses as $label => $count)
                    <li class="flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2">
                            <span class="inline-block w-3 h-3 rounded-full"
                                style="background-color: {{ $colors[$i++] }};"></span>
                            <span class="text-xs font-medium">{{ $label }}</span>
                        </div>
                        <span class="text-xs font-semibold text-gray-800">{{ $count }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    @endsection

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const ctx = document.getElementById('scholarPieChart').getContext('2d');
            const data = {
                labels: {!! json_encode(array_values($scholarStatuses->keys()->toArray())) !!},
                datasets: [{
                    data: {!! json_encode($scholarStatuses->values()) !!},
                    backgroundColor: [
                        '#4CAF50', '#FF9800', '#03A9F4', '#9C27B0',
                        '#F44336', '#FFC107', '#00BCD4', '#607D8B'
                    ],
                    borderWidth: 2,
                    borderColor: 'rgba(255, 255, 255, 0.8)',
                    hoverBorderWidth: 3,
                }]
            };

            const chart = new Chart(ctx, {
                type: 'pie',
                data: data,
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: 'white',
                            bodyColor: 'white',
                            borderColor: 'rgba(255, 255, 255, 0.3)',
                            borderWidth: 1,
                            cornerRadius: 8,
                            callbacks: {
                                label: function(context) {
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((context.parsed / total) * 100).toFixed(1);
                                    return `${context.label}: ${context.parsed} (${percentage}%)`;
                                }
                            }
                        }
                    },
                    onClick: (e, elements) => {
                        if (elements.length > 0) {
                            const label = data.labels[elements[0].index];
                            window.location.href = "{{ route('admin.reports.monitoring') }}";
                        }
                    }
                }
            });
        </script>
    @endpush
