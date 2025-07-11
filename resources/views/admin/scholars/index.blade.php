@extends('layouts.admin-layout')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold mb-4">🎓 Approved Scholars</h1>

    <!-- Scholars Table -->
    <div class="bg-white shadow rounded overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Applicant Name</th>
                    <th class="p-3 text-left">Email</th>
                    <th class="p-3 text-left">Program</th>
                    <th class="p-3 text-left">School</th>
                    <th class="p-3 text-left">Year Level</th>
                    <th class="p-3 text-left">Submitted At</th>
                    <th class="p-3 text-left">Approved At</th>
                    <th class="p-3 text-left">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($scholars as $scholar)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-3">
                            {{ $scholar->user->full_name ?? $scholar->user->first_name . ' ' . $scholar->user->last_name }}
                        </td>
                        <td class="p-3">{{ $scholar->user->email }}</td>

                        {{-- ✅ These come from the applicationForm relation --}}
                        <td class="p-3">{{ $scholar->applicationForm->program ?? 'N/A' }}</td>
                        <td class="p-3">{{ $scholar->applicationForm->school ?? 'N/A' }}</td>
                        <td class="p-3">{{ $scholar->applicationForm->year_level ?? 'N/A' }}</td>

                        <td class="p-3">
                            {{ optional($scholar->applicationForm->submitted_at ?? $scholar->applicationForm->created_at)->format('M d, Y') }}
                        </td>
                        <td class="p-3">{{ $scholar->updated_at->format('M d, Y') }}</td>
                        <td class="p-3">
                            <a href="{{ route('admin.applications.show', $scholar->application_form_id) }}"
                               class="text-blue-600 hover:underline text-sm font-semibold">View</a>
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

    <!-- Pagination -->
    @if ($scholars->hasPages())
        <div class="mt-4">
            {{ $scholars->links('pagination::tailwind') }}
        </div>
    @endif
</div>
@endsection
