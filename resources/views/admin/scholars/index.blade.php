@extends('layouts.admin-layout')

@section('content')
<div class="mb-6 space-y-6">
    <h1 class="text-3xl font-bold text-gray-800 mb-4">Approved Scholars</h1>

    <!-- 📋 Scholars Table with Glassmorphism -->
    <div class="bg-white/20 backdrop-blur-xl border border-white/30 shadow-xl rounded-2xl overflow-x-auto transition hover:shadow-2xl">
        <table class="min-w-full text-sm text-gray-800">
            <thead class="bg-white/40 text-gray-700 border-b border-white/30">
                <tr>
                    <th class="p-3 text-left font-semibold">Applicant Name</th>
                    <th class="p-3 text-left font-semibold">Email</th>
                    <th class="p-3 text-left font-semibold">Program</th>
                    <th class="p-3 text-left font-semibold">School</th>
                    <th class="p-3 text-left font-semibold">Year Level</th>
                    <th class="p-3 text-left font-semibold">Submitted At</th>
                    <th class="p-3 text-left font-semibold">Approved At</th>
                    <th class="p-3 text-left font-semibold">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/20">
                @forelse ($scholars as $scholar)
                    <tr class="hover:bg-white/30 hover:backdrop-blur-sm transition duration-200 ease-in-out">
                        <td class="p-3">
                            <div class="bg-white/10 backdrop-blur-md rounded-lg border border-white/10 px-3 py-2 shadow-sm">
                                {{ $scholar->user->full_name ?? $scholar->user->first_name . ' ' . $scholar->user->last_name }}
                            </div>
                        </td>
                        <td class="p-3">
                            <div class="bg-white/10 backdrop-blur-md rounded-lg border border-white/10 px-3 py-2 shadow-sm">
                                {{ $scholar->user->email }}
                            </div>
                        </td>
                        <td class="p-3">
                            <div class="bg-white/10 backdrop-blur-md rounded-lg border border-white/10 px-3 py-2 shadow-sm">
                                {{ $scholar->applicationForm->program ?? 'N/A' }}
                            </div>
                        </td>
                        <td class="p-3">
                            <div class="bg-white/10 backdrop-blur-md rounded-lg border border-white/10 px-3 py-2 shadow-sm">
                                {{ $scholar->applicationForm->school ?? 'N/A' }}
                            </div>
                        </td>
                        <td class="p-3">
                            <div class="bg-white/10 backdrop-blur-md rounded-lg border border-white/10 px-3 py-2 shadow-sm">
                                {{ $scholar->applicationForm->year_level ?? 'N/A' }}
                            </div>
                        </td>
                        <td class="p-3">
                            <div class="bg-white/10 backdrop-blur-md rounded-lg border border-white/10 px-3 py-2 shadow-sm">
                                {{ optional($scholar->applicationForm->submitted_at ?? $scholar->applicationForm->created_at)->format('M d, Y') }}
                            </div>
                        </td>
                        <td class="p-3">
                            <div class="bg-white/10 backdrop-blur-md rounded-lg border border-white/10 px-3 py-2 shadow-sm">
                                {{ $scholar->updated_at->format('M d, Y') }}
                            </div>
                        </td>
                        <td class="p-3">
                            <div class="bg-white/10 backdrop-blur-md rounded-lg border border-white/10 px-3 py-2 shadow-sm">
                                <a href="{{ route('admin.applications.show', $scholar->application_form_id) }}"
                                   class="text-blue-600 hover:text-blue-800 text-sm font-semibold transition underline underline-offset-2">View</a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="p-4 text-center text-gray-500">No scholars yet.</td>
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
