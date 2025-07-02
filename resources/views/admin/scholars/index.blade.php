@extends('layouts.admin')

<<<<<<< HEAD
@section('title', 'Approved Scholars')
@section('header-title', 'Approved Scholars')
=======
    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th>Applicant Name</th>
                <th>Email</th>
                <th>Program</th>
                <th>School</th>
                <th>Year Level</th>
                <th>Submitted At</th>
                <th>Approved At</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($scholars as $scholar)
                <tr>
                    <td>{{ $scholar->user->full_name }}</td>
                    <td>{{ $scholar->user->email }}</td>
                    <td>{{ $scholar->program }}</td>
                    <td>{{ $scholar->school }}</td>
                    <td>{{ $scholar->year_level }}</td>
                    <td>
                        {{ $scholar->submitted_at 
                            ? \Carbon\Carbon::parse($scholar->submitted_at)->format('Y-m-d') 
                            : 'Not submitted' }}
                    </td>
                    <td>
                        {{ \Carbon\Carbon::parse($scholar->updated_at)->format('Y-m-d') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">No scholars yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
>>>>>>> d65b1dac2147ef244807d26b5537693ed11f0791

@section('content')
    <div class="bg-white p-6 rounded shadow">
        <h2 class="text-2xl font-bold text-blue-800 mb-4">🎓 Approved Scholars</h2>

        <table border="1" cellpadding="10" class="w-full text-sm text-left border border-gray-300">
            <thead class="bg-blue-100 text-blue-900">
                <tr>
                    <th>Applicant Name</th>
                    <th>Email</th>
                    <th>Program</th>
                    <th>School</th>
                    <th>Year Level</th>
                    <th>Submitted At</th>
                    <th>Approved At</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($scholars as $scholar)
                    <tr class="hover:bg-gray-50">
                        <td>{{ $scholar->user->name }}</td>
                        <td>{{ $scholar->user->email }}</td>
                        <td>{{ $scholar->program }}</td>
                        <td>{{ $scholar->school }}</td>
                        <td>{{ $scholar->year_level }}</td>
                        <td>
                            {{ $scholar->submitted_at
                                ? \Carbon\Carbon::parse($scholar->submitted_at)->format('Y-m-d')
                                : 'Not submitted' }}
                        </td>
                        <td>
                            {{ \Carbon\Carbon::parse($scholar->updated_at)->format('Y-m-d') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-gray-600 py-4">No scholars yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
