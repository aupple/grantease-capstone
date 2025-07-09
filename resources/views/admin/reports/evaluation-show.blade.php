@extends('layouts.admin-layout')

@section('content')
<div class="bg-white shadow rounded p-8 max-w-6xl mx-auto">
    <h1 class="text-xl font-bold mb-4 text-center uppercase">Accelerated Science & Technology Human Resource Development Program</h1>
    <p class="text-center mb-1">STSD-204 • Rev. 5/04-19-23</p>
    <h2 class="text-lg font-semibold text-center mb-6">Department of Science and Technology – Science Education Institute</h2>

    <form action="{{ route('admin.reports.evaluation.update', $applicant->application_form_id) }}" method="POST">
        @csrf
        @method('POST')

        <h2 class="text-lg font-bold mb-4 uppercase">Documentary Evaluation Sheet</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm mb-6">
            <div>
                <p><strong>Application No.:</strong> {{ $applicant->application_form_id }}</p>
                <p><strong>Graduate Scholarship Program:</strong> {{ $applicant->program }}</p>
                <p><strong>Name:</strong> {{ $applicant->user->full_name }}</p>
                <p><strong>Civil Status:</strong> {{ $applicant->user->civil_status ?? 'N/A' }}</p>
            </div>
            <div>
                <p><strong>Program of Study:</strong> 
                    <label><input type="radio" name="program_study" value="MS" {{ old('program_study') == 'MS' ? 'checked' : '' }}> MS</label>
                    <label class="ml-4"><input type="radio" name="program_study" value="PhD" {{ old('program_study') == 'PhD' ? 'checked' : '' }}> PhD</label>
                </p>
                <p><strong>Area of Specialization:</strong> <input type="text" name="specialization" value="{{ old('specialization', $applicant->specialization ?? '') }}" class="border rounded px-2 py-1 w-full"></p>
                <p><strong>School/University:</strong> {{ $applicant->school }}</p>
                <p><strong>Home Address:</strong> {{ $applicant->user->address ?? 'N/A' }}</p>
                <p><strong>Region:</strong> {{ $applicant->region ?? 'N/A' }}</p>
            </div>
        </div>

        <!-- CRITERIA SECTION -->
        <h3 class="text-md font-semibold mb-2 mt-8">1. DOST-SEI Scholar Graduate (for DOST-SEI scholars only)</h3>
        <div class="mb-4 space-y-2">
            <input type="text" name="scholarship_program" placeholder="Scholarship Program" class="w-full border rounded px-2 py-1">
            <input type="text" name="graduation_year" placeholder="Year of Award/Graduated" class="w-full border rounded px-2 py-1">
            <input type="text" name="course_school" placeholder="Course/School" class="w-full border rounded px-2 py-1">
        </div>

        <h3 class="text-md font-semibold mb-2 mt-6">2. Admission in Master’s/Doctorate degree</h3>
        <div class="mb-4 space-y-2">
            <input type="text" name="admission_program" placeholder="Program of Study" class="w-full border rounded px-2 py-1">
            <input type="text" name="admission_school" placeholder="School" class="w-full border rounded px-2 py-1">
            <input type="date" name="admission_date" class="w-full border rounded px-2 py-1">
        </div>

        <h3 class="text-md font-semibold mb-2 mt-6">3. Academic Performance</h3>
        <div class="mb-4 space-y-2">
            <input type="text" name="academic_gpa" placeholder="GWA/GPA" class="w-full border rounded px-2 py-1">
            <input type="text" name="academic_honors" placeholder="Honor/s Received" class="w-full border rounded px-2 py-1">
        </div>

        <h3 class="text-md font-semibold mb-2 mt-6">4. Years of Experience (CBPSME only)</h3>
        <div class="mb-4 space-y-2">
            <input type="text" name="employer_deped" placeholder="Current Employer" class="w-full border rounded px-2 py-1">
            <input type="text" name="years_service" placeholder="Years in Service" class="w-full border rounded px-2 py-1">
            <input type="text" name="reg_deped" placeholder="As REGULAR DepEd Teacher" class="w-full border rounded px-2 py-1">
            <input type="text" name="part_deped" placeholder="As Part-time/Contractual/Private" class="w-full border rounded px-2 py-1">
            <label><input type="checkbox" name="performance_rating"> Performance Rating: Very Satisfactory (2 years)</label>
        </div>

        <h3 class="text-md font-semibold mb-2 mt-6">5. Health Status</h3>
        <div class="mb-4 space-y-2">
            <label><input type="checkbox" name="physically_fit"> Physically Fit</label>
            <textarea name="health_comments" rows="2" placeholder="Comments..." class="w-full border rounded px-2 py-1"></textarea>
        </div>

        <h3 class="text-md font-semibold mb-2 mt-6">6. Age</h3>
        <input type="number" name="age" placeholder="Age" class="w-full border rounded px-2 py-1 mb-4">

        <h3 class="text-md font-semibold mb-2 mt-6">7. Endorsements</h3>
        <div class="mb-4 space-y-2">
            <input type="text" name="endorsement_1" placeholder="Endorsement 1" class="w-full border rounded px-2 py-1">
            <input type="text" name="endorsement_2" placeholder="Endorsement 2" class="w-full border rounded px-2 py-1">
        </div>

        <h3 class="text-md font-semibold mb-2 mt-6">8. Additional Info for Lateral Entry</h3>
        <div class="mb-4 space-y-2">
            <input type="text" name="grad_units" placeholder="No. of graduate units earned" class="w-full border rounded px-2 py-1">
            <input type="text" name="gpa_lateral" placeholder="MS/PhD grades (GWA)" class="w-full border rounded px-2 py-1">
        </div>

        <!-- REMARKS -->
        <div class="mb-6">
            <label class="block font-medium mb-1">Remarks / Comments</label>
            <textarea name="remarks" rows="3" class="w-full border rounded px-2 py-2"></textarea>
        </div>

        <!-- Evaluated By -->
        <div class="mb-6">
            <h4 class="font-bold mb-2">Evaluated By:</h4>
            <label><input type="radio" name="decision" value="approved"> APPROVED</label>
            <label class="ml-4"><input type="radio" name="decision" value="disapproved"> DISAPPROVED</label>
            <div class="mt-2">
                <input type="text" name="evaluator_name" placeholder="Signature Over Printed Name" class="w-full border rounded px-2 py-1">
                <input type="date" name="evaluation_date" class="mt-2 w-full border rounded px-2 py-1">
            </div>
        </div>

        <!-- BUTTONS -->
        <div class="flex justify-between">
            <a href="{{ route('admin.reports.evaluation') }}" class="bg-gray-200 hover:bg-gray-300 text-sm px-4 py-2 rounded">
                ← Back to List
            </a>
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white text-sm font-semibold px-6 py-2 rounded">
                Save Evaluation Sheet
            </button>
        </div>
    </form>
</div>
@endsection
