@extends('layouts.admin-layout')

@section('content')
<style>
    @media print {
        @page {
            size: A4 landscape;
            margin: 1cm;
        }

        body {
            font-size: 12px;
        }

        .no-print {
            display: none;
        }

        .print-page-break {
            page-break-before: always;
        }

        table {
            border-collapse: collapse !important;
            width: 100%;
        }

        th, td {
            border: 1px solid black !important;
            padding: 4px !important;
        }
    }
</style>

<div class="mb-10">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">📊 Scholar Monitoring Report</h1>
    </div>
    {{-- ========================= --}}
   {{-- 1. SCHOLAR LIST (From Scholars Table) --}}
    {{-- ========================= --}}
    <h2 class="text-2xl font-bold mb-4">Individual Scholar List</h2>
<div class="overflow-x-auto bg-white shadow rounded-lg p-4 mb-10">
    <table class="min-w-full text-sm text-left border border-gray-300">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-3 py-2 border">No.</th>
                <th class="px-3 py-2 border">Last Name</th>
                <th class="px-3 py-2 border">First Name</th>
                <th class="px-3 py-2 border">Middle Name</th>
                <th class="px-3 py-2 border">Level</th>
                <th class="px-3 py-2 border">Course</th>
                <th class="px-3 py-2 border">School</th>
                <th class="px-3 py-2 border">New / Lateral</th>
                <th class="px-3 py-2 border">Part-Time / Full-Time</th>
                <th class="px-3 py-2 border">Scholarship Duration</th>
                <th class="px-3 py-2 border">Date Started</th>
                <th class="px-3 py-2 border">Expected Completion</th>
                <th class="px-3 py-2 border">Status</th>
                <th class="px-3 py-2 border">Remarks</th>
            </tr>
        </thead>
        <tbody>
            @foreach($scholars as $index => $scholar)
                <tr class="hover:bg-gray-50">
                    <td class="px-3 py-2 border">{{ $index+1 }}</td>
                    <td class="px-3 py-2 border">{{ $scholar->last_name }}</td>
                    <td class="px-3 py-2 border">{{ $scholar->first_name }}</td>
                    <td class="px-3 py-2 border">{{ $scholar->middle_name }}</td>
                    <td class="px-3 py-2 border">{{ $scholar->level }}</td>
                    <td class="px-3 py-2 border">{{ $scholar->course }}</td>
                    <td class="px-3 py-2 border">{{ $scholar->school }}</td>
                    <td class="px-3 py-2 border">{{ $scholar->new_or_lateral }}</td>
                    <td class="px-3 py-2 border">{{ $scholar->full_or_part_time }}</td>
                    <td class="px-3 py-2 border">{{ $scholar->scholarship_duration }}</td>
                    <td class="px-3 py-2 border">{{ $scholar->date_started }}</td>
                    <td class="px-3 py-2 border">{{ $scholar->expected_completion }}</td>
                    <td class="px-3 py-2 border">{{ $scholar->status_code }}</td>
                    <td class="px-3 py-2 border">{{ $scholar->remarks }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
    {{-- ========================= --}}
    {{-- 2. NEW EXCEL-LIKE TABLE (Client Expected) --}}
    {{-- ========================= --}}
    <form action="{{ route('admin.reports.monitoring.save') }}" method="POST">
        @csrf

        <div class="overflow-x-auto bg-white rounded-xl shadow-md border border-gray-200 mb-10">
            <table class="min-w-full text-xs text-center border-collapse">
                <thead class="text-xs text-center font-semibold text-gray-900">
                    <tr>
                        <th rowspan="2" class="bg-white border px-2 py-1 align-middle">Year of Award</th>
                        <th rowspan="2" class="bg-white border px-2 py-1">Qualifiers<br><span class="text-red-600">(1)</span></th>
                        <th rowspan="2" class="bg-white border px-2 py-1">Not Availing<br><span class="text-red-600">(2)</span></th>
                        <th rowspan="2" class="bg-white border px-2 py-1">Deferred<br><span class="text-red-600">(3)</span></th>

                        <th colspan="2" class="bg-green-100 border px-2 py-1">Graduated</th>
                        <th colspan="3" class="bg-rose-100 border px-2 py-1">On Extension</th>
                        <th colspan="4" class="bg-sky-100 border px-2 py-1">Ongoing</th>

                        <th rowspan="2" class="bg-white border px-2 py-1">Non-Compliance<br><span class="text-red-600">(7)</span></th>
                        <th rowspan="2" class="bg-white border px-2 py-1">Terminated<br><span class="text-red-600">(8)</span></th>
                        <th rowspan="2" class="bg-white border px-2 py-1">Withdrew<br><span class="text-red-600">(9)</span></th>
                        <th rowspan="2" class="bg-white border px-2 py-1">DR<br><span class="text-red-600">(10)</span></th>
                    </tr>
                    <tr>
                        <th class="bg-green-100 border px-2 py-1">On Time<br><span class="text-red-600">(4a)</span></th>
                        <th class="bg-green-100 border px-2 py-1">with Extension<br><span class="text-red-600">(4b)</span></th>

                        <th class="bg-rose-100 border px-2 py-1">Complete FA<br><span class="text-red-600">(5a)</span></th>
                        <th class="bg-rose-100 border px-2 py-1">With FA<br><span class="text-red-600">(5b)</span></th>
                        <th class="bg-rose-100 border px-2 py-1">FM<br><span class="text-red-600">(5c)</span></th>

                        <th class="bg-sky-100 border px-2 py-1">GS-On Track<br><span class="text-red-600">(6a)</span></th>
                        <th class="bg-sky-100 border px-2 py-1">LOA<br><span class="text-red-600">(6b)</span></th>
                        <th class="bg-sky-100 border px-2 py-1">Suspended<br><span class="text-red-600">(6c)</span></th>
                        <th class="bg-sky-100 border px-2 py-1">No Report<br><span class="text-red-600">(6d)</span></th>
                    </tr>
                </thead>

                <tbody>
                    @php
                        $degrees = ['Bachelors', 'Masters', 'Doctorate'];
                        $years = range(2023, now()->year);
                        $statusCodes = [1, 2, 3, '4a', '4b', '5a', '5b', '5c', '6a', '6b', '6c', '6d', 7, 8, 9, 10];
                    @endphp

                    @foreach ($degrees as $degree)
                        @php
                            $records = $grouped[$degree] ?? collect();
                        @endphp

                        <tr class="bg-blue-50 text-left font-bold">
                            <td colspan="17" class="text-left px-2 py-1 uppercase">{{ $degree }} Degree</td>
                        </tr>

                        @foreach ($years as $year)
                            <tr>
                                <td>{{ $year }}</td>
                                @foreach ($statusCodes as $code)
                                    <td>
                                        <input type="number"
                                            name="data[{{ $degree }}][{{ $year }}][{{ $code }}]"
                                            class="w-12 text-center border border-gray-400 rounded print:border-none px-1 py-1 text-sm"
                                            value="{{ $records->where('year_awarded', $year)->where('status_code', $code)->first()?->total ?? 0 }}">
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach

                        <tr class="font-bold bg-gray-50">
                            <td>Total</td>
                            @foreach ($statusCodes as $code)
                                <td>
                                    {{ $records->where('status_code', $code)->sum('total') }}
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6 flex justify-between items-center no-print mb-10">
            <a href="{{ route('admin.reports.monitoring.download') }}"
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow text-sm font-semibold">
               Download PDF
            </a>

            <button type="submit"
                class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded shadow text-sm font-semibold">
                Save Changes
            </button>
        </div>
    </form>
@endsection
