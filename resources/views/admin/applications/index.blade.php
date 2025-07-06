@extends('layouts.admin-layout')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold mb-4">📑 Submitted Applications</h1>

    <!-- ✅ Filters -->
    <div class="flex items-center justify-between mb-4">
        <div class="space-x-2">
            <a href="{{ route('admin.applications') }}"
               class="px-3 py-1 rounded border text-sm {{ is_null($status) ? 'bg-blue-600 text-white' : 'bg-white text-gray-800' }}">
               All
            </a>
            @foreach(['pending', 'document_verification', 'for_interview', 'approved', 'rejected'] as $s)
                <a href="{{ route('admin.applications', ['status' => $s]) }}"
                   class="px-3 py-1 rounded border text-sm {{ $status === $s ? 'bg-blue-600 text-white' : 'bg-white text-gray-800' }}">
                    {{ ucfirst(str_replace('_', ' ', $s)) }}
                </a>
            @endforeach
        </div>

        <!-- 🔍 Search -->
        <form method="GET" action="{{ route('admin.applications') }}" class="flex items-center space-x-2">
            <input type="text" name="search" placeholder="Search..." value="{{ request('search') }}"
                   class="border rounded px-3 py-1 text-sm">
            @if (request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            <button type="submit"
                    class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700">Search</button>
        </form>
    </div>

    <!-- ✅ Applications Table -->
    <div class="bg-white shadow rounded overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 text-left">Applicant</th>
                    <th class="p-2 text-left">Program</th>
                    <th class="p-2 text-left">School</th>
                    <th class="p-2 text-left">Year Level</th>
                    <th class="p-2 text-left">Status</th>
                    <th class="p-2 text-left">Submitted</th>
                    <th class="p-2 text-left">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($applications as $app)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-2">{{ $app->user->full_name ?? $app->user->first_name . ' ' . $app->user->last_name }}</td>
                        <td class="p-2">{{ $app->program }}</td>
                        <td class="p-2">{{ $app->school }}</td>
                        <td class="p-2">{{ $app->year_level }}</td>
                        <td class="p-2">
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
                        <td class="p-2">{{ $app->submitted_at ?? $app->created_at->format('M d, Y') }}</td>
                        <td class="p-2">
                            <a href="{{ route('admin.applications.show', $app->application_form_id) }}"
                               class="text-blue-600 hover:underline text-sm">View</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="p-4 text-center text-gray-500">No applications found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
