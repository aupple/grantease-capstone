@extends('layouts.admin-layout')

@section('content')
<div class="mb-6 space-y-6">
    <h1 class="text-3xl font-bold text-red-700 mb-4">Rejected Applications</h1>

    <div class="bg-white/20 backdrop-blur-xl border border-white/30 shadow-xl rounded-2xl overflow-x-auto">
        <table class="min-w-full text-sm text-gray-800">
            <thead class="bg-white/40 text-gray-700 border-b border-white/30">
                <tr>
                    <th class="p-3 text-left font-semibold">Applicant Name</th>
                    <th class="p-3 text-left font-semibold">Email</th>
                    <th class="p-3 text-left font-semibold">Program</th>
                    <th class="p-3 text-left font-semibold">Rejected At</th>
                    <th class="p-3 text-left font-semibold">Reason</th>
                    <th class="p-3 text-left font-semibold">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/20">
                @forelse ($rejectedApplications as $application)
                    <tr class="hover:bg-white/30 transition duration-200">
                        <td class="p-3">{{ $application->user->first_name }} {{ $application->user->last_name }}</td>
                        <td class="p-3">{{ $application->user->email }}</td>
                        <td class="p-3">{{ $application->program ?? 'N/A' }}</td>
                        <td class="p-3 text-red-600 font-semibold">{{ $application->updated_at->format('M d, Y') }}</td>
                        <td class="p-3">{{ $application->rejection_reason ?? 'No reason provided' }}</td>
                        <td class="p-3">
                            <a href="{{ route('admin.rejected.show', $application->id) }}" 
                               class="text-blue-600 hover:text-blue-800 underline">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-4 text-center text-gray-500">No rejected scholars yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($rejectedApplications->hasPages())
        <div class="p-4">
            {{ $rejectedApplications->links('pagination::tailwind') }}
        </div>
    @endif
</div>
@endsection
