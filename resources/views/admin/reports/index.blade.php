@extends('layouts.admin-layout')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold mb-4">📊 Application Report Summary</h1>

    <div class="bg-white p-6 rounded shadow max-w-md">
        <ul class="space-y-2 text-sm">
            <li>
                <strong>Total Applicants:</strong>
                <span class="ml-2">{{ $total_applicants }}</span>
            </li>
            <li>
                <strong>Approved:</strong>
                <span class="ml-2 text-green-700">{{ $approved }}</span>
            </li>
            <li>
                <strong>Rejected:</strong>
                <span class="ml-2 text-red-700">{{ $rejected }}</span>
            </li>
            <li>
                <strong>Pending:</strong>
                <span class="ml-2 text-yellow-700">{{ $pending }}</span>
            </li>
        </ul>

        <!-- 📥 PDF Download Button -->
        <div class="mt-6">
            <a href="{{ route('admin.reports.pdf') }}" target="_blank"
               class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">
                📥 Download PDF Report
            </a>
        </div>
    </div>

</div>
@endsection
