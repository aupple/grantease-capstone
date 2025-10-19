@extends('layouts.admin-layout')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <h1 class="text-3xl font-bold text-red-700">Rejected Application Details</h1>

    {{-- Application Info --}}
    <div class="bg-white/20 backdrop-blur-xl border border-white/30 shadow-xl rounded-2xl p-6">
        <div class="space-y-4">
            <p><span class="font-semibold">Name:</span> {{ $application->user->first_name }} {{ $application->user->last_name }}</p>
            <p><span class="font-semibold">Email:</span> {{ $application->user->email }}</p>
            <p><span class="font-semibold">Program:</span> {{ $application->program ?? 'N/A' }}</p>
            <p><span class="font-semibold">Rejected At:</span> {{ $application->updated_at->format('M d, Y H:i') }}</p>
            <p><span class="font-semibold">Reason:</span> {{ $application->rejection_reason ?? 'No remarks provided' }}</p>
        </div>
    </div>

    {{-- Actions --}}
    <div class="flex justify-between mt-6">
        {{-- Back button --}}
        <a href="{{ route('admin.rejected.index') }}" 
           class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
            ← Back to Rejected List
        </a>

        {{-- Restore Application form --}}
        <form action="{{ route('admin.applications.update-status', $application->application_form_id) }}" method="POST">
            @csrf
            <input type="hidden" name="status" value="pending">
            <button type="submit" 
                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                Restore Application
            </button>
        </form>
    </div>
</div>
@endsection
