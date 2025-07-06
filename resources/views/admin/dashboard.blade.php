@extends('layouts.admin-layout')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Welcome, {{ auth()->user()->first_name }}!</h1>

    <!-- ✅ Application Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-blue-100 p-4 rounded text-center shadow">
            <p class="text-sm text-blue-700">Total Applicants</p>
            <p class="text-xl font-bold">{{ $total_applicants }}</p>
        </div>
        <div class="bg-yellow-100 p-4 rounded text-center shadow">
            <p class="text-sm text-yellow-700">Pending</p>
            <p class="text-xl font-bold">{{ $pending }}</p>
        </div>
        <div class="bg-green-100 p-4 rounded text-center shadow">
            <p class="text-sm text-green-700">Approved</p>
            <p class="text-xl font-bold">{{ $approved }}</p>
        </div>
        <div class="bg-red-100 p-4 rounded text-center shadow">
            <p class="text-sm text-red-700">Rejected</p>
            <p class="text-xl font-bold">{{ $rejected }}</p>
        </div>
    </div>

    <!-- ✅ Recent Applications Table -->
    <div class="bg-white shadow rounded p-6">
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
