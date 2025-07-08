@extends('layouts.admin-layout')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold mb-4">📑 Scholarship Applications</h1>

    <!-- Search + Filter -->
    <div class="flex items-center justify-between mb-4">
        <form method="GET" action="{{ route('admin.applications') }}" class="flex items-center gap-2">
            <div class="relative">
                <input type="text"
                    name="search"
                    placeholder="Search applicants..."
                    value="{{ request('search') }}"
                    class="bg-transparent border-0 border-b border-gray-400 text-sm w-64 px-0 py-1.5
                        focus:outline-none focus:ring-0 focus:border-blue-600 placeholder-gray-500" />
            </div>
            @if (request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            <button type="submit"
                class="text-sm bg-blue-600 text-white px-2 py-1 rounded-md hover:bg-blue-700 transition font-semibold">
                Search
            </button>
        </form>

        <form method="GET" action="{{ route('admin.applications') }}" class="flex items-center space-x-2">
            @if (request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif
            <label for="status" class="font-semibold text-sm text-gray-700">Filter:</label>
            <select name="status" id="status" onchange="this.form.submit()" class="border text-sm px-2 py-1 rounded shadow-sm focus:ring-blue-200 focus:outline-none">
                <option value="" {{ is_null(request('status')) ? 'selected' : '' }}>All</option>
                @foreach(['pending', 'document_verification', 'for_interview', 'approved', 'rejected'] as $s)
                    <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>
                        {{ ucfirst(str_replace('_', ' ', $s)) }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>

    <!-- Applications Table (NO export/select) -->
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
                               class="text-blue-600 hover:underline text-sm font-semibold">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-4 text-center text-gray-500">No applications found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="p-4">
        {{ $applications->withQueryString()->links('pagination::tailwind') }}
    </div>
</div>
@endsection
