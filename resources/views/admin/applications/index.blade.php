@extends('layouts.admin-layout')

@section('content')
    <div class="mb-6 space-y-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-4">DOST Applicants</h1>

        <!-- 🔍 Filters -->
        <form method="GET" action="{{ route('admin.applications') }}"
            class="flex flex-wrap gap-4 items-center bg-gray-150/80 backdrop-blur-xl border border-gray-100 shadow-md rounded-xl px-4 py-3">

            <!-- Search Bar -->
            <div class="flex-1 min-w-[250px]">
                <label for="search" class="text-sm font-bold text-gray-700">Search:</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}"
                    placeholder="Search Applicant..."
                    class="ml-2 px-3 py-2 border border-white/30 rounded-md bg-white/30 backdrop-blur-md text-sm w-full max-w-md focus:ring-2 focus:ring-blue-400 focus:outline-none placeholder:text-gray-500">
            </div>

            <!-- Academic Year Filter -->
            <div>
                <label for="academic_year" class="text-sm font-semibold text-gray-700">Academic Year:</label>
                <select name="academic_year" id="academic_year"
                    class="ml-2 px-3 py-2 pr-10 border border-white/30 rounded-md text-sm bg-white/30 backdrop-blur-md focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <option value="all" {{ request('academic_year') == 'all' ? 'selected' : '' }}>All</option>
                    <option value="2024-2025" {{ request('academic_year') == '2024-2025' ? 'selected' : '' }}>2024-2025
                    </option>
                    <option value="2025-2026" {{ request('academic_year') == '2025-2026' ? 'selected' : '' }}>2025-2026
                    </option>
                    <option value="2026-2027" {{ request('academic_year') == '2026-2027' ? 'selected' : '' }}>2026-2027
                    </option>
                </select>
            </div>

            <!-- Semester Filter -->
            <div>
                <label for="semester" class="text-sm font-semibold text-gray-700">Semester:</label>
                <select name="semester" id="semester"
                    class="ml-2 px-3 py-2 pr-10 border border-white/30 rounded-md text-sm bg-white/30 backdrop-blur-md focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <option value="all" {{ request('semester') == 'all' ? 'selected' : '' }}>All</option>
                    <option value="First Semester" {{ request('semester') == 'First Semester' ? 'selected' : '' }}>1st
                        Semester</option>
                    <option value="Second Semester" {{ request('semester') == 'Second Semester' ? 'selected' : '' }}>2nd
                        Semester</option>
                </select>
            </div>

            <!-- Apply Button -->
            <button type="submit"
                class="bg-blue-900 backdrop-blur-md text-white px-4 py-2 text-sm rounded-md shadow-md hover:bg-blue-600/80 transition font-semibold">
                Apply
            </button>
        </form>

        <!-- 📋 Applications Table with Glassmorphism -->
        <div
            class="bg-white/20 backdrop-blur-xl border border-white/30 shadow-xl rounded-2xl overflow-x-auto transition hover:shadow-2xl">
            <table class="min-w-full text-sm text-gray-800">
                <thead class="bg-white/40 text-gray-700 border-b border-white/30">
                    <tr>
                        <th class="p-3 text-left font-semibold">#</th>
                        <th class="p-3 text-left font-semibold">Applicant Name</th>
                        <th class="p-3 text-left font-semibold">Email</th>
                        <th class="p-3 text-left font-semibold">Program</th>
                        <th class="p-3 text-left font-semibold">Academic Year</th>
                        <th class="p-3 text-left font-semibold">Semester</th>
                        <th class="p-3 text-left font-semibold">Status</th>
                        <th class="p-3 text-left font-semibold">Applied At</th>
                        <th class="p-3 text-left font-semibold">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/20">
                    @forelse ($applications as $index => $application)
                        <tr class="hover:bg-white/30 hover:backdrop-blur-sm transition duration-200 ease-in-out">
                            <!-- Numbering -->
                            <td class="p-3 text-gray-700 font-medium">
                                {{ $applications->firstItem() + $index }}
                            </td>

                            <!-- Applicant Name -->
                            <td class="p-1">
                                <div
                                    class="bg-white/10 backdrop-blur-md rounded-lg border border-white/10 px-3 py-2 shadow-sm">
                                    @if (isset($application->user) && $application->user)
                                        @if (isset($application->user->full_name))
                                            {{ $application->user->full_name }}
                                        @elseif(isset($application->user->first_name))
                                            {{ $application->user->first_name }} {{ $application->user->last_name ?? '' }}
                                        @else
                                            <span class="text-gray-500 text-xs italic">Name not available</span>
                                        @endif
                                    @else
                                        <span class="text-red-500 text-xs font-semibold">⚠️ No User Linked</span>
                                    @endif
                                </div>
                            </td>

                            <!-- Email -->
                            <td class="p-1">
                                <div
                                    class="bg-white/10 backdrop-blur-md rounded-lg border border-white/10 px-3 py-2 shadow-sm">
                                    {{ $application->user->email ?? 'N/A' }}
                                </div>
                            </td>

                            <!-- Program -->
                            <td class="p-1">
                                <div
                                    class="bg-white/10 backdrop-blur-md rounded-lg border border-white/10 px-3 py-2 shadow-sm">
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                                        DOST
                                    </span>
                                </div>
                            </td>

                            <!-- Academic Year -->
                            <td class="p-1">
                                <div
                                    class="bg-white/10 backdrop-blur-md rounded-lg border border-white/10 px-3 py-2 shadow-sm">
                                    {{ $application->academic_year ?? 'N/A' }}
                                </div>
                            </td>

                            <!-- Semester -->
                            <td class="p-1">
                                <div
                                    class="bg-white/10 backdrop-blur-md rounded-lg border border-white/10 px-3 py-2 shadow-sm">
                                    {{ $application->school_term ?? 'N/A' }}
                                </div>
                            </td>

                            <!-- Status -->
                            <td class="p-1">
                                <div
                                    class="bg-white/10 backdrop-blur-md rounded-lg border border-white/10 px-3 py-2 shadow-sm">
                                    @php
                                        $statusColor = match ($application->status) {
                                            'pending' => 'bg-yellow-100 text-yellow-700',
                                            'document_verification' => 'bg-blue-100 text-blue-700',
                                            'approved' => 'bg-green-100 text-green-700',
                                            'rejected' => 'bg-red-100 text-red-700',
                                            default => 'bg-gray-100 text-gray-700',
                                        };
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusColor }}">
                                        {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                                    </span>
                                </div>
                            </td>

                            <!-- Applied At -->
                            <td class="p-1">
                                <div
                                    class="bg-white/10 backdrop-blur-md rounded-lg border border-white/10 px-3 py-2 shadow-sm">
                                    {{ $application->created_at->format('M d, Y') }}
                                </div>
                            </td>

                            <!-- Action -->
                            <td class="p-1">
                                <div
                                    class="bg-white/10 backdrop-blur-md rounded-lg border border-white/10 px-3 py-2 shadow-sm">
                                    <a href="{{ route('admin.applications.show', $application->application_form_id) }}"
                                        class="text-blue-600 hover:text-blue-800 text-sm font-semibold">
                                        View
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="p-4 text-center text-gray-500">No applications yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- 📄 Pagination with Glassmorphism -->
        @if ($applications->hasPages())
            <div class="p-4 bg-white/20 backdrop-blur-xl border border-white/20 shadow-md rounded-xl">
                {{ $applications->links('pagination::tailwind') }}
            </div>
        @endif
    </div>
@endsection
