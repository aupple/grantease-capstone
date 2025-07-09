@extends('layouts.admin-layout')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold mb-4">📝 Scoresheets – Applicant Evaluation Summary</h1>

    <div class="bg-white rounded shadow overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Name</th>
                    <th class="p-3 text-left">Program</th>
                    <th class="p-3 text-left">School</th>
                    <th class="p-3 text-left text-center">Academic</th>
                    <th class="p-3 text-left text-center">Financial</th>
                    <th class="p-3 text-left text-center">Interview</th>
                    <th class="p-3 text-left">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($applicants as $applicant)
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-3">
                        {{ $applicant->user->full_name ?? $applicant->user->first_name . ' ' . $applicant->user->last_name }}
                    </td>
                    <td class="p-3">{{ $applicant->program }}</td>
                    <td class="p-3">{{ $applicant->school }}</td>
                    <td class="p-3 text-center font-semibold text-blue-800">
                        {{ $applicant->academic_score ?? 'N/A' }}
                    </td>
                    <td class="p-3 text-center font-semibold text-green-800">
                        {{ $applicant->financial_score ?? 'N/A' }}
                    </td>
                    <td class="p-3 text-center font-semibold text-purple-800">
                        {{ $applicant->interview_score ?? 'N/A' }}
                    </td>
                    <td class="p-3">
                        <a href="{{ route('admin.reports.scoresheet.show', $applicant->application_form_id) }}"
                           class="text-blue-600 hover:underline text-sm font-semibold">
                            View
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center p-4 text-gray-500">No applicants found.</td>
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
