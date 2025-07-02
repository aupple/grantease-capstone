@extends('layouts.admin')

<<<<<<< HEAD
@section('title', 'Submitted Applications')
@section('header-title', 'Submitted Applications')
=======
    <!-- ✅ Filter Buttons -->
    <div style="margin-bottom: 20px;">
        <strong>Filter by Status:</strong>
        <a href="{{ route('admin.applications') }}">All</a> |
        <a href="{{ route('admin.applications', ['status' => 'pending']) }}">Pending</a> |
        <a href="{{ route('admin.applications', ['status' => 'approved']) }}">Approved</a> |
        <a href="{{ route('admin.applications', ['status' => 'rejected']) }}">Rejected</a>
    </div>

    <!-- ✅ Current Filter -->
    @if (isset($status))
        <p><strong>Showing:</strong> {{ ucfirst($status) }} Applications</p>
    @endif

    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th>Applicant Name</th>
                <th>Program</th>
                <th>School</th>
                <th>Year Level</th>
                <th>Status</th>
                <th>Submitted</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($applications as $app)
                <tr>
                    <td>{{ $app->user->full_name }}</td>
                    <td>{{ $app->program }}</td>
                    <td>{{ $app->school }}</td>
                    <td>{{ $app->year_level }}</td>
                    <td>{{ ucfirst($app->status ?? 'pending') }}</td>
                    <td>{{ $app->submitted_at ?? $app->created_at }}</td>
                    <td>
                        <a href="{{ route('admin.applications.show', $app->application_form_id) }}">
                            View
                        </a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7">No applications submitted yet.</td></tr>
            @endforelse
        </tbody>
    </table>
>>>>>>> d65b1dac2147ef244807d26b5537693ed11f0791

@section('content')
    <div class="bg-white p-6 rounded shadow">
        <h2 class="text-2xl font-bold text-blue-800 mb-4">📑 Submitted Applications</h2>

        <table border="1" cellpadding="10" class="w-full text-sm text-left border border-gray-300">
            <thead class="bg-blue-100 text-blue-900">
                <tr>
                    <th>Applicant Name</th>
                    <th>Program</th>
                    <th>School</th>
                    <th>Year Level</th>
                    <th>Status</th>
                    <th>Submitted</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($applications as $app)
                    <tr class="hover:bg-gray-50">
                        <td>{{ $app->user->name }}</td>
                        <td>{{ $app->program }}</td>
                        <td>{{ $app->school }}</td>
                        <td>{{ $app->year_level }}</td>
                        <td>{{ ucfirst($app->status ?? 'pending') }}</td>
                        <td>{{ $app->submitted_at ?? $app->created_at }}</td>
                        <td>
                            <a href="{{ route('admin.applications.show', $app->application_form_id) }}"
                               class="text-blue-600 hover:underline">
                                View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-gray-600 py-4">No applications submitted yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
