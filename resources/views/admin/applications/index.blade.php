@extends('layouts.admin')

@section('title', 'Submitted Applications')
@section('header-title', 'Submitted Applications')

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
