@extends('layouts.applicant')

@section('title', 'Application')

@section('content')
    <div class="p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Application Form</h2>
        <p>This is the Apply screen content.</p>

        {{-- You can place your actual form here --}}
        {{-- Example form structure --}}
        <form method="POST" action="{{ route('applicant.apply.submit') }}">
            @csrf
            <div class="mb-4">
                <label for="full_name" class="block text-gray-700 font-semibold mb-1">Full Name</label>
                <input type="text" id="full_name" name="full_name" class="w-full border border-gray-300 rounded px-4 py-2" required>
            </div>

            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-semibold mb-1">Email Address</label>
                <input type="email" id="email" name="email" class="w-full border border-gray-300 rounded px-4 py-2" required>
            </div>

            <div class="mb-4">
                <label for="program" class="block text-gray-700 font-semibold mb-1">Program</label>
                <select id="program" name="program" class="w-full border border-gray-300 rounded px-4 py-2">
                    <option value="">Select Program</option>
                    <option value="MSIT">MSIT</option>
                    <option value="MSCS">MSCS</option>
                    <option value="MIT">MIT</option>
                </select>
            </div>

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Submit Application
            </button>
        </form>
    </div>
@endsection
