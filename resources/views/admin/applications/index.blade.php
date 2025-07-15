@extends('layouts.admin-layout')

@section('content')
<div class="mb-6 space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-3xl font-bold text-gray-800 mb-1">Scholarship Applications</h3>
            <p class="text-sm text-gray-600">Review and manage all scholarship applications</p>
        </div>
    </div>

    <!-- 🔍 Search + Filter -->
    <div class="flex flex-wrap gap-4 items-center justify-between bg-white/20 backdrop-blur-xl border border-white/20 shadow-md rounded-xl px-4 py-3">
        <form method="GET" action="{{ route('admin.applications') }}" class="flex items-center gap-2">
            <div class="relative">
                <input type="text"
                    name="search"
                    placeholder="Search applicants..."
                    value="{{ request('search') }}"
                    class="bg-white/10 backdrop-blur-md border border-white/20 text-sm rounded-md px-3 py-2 text-gray-800 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-300 transition" />
            </div>
            @if (request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            <button type="submit"
                class="bg-blue-500/80 backdrop-blur-md text-white px-4 py-2 text-sm rounded-md shadow-md hover:bg-blue-600/80 transition font-semibold">
                Search
            </button>
        </form>

        <form method="GET" action="{{ route('admin.applications') }}" class="flex items-center gap-2">
            @if (request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif
            <label for="status" class="font-semibold text-sm text-gray-700">Filter:</label>
            <select name="status" id="status" onchange="this.form.submit()"
                class="bg-white/10 backdrop-blur-md border border-white/20 rounded-md text-sm px-3 py-2 shadow-sm focus:ring-2 focus:ring-blue-300 focus:outline-none transition text-gray-800">
                <option value="" {{ is_null(request('status')) ? 'selected' : '' }}>All</option>
                @foreach(['pending', 'approved', 'rejected'] as $s)
                    <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>
                        {{ ucfirst(str_replace('_', ' ', $s)) }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>

    <!-- 📋 Applications Table -->
    <div class="bg-white/20 backdrop-blur-xl border border-white/30 shadow-xl rounded-2xl overflow-x-auto transition hover:shadow-2xl">
        <table class="min-w-full text-sm text-gray-800">
            <thead class="bg-white/40 text-gray-700 border-b border-white/30">
                <tr>
                    <th class="p-3 text-left font-semibold">Applicant</th>
                    <th class="p-3 text-left font-semibold">Program</th>
                    <th class="p-3 text-left font-semibold">School</th>
                    <th class="p-3 text-left font-semibold">Year Level</th>
                    <th class="p-3 text-left font-semibold">Status</th>
                    <th class="p-3 text-left font-semibold">Submitted</th>
                    <th class="p-3 text-left font-semibold">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/20">
                @forelse ($applications as $app)
                    <tr class="hover:bg-white/30 hover:backdrop-blur-sm transition duration-200 ease-in-out">
                        <td class="p-3">
                            <div class="bg-white/10 backdrop-blur-md rounded-lg border border-white/10 px-3 py-2 shadow-sm">
                                {{ $app->user->full_name ?? $app->user->first_name . ' ' . $app->user->last_name }}
                            </div>
                        </td>
                        <td class="p-3">
                            <div class="bg-white/10 backdrop-blur-md rounded-lg border border-white/10 px-3 py-2 shadow-sm">
                                {{ $app->program }}
                            </div>
                        </td>
                        <td class="p-3">
                            <div class="bg-white/10 backdrop-blur-md rounded-lg border border-white/10 px-3 py-2 shadow-sm">
                                {{ $app->school }}
                            </div>
                        </td>
                        <td class="p-3">
                            <div class="bg-white/10 backdrop-blur-md rounded-lg border border-white/10 px-3 py-2 shadow-sm">
                                {{ $app->year_level }}
                            </div>
                        </td>
                        <td class="p-3">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold shadow-md backdrop-blur-sm border border-white/30
                                @if ($app->status === 'approved') bg-green-200/60 text-green-900
                                @elseif ($app->status === 'rejected') bg-red-200/60 text-red-900
                                @elseif ($app->status === 'pending') bg-yellow-200/60 text-yellow-900
                                @elseif ($app->status === 'document_verification') bg-purple-200/60 text-purple-900
                                @elseif ($app->status === 'for_interview') bg-blue-200/60 text-blue-900
                                @else bg-gray-200/60 text-gray-900
                                @endif">
                                {{ ucfirst(str_replace('_', ' ', $app->status)) }}
                            </span>
                        </td>
                        <td class="p-3">
                            <div class="bg-white/10 backdrop-blur-md rounded-lg border border-white/10 px-3 py-2 shadow-sm">
                                {{ $app->submitted_at ?? $app->created_at->format('M d, Y') }}
                            </div>
                        </td>
                        <td class="p-3">
                            <div class="bg-white/10 backdrop-blur-md rounded-lg border border-white/10 px-3 py-2 shadow-sm">
                                <a href="{{ route('admin.applications.show', $app->application_form_id) }}"
                                    class="text-blue-600 hover:text-blue-800 text-sm font-semibold transition underline underline-offset-2">
                                    View
                                </a>
                            </div>
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

    <!-- 📄 Pagination -->
    <div class="p-4 bg-white/20 backdrop-blur-xl border border-white/20 shadow-md rounded-xl">
        {{ $applications->withQueryString()->links('pagination::tailwind') }}
    </div>
</div>
@endsection
