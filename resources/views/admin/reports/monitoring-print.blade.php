@extends('layouts.admin-layout')

@section('content')
<style>
@media print {
    @page {
        size: A4 landscape;
        margin: 1cm;
    }
    .no-print {
        display: none !important;
    }
    table {
        border-collapse: collapse;
        width: 100%;
        font-size: 12px;
    }
    th, td {
        border: 1px solid black;
        padding: 4px;
        text-align: center;
    }
}

body {
    font-family: 'Arial', sans-serif;
}
.header-section {
    text-align: left;
    margin-bottom: 10px;
}
.header-section h2 {
    font-size: 16px;
    font-weight: bold;
}
.header-section h3 {
    font-size: 14px;
}
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}
th, td {
    border: 1px solid black;
    padding: 6px;
    text-align: center;
}
</style>

<div class="no-print mb-4 flex justify-between">
    <a href="{{ route('admin.reports.monitoring') }}" class="bg-gray-500 text-white px-4 py-2 rounded">← Back</a>
    <button onclick="window.print()" class="bg-green-600 text-white px-4 py-2 rounded">Print</button>
</div>

<div class="header-section">
    <h2>UNIVERSITY OF SCIENCE AND TECHNOLOGY OF SOUTHERN PHILIPPINES</h2>
    <h3>DETAILED STATUS REPORT OF SCHOLARSHIP PROGRAM SCHOLARS<br>
        AS OF THE END OF {{ strtoupper($schoolTerm) }} TERM AY {{ strtoupper($academicYear) }}
    </h3>
    <p><strong>Scholarship Program:</strong> {{ strtoupper($program) }}</p>
</div>

<table>
    <thead>
        <tr>
            <th>NO.</th>
            <th>LAST NAME</th>
            <th>FIRST NAME</th>
            <th>MIDDLE NAME</th>
            <th>LEVEL<br>(MS / PhD)</th>
            <th>COURSE</th>
            <th>SCHOOL</th>
            <th>NEW / LATERAL</th>
            <th>PART-TIME / FULL-TIME</th>
            <th>SCHOLARSHIP DURATION</th>
            <th>DATE STARTED<br>(Month and Year)</th>
            <th>EXPECTED COMPLETION<br>(Month and Year)</th>
            <th>STATUS</th>
            <th>REMARKS</th>
        </tr>
    </thead>
    <tbody>
        @foreach($scholars as $index => $s)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $s->last_name ?? '' }}</td>
            <td>{{ $s->first_name ?? '' }}</td>
            <td>{{ $s->middle_name ?? '' }}</td>
            <td>{{ $s->level ?? '' }}</td>
            <td>{{ $s->course ?? '' }}</td>
            <td>{{ $s->school ?? '' }}</td>
            <td>{{ $s->new_or_lateral ?? '' }}</td>
            <td>{{ $s->full_or_part_time ?? '' }}</td>
            <td>{{ $s->scholarship_duration ?? '' }}</td>
            <td>{{ $s->date_started ?? '' }}</td>
            <td>{{ $s->expected_completion ?? '' }}</td>
            <td>{{ $s->status ?? '' }}</td>
            <td>{{ $s->remarks ?? '' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
