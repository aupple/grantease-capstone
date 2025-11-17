@extends('layouts.admin-layout')

@section('content')
    <div class="mb-6 space-y-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-4"> DOST & CHED Scholars</h1>

        <!-- 🔍 Filters -->
        <form method="GET" action="{{ route('admin.scholars') }}"
            class="flex flex-wrap gap-4 items-center bg-gray-150/80 backdrop-blur-xl border border-gray-100 shadow-md rounded-xl px-4 py-3">

            <!-- Search Bar -->
            <div class="flex-1 min-w-[250px]">
                <label for="search" class="text-sm font-bold text-gray-700">Search:</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}"
                    placeholder="Search Scholar..."
                    class="ml-2 px-3 py-2 border border-white/30 rounded-md bg-white/30 backdrop-blur-md text-sm w-full max-w-md focus:ring-2 focus:ring-blue-400 focus:outline-none placeholder:text-gray-500">
            </div>

            <!-- Program Filter -->
            <div>
                <label for="program" class="text-sm font-semibold text-gray-700">Program:</label>
                <select name="program" id="program"
                    class="ml-2 px-3 py-2 pr-10 border border-white/30 rounded-md text-sm bg-white/30 backdrop-blur-md focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <option value="all" {{ $program == 'all' ? 'selected' : '' }}>All</option>
                    <option value="DOST" {{ $program == 'DOST' ? 'selected' : '' }}>DOST</option>
                    <option value="CHED" {{ $program == 'CHED' ? 'selected' : '' }}>CHED</option>
                </select>
            </div>

            <!-- Semester Filter -->
            <div>
                <label for="semester" class="text-sm font-semibold text-gray-700">Semester:</label>
                <select name="semester" id="semester"
                    class="ml-2 px-3 py-2 pr-10 border border-white/30 rounded-md text-sm bg-white/30 backdrop-blur-md focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <option value="all" {{ $semester == 'all' ? 'selected' : '' }}>All</option>
                    <option value="First" {{ $semester == 'First' ? 'selected' : '' }}>1st Semester</option>
                    <option value="Second" {{ $semester == 'Second' ? 'selected' : '' }}>2nd Semester</option>
                </select>
            </div>

            <!-- Apply Button -->
            <button type="submit"
                class="bg-blue-900 backdrop-blur-md text-white px-4 py-2 text-sm rounded-md shadow-md hover:bg-blue-600/80 transition font-semibold">
                Apply
            </button>
        </form>

        <!-- 📋 Scholars Table with Glassmorphism -->
        <div
            class="bg-white/20 backdrop-blur-xl border border-white/30 shadow-xl rounded-2xl overflow-x-auto transition hover:shadow-2xl">
            <table class="min-w-full text-sm text-gray-800">
                <thead class="bg-white/40 text-gray-700 border-b border-white/30">
                    <tr>
                        <th class="p-3 text-left font-semibold">#</th>
                        <th class="p-3 text-left font-semibold">Scholar Name</th>
                        <th class="p-3 text-left font-semibold">Scholarship Program</th>
                        <th class="p-3 text-left font-semibold">Level</th>
                        <th class="p-3 text-left font-semibold">School</th>
                        <th class="p-3 text-left font-semibold">Semester</th>
                        <th class="p-3 text-left font-semibold">Scholar Status</th>
                        <th class="p-3 text-left font-semibold">Approved At</th>
                        <th class="p-3 text-left font-semibold">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/20">
                    @forelse ($scholars as $index => $scholar)
                        <tr class="hover:bg-white/30 hover:backdrop-blur-sm transition duration-200 ease-in-out">
                            <!-- Numbering -->
                            <td class="p-3 text-gray-700 font-medium">
                                {{ $scholars->firstItem() + $index }}
                            </td>

                            <!-- Applicant Name -->
                            <td class="p-1">
                                <div
                                    class="bg-white/10 backdrop-blur-md rounded-lg border border-white/10 px-3 py-2 shadow-sm">
                                    {{ $scholar->user->full_name ?? $scholar->user->first_name . ' ' . $scholar->user->last_name }}
                                </div>
                            </td>
                            <!-- Scholarship Type -->
                            <td class="p-1">
                                <div
                                    class="bg-white/10 backdrop-blur-md rounded-lg border border-white/10 px-3 py-2 shadow-sm">
                                    {{ $scholar->applicationForm->program ?? 'N/A' }}
                                </div>
                            </td>

                            <!-- Level -->
                            <td class="p-1">
                                <div
                                    class="bg-white/10 backdrop-blur-md rounded-lg border border-white/10 px-3 py-2 shadow-sm">
                                    @if ($scholar->program_type === 'CHED')
                                        {{ $scholar->applicationForm->year_level ?? 'N/A' }}
                                    @else
                                        {{ is_array($scholar->applicationForm->scholarship_type ?? null)
                                            ? implode(', ', $scholar->applicationForm->scholarship_type)
                                            : $scholar->applicationForm->scholarship_type ?? 'N/A' }}
                                    @endif
                                </div>
                            </td>

                            <!-- School -->
                            <td class="p-1">
                                <div
                                    class="bg-white/10 backdrop-blur-md rounded-lg border border-white/10 px-3 py-2 shadow-sm">
                                    @if ($scholar->program_type === 'CHED')
                                        {{ $scholar->applicationForm->school ?? 'N/A' }}
                                    @else
                                        {{ $scholar->applicationForm->bs_university ?? 'N/A' }}
                                    @endif
                                </div>
                            </td>
                            <!-- Semester -->
                            <td class="p-1">
                                <div
                                    class="bg-white/10 backdrop-blur-md rounded-lg border border-white/10 px-3 py-2 shadow-sm">
                                    {{ $scholar->applicationForm->school_term ?? 'N/A' }}
                                </div>
                            </td>

                            <!-- Scholar Status -->
                           @php
    $status = $scholar->status ?? 'N/A';
    $statusLower = strtolower($status);
    $badgeClass = ''; // For Tailwind background and border
    $textColor = 'text-gray-700'; // For Tailwind text color
    $displayStatus = ucfirst(str_replace('_', ' ', $status)); // Para nindot tan-awon ang text

    // --- COLOR MAPPING LOGIC ---

    // GREEN: Approved, Qualifiers, Graduated, On Track
    if (in_array($statusLower, ['qualifiers', 'approved', 'graduated_on_time', 'graduated_ext', 'gs_on_track'])) {
        $badgeClass = 'bg-green-500/30 border-green-500'; 
        $textColor = 'text-green-800';
    } 
    // YELLOW/ORANGE: Pending, For Review, Monitoring, Leave
    elseif (in_array($statusLower, ['pending', 'document_verification', 'for_interview', 'on_ext_for_monitoring', 'on_ext_complete_fa', 'on_ext_with_fa', 'leave_of_absence'])) {
        $badgeClass = 'bg-yellow-500/30 border-yellow-500'; 
        $textColor = 'text-yellow-800';
    } 
    // RED: Rejected, Terminated, Non-Compliant, Withdrawn
    elseif (in_array($statusLower, ['rejected', 'not_availing', 'suspended', 'no_report', 'non_compliance', 'terminated', 'withdrawn'])) {
        $badgeClass = 'bg-red-500/30 border-red-500'; 
        $textColor = 'text-red-700';
    } 
    // DEFAULT/INFO: Deferred
    else {
        $badgeClass = 'bg-blue-500/30 border-blue-500';
        $textColor = 'text-blue-700';
    }
@endphp

<td class="p-4">
    <span
        class="px-3 py-1 rounded-full text-xs font-semibold shadow-sm backdrop-blur-sm border {{ $badgeClass }} {{ $textColor }}">
        {{ $displayStatus }}
    </span>
</td>


                            <!-- Approved At -->
                            <td class="p-1">
                                <div
                                    class="bg-white/10 backdrop-blur-md rounded-lg border border-white/10 px-3 py-2 shadow-sm">
                                    {{ $scholar->updated_at->format('M d, Y') }}
                                </div>
                            </td>

                            <!-- Action -->
                            <td class="p-1">
                                <div
                                    class="bg-white/10 backdrop-blur-md rounded-lg border border-white/10 px-3 py-2 shadow-sm">
                                    @if ($scholar->program_type === 'DOST')
                                        <a href="{{ route('admin.scholars.show', $scholar->id) }}"
                                            class="text-blue-600 hover:text-blue-800 text-sm font-semibold">
                                            View
                                        </a>
                                    @else
                                        <a href="{{ route('admin.ched.show', $scholar->id) }}"
                                            class="text-blue-600 hover:text-blue-800 text-sm font-semibold">
                                            View
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="p-4 text-center text-gray-500">No scholars yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>


        <!-- 📄 Pagination with Glassmorphism -->
        @if ($scholars->hasPages())
            <div class="p-4 bg-white/20 backdrop-blur-xl border border-white/20 shadow-md rounded-xl">
                {{ $scholars->links('pagination::tailwind') }}
            </div>
        @endif
    </div>
@endsection
