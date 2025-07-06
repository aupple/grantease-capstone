@extends('layouts.admin-layout')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold mb-4">🎓 Approved Scholars</h1>

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
                </tr>
            </thead>
            <tbody>
                @forelse ($scholars as $scholar)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-3">{{ $scholar->user->full_name ?? $scholar->user->first_name . ' ' . $scholar->user->last_name }}</td>
                        <td class="p-3">{{ $scholar->user->email }}</td>
                        <td class="p-3">{{ $scholar->program }}</td>
                        <td class="p-3">{{ $scholar->school }}</td>
                        <td class="p-3">{{ $scholar->year_level }}</td>
                        <td class="p-3">
                            {{ $scholar->submitted_at 
                                ? \Carbon\Carbon::parse($scholar->submitted_at)->format('M d, Y') 
                                : 'Not submitted' }}
                        </td>
                        <td class="p-3">
                            {{ \Carbon\Carbon::parse($scholar->updated_at)->format('M d, Y') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-4 text-center text-gray-500">No scholars yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
