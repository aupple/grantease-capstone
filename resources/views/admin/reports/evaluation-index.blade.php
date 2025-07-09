@extends('layouts.admin-layout')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold mb-4">📋 Evaluation Sheet – Applicants List</h1>

    <div class="bg-white rounded shadow overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Name</th>
                    <th class="p-3 text-left">Program</th>
                    <th class="p-3 text-left">School</th>
                    <th class="p-3 text-left">Status</th>
                    <th class="p-3 text-left">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($applicants as $applicant)
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-3">
                        {{ $applicant->user->full_name ?? ($applicant->user?->first_name . ' ' . $applicant->user?->last_name) }}
                    </td>
                    <td class="p-3">{{ $applicant->program }}</td>
                    <td class="p-3">{{ $applicant->school }}</td>
                    <td class="p-3 capitalize">
                        <span class="px-2 py-1 rounded text-xs font-semibold
                            @if ($applicant->status === 'approved') bg-green-100 text-green-800
                            @elseif ($applicant->status === 'rejected') bg-red-100 text-red-800
                            @elseif ($applicant->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif ($applicant->status === 'document_verification') bg-purple-100 text-purple-800
                            @elseif ($applicant->status === 'for_interview') bg-blue-100 text-blue-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ str_replace('_', ' ', $applicant->status) }}
                        </span>
                    </td>
                    <td class="p-3">
                        <a href="{{ route('admin.reports.evaluation.show', $applicant->application_form_id) }}"
                           class="text-blue-600 hover:underline text-sm font-semibold">View</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-4 text-center text-gray-500">No applicants found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $applicants->links('pagination::tailwind') }}
    </div>
</div>
@endsection
