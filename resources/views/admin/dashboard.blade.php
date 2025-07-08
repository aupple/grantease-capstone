@extends('layouts.admin-layout')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Welcome, {{ auth()->user()->first_name }}!</h1>

    <!-- ✅ Scholar Status & Application Summary Overview -->
    <div class="bg-white p-6 rounded shadow mt-6">
        <h3 class="text-lg font-bold mb-4">📊 Scholar Status Overview</h3>

        <div class="flex flex-col lg:flex-row gap-8 items-start">
            <!-- 🔹 LEFT SIDE: Application Summary Cards -->
            <div class="flex-1">
                <div class="grid grid-cols-2 md:grid-cols-2 gap-4 mt-12 mb-4">
                    <div class="bg-blue-100 p-4 rounded text-center shadow">
                        <p class="text-sm text-blue-700 font-semibold">Total Applicants</p>
                        <p class="text-xl font-bold">{{ $total_applicants }}</p>
                    </div>
                    <div class="bg-yellow-100 p-4 rounded text-center shadow">
                        <p class="text-sm text-yellow-700 font-semibold">Pending</p>
                        <p class="text-xl font-bold">{{ $pending }}</p>
                    </div>
                    <div class="bg-green-100 p-4 rounded text-center shadow">
                        <p class="text-sm text-green-700 font-semibold">Approved</p>
                        <p class="text-xl font-bold">{{ $approved }}</p>
                    </div>
                    <div class="bg-red-100 p-4 rounded text-center shadow">
                        <p class="text-sm text-red-700 font-semibold">Rejected</p>
                        <p class="text-xl font-bold">{{ $rejected }}</p>
                    </div>
                </div>
            </div>

            <!-- 🔹 RIGHT SIDE: Pie Chart + Vertical Legend -->
            <div class="flex-1 flex flex-col lg:flex-row items-center gap-6">
                <!-- Chart -->
                <div class="w-full max-w-xs">
                    <canvas id="scholarPieChart"></canvas>
                </div>

                <!-- Legend -->
                <div class="w-full max-w-xs">
                    <ul class="space-y-2 text-sm">
                        @php
                            $colors = ['#4CAF50', '#FF9800', '#03A9F4', '#9C27B0', '#F44336', '#FFC107', '#00BCD4', '#607D8B'];
                            $i = 0;
                            $statusMap = [
                                'graduated_ext'    => ['label' => 'Graduated w/ Extension', 'code' => 'GE'],
                                'good_standing'    => ['label' => 'Good Standing', 'code' => 'GS'],
                                'on_extension'     => ['label' => 'On Extension', 'code' => 'Extension'],
                                'leave_of_absence' => ['label' => 'Leave of Absence', 'code' => 'LOA'],
                                'non_compliance'   => ['label' => 'Non-Compliance', 'code' => 'NC'],
                                'no_report'        => ['label' => 'No Report', 'code' => 'NR'],
                                'withdrawn'        => ['label' => '↩Withdrawn', 'code' => 'WD'],
                                'terminated'       => ['label' => 'Terminated', 'code' => 'TERM'],
                            ];
                        @endphp

                        @foreach ($scholarStatuses as $status => $count)
                            @php
                                $info = $statusMap[$status] ?? ['label' => ucfirst(str_replace('_', ' ', $status)), 'code' => strtoupper(substr($status, 0, 3))];
                            @endphp
                            <li class="flex items-center gap-2">
                                <span class="inline-block w-4 h-4 rounded" style="background-color: {{ $colors[$i++] }};"></span>
                                <span>{{ $info['label'] }} <span class="text-gray-500">({{ $info['code'] }})</span> – {{ $count }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- ✅ Recent Applications Table -->
    <div class="bg-white shadow rounded p-6 mt-6">
        <h3 class="text-lg font-semibold mb-4">📄 Recent Applications</h3>

        <table class="w-full text-sm text-left border border-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 border">Applicant</th>
                    <th class="p-2 border">Program</th>
                    <th class="p-2 border">Status</th>
                    <th class="p-2 border">Date</th>
                    <th class="p-2 border">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach (\App\Models\ApplicationForm::with('user')->latest()->take(5)->get() as $app)
                    <tr class="hover:bg-gray-50">
                        <td class="p-2 border">
                            {{ $app->user->full_name ?? $app->user->first_name . ' ' . $app->user->last_name }}
                        </td>
                        <td class="p-2 border">{{ $app->program }}</td>
                        <td class="p-2 border">
                            <span class="px-2 py-1 rounded text-xs font-semibold
                                @if ($app->status === 'approved') bg-green-100 text-green-800
                                @elseif ($app->status === 'rejected') bg-red-100 text-red-800
                                @elseif ($app->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif ($app->status === 'document_verification') bg-purple-100 text-purple-800
                                @elseif ($app->status === 'for_interview') bg-blue-100 text-blue-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst(str_replace('_', ' ', $app->status)) }}
                            </span>
                        </td>
                        <td class="p-2 border">{{ $app->created_at->format('M d, Y') }}</td>
                        <td class="p-2 border">
                            <a href="{{ route('admin.applications.show', $app->application_form_id) }}"
                               class="text-blue-600 hover:underline">
                                View
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('scholarPieChart').getContext('2d');
    const data = {
        labels: {!! json_encode($scholarStatuses->keys()) !!},
        datasets: [{
            data: {!! json_encode($scholarStatuses->values()) !!},
            backgroundColor: [
                '#4CAF50', '#FF9800', '#03A9F4', '#9C27B0',
                '#F44336', '#FFC107', '#00BCD4', '#607D8B'
            ],
        }]
    };

    const chart = new Chart(ctx, {
        type: 'pie',
        data: data,
        options: {
            plugins: {
                legend: { display: false } // ❌ Hides default Chart.js legend
            },
            onClick: (e, elements) => {
                if (elements.length > 0) {
                    const label = data.labels[elements[0].index];
                    window.location.href = `/admin/reports?status=${label}`;
                }
            }
        }
    });
</script>
@endpush
