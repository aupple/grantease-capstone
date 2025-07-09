@extends('layouts.admin-layout')

@section('content')
<div class="bg-white shadow rounded p-8 max-w-5xl mx-auto">
    <h1 class="text-xl font-bold mb-2 text-center uppercase">Accelerated Science & Technology Human Resource Development Program</h1>
    <p class="text-center mb-1">STSD-205 • Rev. 3/04-19-23</p>
    <h2 class="text-lg font-semibold text-center mb-6">Department of Science and Technology – Science Education Institute</h2>

    <form action="#" method="POST"> {{-- You can add route later if needed --}}
        @csrf

        <h2 class="text-lg font-bold mb-4 uppercase">Interview Score Sheet</h2>

        <!-- Header Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm mb-6 border rounded p-4">
            <div>
                <p><strong>Date of Interview:</strong> <input type="date" name="interview_date" class="border px-2 py-1 rounded w-full"></p>
                <p><strong>Application No.:</strong> {{ $applicant->application_form_id }}</p>
                <p><strong>Graduate Scholarship Program:</strong> {{ $applicant->program }}</p>
                <p><strong>Name:</strong> {{ $applicant->user->full_name ?? $applicant->user->first_name . ' ' . $applicant->user->last_name }}</p>
                <p><strong>Civil Status:</strong> {{ $applicant->user->civil_status ?? 'N/A' }}</p>
            </div>
            <div>
                <p><strong>Program of Study:</strong>
                    <label><input type="radio" name="program_study" value="MS"> MS</label>
                    <label class="ml-4"><input type="radio" name="program_study" value="PhD"> PhD</label>
                </p>
                <p><strong>Area of Specialization:</strong> {{ $applicant->specialization ?? 'N/A' }}</p>
                <p><strong>School/University:</strong> {{ $applicant->school }}</p>
                <p><strong>Home Address:</strong> {{ $applicant->user->address ?? 'N/A' }}</p>
                <p><strong>Region:</strong> {{ $applicant->region ?? 'N/A' }}</p>
            </div>
        </div>

        <!-- Score Table -->
        <h3 class="text-md font-semibold mb-2">Interview (30%)</h3>
        <div class="overflow-x-auto mb-6">
            <table class="w-full text-sm border">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border p-2 text-left w-3/4">Criteria</th>
                        <th class="border p-2 text-center">Points</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="border p-2">Career Plans (must be supportive of the DOST’s thrusts)</td>
                        <td class="border p-2 text-center"><input type="number" name="career_plans" class="w-16 border rounded px-1 text-center" max="20"></td>
                    </tr>
                    <tr>
                        <td class="border p-2">Ability to Present Ideas / Innovativeness</td>
                        <td class="border p-2 text-center"><input type="number" name="presentation" class="w-16 border rounded px-1 text-center" max="20"></td>
                    </tr>
                    <tr>
                        <td class="border p-2">Judgement / Critical Thinking</td>
                        <td class="border p-2 text-center"><input type="number" name="judgement" class="w-16 border rounded px-1 text-center" max="20"></td>
                    </tr>
                    <tr>
                        <td class="border p-2">Personality (Composure, etc.)</td>
                        <td class="border p-2 text-center"><input type="number" name="personality" class="w-16 border rounded px-1 text-center" max="20"></td>
                    </tr>
                    <tr>
                        <td class="border p-2">Self-confidence / Independent Work/Study</td>
                        <td class="border p-2 text-center"><input type="number" name="self_confidence" class="w-16 border rounded px-1 text-center" max="20"></td>
                    </tr>
                    <tr class="bg-gray-50 font-bold">
                        <td class="border p-2">TOTAL SCORE</td>
                        <td class="border p-2 text-center">/ 100</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Remarks Section -->
        <div class="mb-4">
            <label class="block font-semibold mb-1">Remarks / Comments:</label>
            <textarea name="remarks" rows="3" class="w-full border rounded px-3 py-2"></textarea>
        </div>

        <!-- Evaluated By -->
        <div class="mb-6">
            <h4 class="font-bold mb-2">Evaluated By:</h4>
            <input type="text" name="evaluator_name" placeholder="Signature Over Printed Name" class="w-full border rounded px-2 py-1 mb-2">
            <input type="date" name="evaluation_date" class="w-full border rounded px-2 py-1">
        </div>

        <!-- Approval Decision -->
        <div class="mb-6">
            <p class="font-semibold">Decision:</p>
            <label><input type="radio" name="decision" value="approved"> APPROVED</label>
            <label class="ml-4"><input type="radio" name="decision" value="disapproved"> DISAPPROVED</label>
        </div>

        <!-- Buttons -->
        <div class="flex justify-between">
            <a href="{{ route('admin.reports.scoresheets') }}" class="bg-gray-200 hover:bg-gray-300 text-sm px-4 py-2 rounded">
                ← Back to List
            </a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-6 py-2 rounded">
                Save Scoresheet
            </button>
        </div>
    </form>
</div>
@endsection
