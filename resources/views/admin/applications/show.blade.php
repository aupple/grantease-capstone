@extends('layouts.admin-layout')

@section('content')
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-semibold text-gray-800 leading-tight">
                    Application #{{ $application->application_form_id }}
                </h2>
                <p class="text-sm text-gray-500">Submitted on {{ $application->created_at->format('F d, Y') }}</p>
            </div>
            <div class="space-x-2">
                <form method="POST" action="{{ route('admin.applications.approve', $application->application_form_id) }}" class="inline">
                    @csrf
                    <button class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                        ✅ Approve
                    </button>
                </form>

                <form method="POST" action="{{ route('admin.applications.reject', $application->application_form_id) }}" class="inline">
                    @csrf
                    <input type="hidden" name="remarks" value="Rejected by admin.">
                    <button class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                        ❌ Reject
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- LEFT SIDE: Personal, Academic, Financial Info -->
        <div class="col-span-2 space-y-6">
            <!-- 🧑 Personal Information -->
            <div class="bg-white p-6 rounded shadow">
                <h3 class="text-lg font-bold mb-4">Personal Information</h3>
                <p><strong>Name:</strong> {{ $application->user->full_name ?? $application->user->first_name . ' ' . $application->user->last_name }}</p>
                <p><strong>Email:</strong> {{ $application->user->email }}</p>
                <p><strong>Phone:</strong> {{ $application->user->phone ?? 'N/A' }}</p>
                <p><strong>Address:</strong> {{ $application->user->address ?? 'N/A' }}</p>
            </div>

            <!-- 🎓 Academic Background -->
            <div class="bg-white p-6 rounded shadow">
                <h3 class="text-lg font-bold mb-4">🎓 Academic Background</h3>
                <p><strong>Program:</strong> {{ $application->program }}</p>
                <p><strong>School:</strong> {{ $application->school }}</p>
                <p><strong>Year Level:</strong> {{ $application->year_level }}</p>
                <p><strong>Reason:</strong> {{ $application->reason ?? 'N/A' }}</p>
            </div>

            <!-- 💸 Financial Status -->
            <div class="bg-white p-6 rounded shadow">
                <h3 class="text-lg font-bold mb-4"> Financial Status</h3>
                <p><strong>Monthly Income:</strong> {{ $application->monthly_income ?? 'N/A' }}</p>
                <p><strong>Family Members:</strong> {{ $application->family_size ?? 'N/A' }}</p>
                <p><strong>Other Scholarships:</strong> {{ $application->other_scholarships ?? 'N/A' }}</p>
            </div>
        </div>

        <!-- RIGHT SIDE: Docs, Remarks, Actions -->
        <div class="space-y-6">
            <!-- 📎 Documents + Individual Remarks -->
            <div class="bg-white p-6 rounded shadow">
                <h3 class="text-lg font-bold mb-4">📎 Documents</h3>

                @php
                    $documents = [
                        'Valid ID' => '#',
                        'Certificate of Enrollment' => '#',
                        'Grade Report' => '#',
                    ];
                @endphp

                @foreach ($documents as $label => $link)
                    <div class="mb-3">
                        <div class="flex justify-between items-center">
                            <p class="text-sm font-medium">{{ $label }}</p>
                            <a href="{{ $link }}" class="text-blue-600 hover:underline text-sm">View</a>
                        </div>
                        <textarea name="document_remarks[{{ Str::slug($label, '_') }}]"
                            rows="1"
                            class="w-full border rounded px-2 py-1 mt-1 text-xs resize-none"
                            placeholder="Remarks for {{ strtolower($label) }}"></textarea>
                    </div>
                @endforeach
            </div>

         <!-- ℹ️ Application Info -->
<div class="bg-white p-6 rounded shadow">
    <h3 class="text-lg font-bold mb-4">Application Info</h3>

    <form action="{{ route('admin.applications.update-status', $application->application_form_id) }}" method="POST" class="space-y-3">
        @csrf

        <!-- ✅ Status with badge + dropdown button -->
        <div class="flex items-center gap-4">
    <strong class="text-sm">Status:</strong>

    <!-- ✅ Rounded Status Badge -->
    <span class="px-3 py-1 rounded-full text-sm font-bold
        @if ($application->status === 'approved') bg-green-100 text-green-800
        @elseif ($application->status === 'rejected') bg-red-100 text-red-800
        @elseif ($application->status === 'pending') bg-yellow-100 text-yellow-800
        @elseif ($application->status === 'document_verification') bg-purple-100 text-purple-800
        @elseif ($application->status === 'for_interview') bg-blue-100 text-blue-800
        @else bg-gray-100 text-gray-800
        @endif">
        {{ ucfirst(str_replace('_', ' ', $application->status)) }}
    </span>

    <!-- ✨ Spaced, cleaner dropdown -->
    <div class="relative">
        <select name="status"
                class="text-sm border rounded px-1 py-0 bg-white shadow-sm focus:ring-2 focus:ring-blue-100">
            <option disabled selected>Update Status</option>
            @foreach(['pending', 'document_verification', 'for_interview', 'approved', 'rejected'] as $s)
                <option value="{{ $s }}" {{ $application->status === $s ? 'selected' : '' }}>
                    {{ ucfirst(str_replace('_', ' ', $s)) }}
                </option>
            @endforeach
        </select>
    </div>
</div>


        <!-- ✅ Remarks inline -->
        <div class="flex items-center gap-3">
            <strong>Remarks:</strong>
            <input type="text"
                   name="remarks"
                   class="text-xs border px-2 py-1 rounded w-56"
                   placeholder="Remarks (optional)"
                   value="{{ $application->remarks }}">
        </div>

        <!-- ✅ Update button -->
        <button type="submit" class="bg-blue-600 text-white px-4 py-1.5 text-sm rounded hover:bg-blue-700 transition">
            Update
        </button>
    </form>
</div>

            <!-- ⚙️ Quick Actions -->
        <!-- ⚙️ Quick Actions -->
<div class="bg-white p-6 rounded shadow">
    <h3 class="text-lg font-bold mb-4">Quick Actions</h3>
    <div class="space-y-2">
        <a href="#"
           class="block w-full text-center bg-gray-50 border border-gray-200 text-sm text-gray-800 rounded-md px-4 py-2 hover:bg-gray-100 transition">
           📄 Print Application
        </a>
        <a href="#"
           class="block w-full text-center bg-gray-50 border border-gray-200 text-sm text-gray-800 rounded-md px-4 py-2 hover:bg-gray-100 transition">
           📥 Download Documents
        </a>
        <a href="#"
           class="block w-full text-center bg-gray-50 border border-gray-200 text-sm text-gray-800 rounded-md px-4 py-2 hover:bg-gray-100 transition">
           💬 Send Message
        </a>
    </div>
</div>
    </div>
@endsection
