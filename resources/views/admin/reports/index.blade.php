@extends('layouts.admin-layout')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold mb-6">📊 Reports & Monitoring</h1>

    <!-- Report Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        @php
            $reportCards = [
                [
                    'title' => '📋 Evaluation Sheet for Applicants',
                    'desc' => 'Summarizes academic, financial, and interview scores.',
                    'route' => 'admin.reports.evaluation'
                ],
                [
                    'title' => '🧑‍💼 List of All Applicants',
                    'desc' => 'Shows all applicants and their current status.',
                    'route' => 'admin.reports.applicants'
                ],
                [
                    'title' => '🎓 Monitoring of All Scholars',
                    'desc' => 'Track scholars, their statuses, and compliance.',
                    'route' => 'admin.reports.scholars'
                ],
                [
                    'title' => '📝 Scoresheets for Applicants',
                    'desc' => 'Breakdown of scores (grades, interview, financials).',
                    'route' => 'admin.reports.scoresheets'
                ]
            ];
        @endphp

        @foreach ($reportCards as $card)
        <div class="bg-white p-6 rounded shadow">
            <h2 class="text-lg font-semibold mb-2">{{ $card['title'] }}</h2>
            <p class="text-sm text-gray-600 mb-3">{{ $card['desc'] }}</p>
            <a href="{{ route($card['route']) }}"
               class="inline-block bg-blue-500 text-white text-sm px-4 py-2 rounded hover:bg-blue-600">Open</a>
        </div>
        @endforeach
    </div>

    <!-- 📥 Export Selected Records -->
    <div class="bg-white p-6 rounded shadow mb-6">
        <h2 class="text-lg font-semibold mb-4">📥 Export Scholar or Applicant Records</h2>
        <form action="{{ route('admin.reports.export-selected') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block mb-1 text-sm font-medium text-gray-700">Filter by Type:</label>
                <select name="type" class="w-full border rounded p-2" onchange="this.form.submit()">
                    <option value="applicant" {{ $type === 'applicant' ? 'selected' : '' }}>Applicants</option>
                    <option value="scholar" {{ $type === 'scholar' ? 'selected' : '' }}>Scholars</option>
                </select>
            </div>

            <div class="overflow-x-auto border rounded">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-2 border"><input type="checkbox" onclick="toggleAll(this)"> Select</th>
                            <th class="p-2 border">Name</th>
                            <th class="p-2 border">Email</th>
                            <th class="p-2 border">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($records as $record)
                        <tr class="hover:bg-gray-50">
                            <td class="p-2 border">
                                <input type="checkbox" name="selected[]" value="{{ $record->id }}">
                            </td>
                            <td class="p-2 border">
                                {{ $record->user->full_name ?? ($record->user?->first_name . ' ' . $record->user?->last_name) ?? 'N/A' }}
                            </td>
                            <td class="p-2 border">{{ $record->user->email ?? 'N/A' }}</td>
                            <td class="p-2 border">
                                <span class="px-2 py-1 rounded text-xs font-semibold capitalize
                                    @if ($record->status === 'approved') bg-green-100 text-green-800
                                    @elseif ($record->status === 'rejected') bg-red-100 text-red-800
                                    @elseif ($record->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif ($record->status === 'document_verification') bg-purple-100 text-purple-800
                                    @elseif ($record->status === 'for_interview') bg-blue-100 text-blue-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ str_replace('_', ' ', $record->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center p-4 text-gray-500">No {{ $type }} records available.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                <button type="submit"
                        class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 text-sm">
                    📄 Download Selected Records
                </button>
            </div>
        </form>
    </div>

    <!-- PDF Full Export -->
    <div class="bg-white p-6 rounded shadow">
        <h2 class="text-lg font-semibold mb-2">📄 Export Entire Summary</h2>
        <p class="text-sm text-gray-600 mb-3">Download the full applicant & scholar summary as a PDF.</p>
        <a href="{{ route('admin.reports.pdf') }}" target="_blank"
           class="inline-block bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 text-sm">
            📥 Download Full Report (PDF)
        </a>
    </div>
</div>

<!-- Toggle all checkbox script -->
<script>
    function toggleAll(source) {
        const checkboxes = document.querySelectorAll('input[name="selected[]"]');
        for (const box of checkboxes) {
            box.checked = source.checked;
        }
    }
</script>
@endsection
