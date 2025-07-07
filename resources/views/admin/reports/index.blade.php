@extends('layouts.admin-layout')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold mb-4">📊 Reports & Monitoring</h1>

    <!-- 📋 Evaluation Sheet -->
    <div class="bg-white p-6 rounded shadow mb-6">
        <h2 class="text-lg font-semibold mb-2">📋 Evaluation Sheet for Applicants</h2>
        <p class="text-sm text-gray-600 mb-3">This section summarizes evaluation scores per applicant (e.g., academic, financial, interview scores).</p>
        <a href="{{ route('admin.reports.evaluation') }}"
           class="inline-block bg-blue-500 text-white text-sm px-3 py-1 rounded hover:bg-blue-600">View Evaluation Sheet</a>
    </div>

    <!-- 🧑‍💼 List of Applicants -->
    <div class="bg-white p-6 rounded shadow mb-6">
        <h2 class="text-lg font-semibold mb-2">🧑‍💼 List of All Applicants</h2>
        <p class="text-sm text-gray-600 mb-3">Shows all applicants who submitted application forms, including their current status.</p>
        <a href="{{ route('admin.reports.applicants') }}"
           class="inline-block bg-blue-500 text-white text-sm px-3 py-1 rounded hover:bg-blue-600">View Applicant List</a>
    </div>

    <!-- 🎓 Scholar Monitoring -->
    <div class="bg-white p-6 rounded shadow mb-6">
        <h2 class="text-lg font-semibold mb-2">🎓 Monitoring of All Scholars</h2>
        <p class="text-sm text-gray-600 mb-3">View and track current scholars, their statuses, and compliance to requirements.</p>
        <a href="{{ route('admin.reports.scholars') }}"
           class="inline-block bg-blue-500 text-white text-sm px-3 py-1 rounded hover:bg-blue-600">View Scholar Monitoring</a>
    </div>

    <!-- 📝 Scoresheets -->
    <div class="bg-white p-6 rounded shadow mb-6">
        <h2 class="text-lg font-semibold mb-2">📝 Scoresheets for Applicants</h2>
        <p class="text-sm text-gray-600 mb-3">Breakdown of scores per category (e.g., grades, interview, financial standing).</p>
        <a href="{{ route('admin.reports.scoresheets') }}"
           class="inline-block bg-blue-500 text-white text-sm px-3 py-1 rounded hover:bg-blue-600">View Scoresheets</a>
    </div>

    <!-- 📥 PDF Report -->
    <div class="bg-white p-6 rounded shadow">
        <h2 class="text-lg font-semibold mb-2">📥 Export Summary</h2>
        <p class="text-sm text-gray-600 mb-3">Download full report as PDF.</p>
        <a href="{{ route('admin.reports.pdf') }}" target="_blank"
           class="inline-block bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 text-sm">
            📄 Download PDF Report
        </a>
    </div>
</div>
@endsection
