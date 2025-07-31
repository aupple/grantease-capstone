@extends('layouts.admin-layout')

@section('content')
<div class="mb-6 relative">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-semibold text-gray-800 leading-tight">
                Application #{{ $application->application_form_id }}
            </h2>
            <p class="text-sm text-gray-500">Submitted on {{ $application->created_at->format('F d, Y') }}</p>
        </div>

        <div id="actionButtons" class="hidden flex gap-2">
            <form action="{{ route('admin.applications.update-status', $application->application_form_id) }}" method="POST">
                @csrf
                <input type="hidden" name="status" value="approved">
                <button type="submit" class="bg-green-600 text-white text-sm px-4 py-1.5 rounded-md hover:bg-green-700 transition font-semibold">Approve</button>
            </form>
            <form action="{{ route('admin.applications.update-status', $application->application_form_id) }}" method="POST">
                @csrf
                <input type="hidden" name="status" value="rejected">
                <button type="submit" class="bg-red-600 text-white text-sm px-4 py-1.5 rounded-md hover:bg-red-700 transition font-semibold">Reject</button>
            </form>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- LEFT SIDE -->
    <div x-data="{ sectionIndex: 0 }" class="col-span-2 space-y-6">

       <!-- 👤 Personal Information -->
<div x-show="sectionIndex === 0" class="transition-all duration-300 bg-white/30 backdrop-blur-md border border-white/20 shadow-md rounded-2xl p-6">
    <h3 class="text-lg font-bold mb-4">👤 Personal Information</h3>

    <p><strong>Full Name:</strong> {{ $application->last_name }}, {{ $application->first_name }} {{ $application->middle_name }} {{ $application->suffix }}</p>
    <p><strong>Email:</strong> {{ $application->email }}</p>
    <p><strong>Contact Number:</strong> {{ $application->contact }}</p>
    <p><strong>Sex / Gender:</strong> {{ $application->gender ?? 'N/A' }}</p>
    <p><strong>Birthdate:</strong> {{ $application->dob ?? 'N/A' }}</p>
    <p><strong>Age:</strong> {{ $application->age ?? 'N/A' }}</p>
    <p><strong>Birthplace:</strong> {{ $application->birthplace ?? 'N/A' }}</p>
    <p><strong>Citizenship:</strong> {{ $application->citizenship ?? 'N/A' }}</p>
    <p><strong>Civil Status:</strong> {{ $application->civil_status ?? 'N/A' }}</p>

    <hr class="my-3 border-dashed">

    <p><strong>Permanent Address:</strong> {{ $application->permanent_address ?? 'N/A' }}</p>
    <p><strong>Current Address:</strong> {{ $application->current_address ?? 'N/A' }}</p>
    <p><strong>Home Address:</strong> {{ $application->home_address ?? 'N/A' }}</p>
    <p><strong>Region:</strong> {{ $application->region ?? 'N/A' }}</p>
    <p><strong>District:</strong> {{ $application->district ?? 'N/A' }}</p>
    <p><strong>Zip Code:</strong> {{ $application->zip_code ?? 'N/A' }}</p>

    <hr class="my-3 border-dashed">

    <p><strong>Passport Number:</strong> {{ $application->passport_no ?? 'N/A' }}</p>
    <p><strong>Father's Name:</strong> {{ $application->father_name ?? 'N/A' }}</p>
    <p><strong>Father's Occupation:</strong> {{ $application->father_occupation ?? 'N/A' }}</p>
    <p><strong>Mother's Name:</strong> {{ $application->mother_name ?? 'N/A' }}</p>
    <p><strong>Mother's Occupation:</strong> {{ $application->mother_occupation ?? 'N/A' }}</p>
</div>


       <!-- 🎓 Academic Background -->
<div x-show="sectionIndex === 1" class="transition-all duration-300 bg-white/30 backdrop-blur-md border border-white/20 shadow-md rounded-2xl p-6">
    <h3 class="text-lg font-bold mb-4">🎓 Academic Background</h3>

    <p><strong>Program Applied:</strong> {{ $application->program ?? 'N/A' }}</p>
    <p><strong>Academic Year:</strong> {{ $application->academic_year ?? 'N/A' }}</p>
    <p><strong>School Term:</strong> {{ $application->school_term ?? 'N/A' }}</p>
    <p><strong>Application No.:</strong> {{ $application->application_no ?? 'N/A' }}</p>

    <hr class="my-3 border-dashed">

    <p><strong>School:</strong> {{ $application->school ?? 'N/A' }}</p>
    <p><strong>Year Level:</strong> {{ $application->year_level ?? 'N/A' }}</p>
    <p><strong>Reason for Applying:</strong> {{ $application->reason ?? 'N/A' }}</p>

    <hr class="my-3 border-dashed">

    <h4 class="font-semibold mt-4 mb-2">📘 Bachelor's Degree</h4>
    <p><strong>School:</strong> {{ $application->bs_school ?? 'N/A' }}</p>
    <p><strong>Course:</strong> {{ $application->bs_course ?? 'N/A' }}</p>
    <p><strong>Graduation Year:</strong> {{ $application->bs_grad_year ?? 'N/A' }}</p>
    <p><strong>Honors:</strong> {{ $application->bs_honors ?? 'N/A' }}</p>
    <p><strong>Scholarship Type:</strong> {{ $application->bs_scholarship_type ?? 'N/A' }}</p>

    <h4 class="font-semibold mt-4 mb-2">📗 Master's Degree</h4>
    <p><strong>School:</strong> {{ $application->ms_school ?? 'N/A' }}</p>
    <p><strong>Course:</strong> {{ $application->ms_course ?? 'N/A' }}</p>
    <p><strong>Graduation Year:</strong> {{ $application->ms_grad_year ?? 'N/A' }}</p>
    <p><strong>Honors:</strong> {{ $application->ms_honors ?? 'N/A' }}</p>
    <p><strong>Scholarship Type:</strong> {{ $application->ms_scholarship_type ?? 'N/A' }}</p>

    <h4 class="font-semibold mt-4 mb-2">📕 Doctorate Degree</h4>
    <p><strong>School:</strong> {{ $application->phd_school ?? 'N/A' }}</p>
    <p><strong>Course:</strong> {{ $application->phd_course ?? 'N/A' }}</p>
    <p><strong>Graduation Year:</strong> {{ $application->phd_grad_year ?? 'N/A' }}</p>
    <p><strong>Honors:</strong> {{ $application->phd_honors ?? 'N/A' }}</p>
    <p><strong>Scholarship Type:</strong> {{ $application->phd_scholarship_type ?? 'N/A' }}</p>

    <hr class="my-3 border-dashed">

    <p><strong>Strand Category:</strong> {{ $application->strand_category ?? 'N/A' }}</p>
    <p><strong>Graduate Strand:</strong> {{ $application->graduate_strand ?? 'N/A' }}</p>
    <p><strong>Strand Type:</strong> {{ $application->strand_type ?? 'N/A' }}</p>
    <p><strong>Scholarship Type:</strong> {{ $application->scholarship_type ?? 'N/A' }}</p>
    <p><strong>Application Type:</strong> {{ $application->application_type ?? 'N/A' }}</p>
    <p><strong>Entry Status:</strong> {{ $application->entry_status ?? 'N/A' }}</p>

    <hr class="my-3 border-dashed">

    <h4 class="font-semibold mt-4 mb-2">🔁 University Transfer</h4>
    <p><strong>New University:</strong> {{ $application->new_university ?? 'N/A' }}</p>
    <p><strong>New Course:</strong> {{ $application->new_course ?? 'N/A' }}</p>
    <p><strong>Lateral Entry University:</strong> {{ $application->lateral_university ?? 'N/A' }}</p>
    <p><strong>Lateral Entry Course:</strong> {{ $application->lateral_course ?? 'N/A' }}</p>

    <hr class="my-3 border-dashed">

    <p><strong>Units Earned:</strong> {{ $application->units_earned ?? 'N/A' }}</p>
    <p><strong>Units Remaining:</strong> {{ $application->units_remaining ?? 'N/A' }}</p>
    <p><strong>Research Title:</strong> {{ $application->research_title ?? 'N/A' }}</p>
    <p><strong>Research Approved:</strong> {{ $application->research_approved ?? 'N/A' }}</p>
    <p><strong>Last Thesis Date:</strong> {{ $application->last_thesis_date ?? 'N/A' }}</p>
</div>


       <!-- 💼 Employment / Business -->
<div x-show="sectionIndex === 2" class="transition-all duration-300 bg-white/30 backdrop-blur-md border border-white/20 shadow-md rounded-2xl p-6">
    <h3 class="text-lg font-bold mb-4">💼 Employment / Business</h3>

    <p><strong>Employment Status:</strong> {{ $application->employment_status ?? 'N/A' }}</p>
    <p><strong>Position:</strong> {{ $application->position ?? 'N/A' }}</p>
    <p><strong>Length of Service:</strong> {{ $application->length_of_service ?? 'N/A' }}</p>

    <hr class="my-3 border-dashed">

    <p><strong>Company Name:</strong> {{ $application->company_name ?? 'N/A' }}</p>
    <p><strong>Company Address:</strong> {{ $application->company_address ?? 'N/A' }}</p>
    <p><strong>Company Email:</strong> {{ $application->company_email ?? 'N/A' }}</p>
    <p><strong>Company Website:</strong> {{ $application->company_website ?? 'N/A' }}</p>
    <p><strong>Company Phone:</strong> {{ $application->company_phone ?? 'N/A' }}</p>
    <p><strong>Company Fax:</strong> {{ $application->company_fax ?? 'N/A' }}</p>

    <hr class="my-3 border-dashed">

    <h4 class="font-semibold mt-4 mb-2">🏢 Self-Employed / Business Info</h4>
    <p><strong>Business Name:</strong> {{ $application->business_name ?? 'N/A' }}</p>
    <p><strong>Business Address:</strong> {{ $application->business_address ?? 'N/A' }}</p>
    <p><strong>Business Email:</strong> {{ $application->business_email ?? 'N/A' }}</p>
    <p><strong>Business Type:</strong> {{ $application->business_type ?? 'N/A' }}</p>
    <p><strong>Years of Operation:</strong> {{ $application->years_operation ?? 'N/A' }}</p>

    <hr class="my-3 border-dashed">

    <h4 class="font-semibold mt-4 mb-2">📚 Studying While Working</h4>
    <p><strong>Currently Studying:</strong> {{ $application->studying ?? 'N/A' }}</p>
    <p><strong>If Yes, School Name:</strong> {{ $application->studying_school ?? 'N/A' }}</p>
    <p><strong>Course:</strong> {{ $application->studying_course ?? 'N/A' }}</p>
    <p><strong>Year Level:</strong> {{ $application->studying_year_level ?? 'N/A' }}</p>

    <hr class="my-3 border-dashed">

    <h4 class="font-semibold mt-4 mb-2">🧰 Government Service Info</h4>
    <p><strong>Government Employee:</strong> {{ $application->govt_employee ?? 'N/A' }}</p>
    <p><strong>Agency Name:</strong> {{ $application->govt_agency_name ?? 'N/A' }}</p>
    <p><strong>Service Years:</strong> {{ $application->govt_years_service ?? 'N/A' }}</p>
</div>


        <!-- 📈 Future Plans & Accomplishments -->
<div x-show="sectionIndex === 3" class="transition-all duration-300 bg-white/30 backdrop-blur-md border border-white/20 shadow-md rounded-2xl p-6">
    <h3 class="text-lg font-bold mb-4">📈 Future Plans & Accomplishments</h3>

    <h4 class="font-semibold mt-4 mb-2">🎯 Career & Research Plans</h4>
    <p><strong>Career Plans:</strong> {{ $application->career_plans ?? 'N/A' }}</p>
    <p><strong>Research Plans:</strong> {{ $application->research_plans ?? 'N/A' }}</p>

    <hr class="my-3 border-dashed">

    <h4 class="font-semibold mt-4 mb-2">🧪 R&D Involvement</h4>
    <p>{{ $application->rnd_involvement ?? 'N/A' }}</p>

    <hr class="my-3 border-dashed">

    <h4 class="font-semibold mt-4 mb-2">📚 Publications</h4>
    @php
        $publications = is_array($application->publications) 
            ? $application->publications 
            : json_decode($application->publications, true);
    @endphp
    @if (!empty($publications))
        <ul class="list-disc list-inside text-sm space-y-1">
            @foreach ($publications as $item)
                <li>{{ $item }}</li>
            @endforeach
        </ul>
    @else
        <p>No publications submitted.</p>
    @endif

    <hr class="my-3 border-dashed">

    <h4 class="font-semibold mt-4 mb-2">🏆 Awards</h4>
    @php
        $awards = is_array($application->awards) 
            ? $application->awards 
            : json_decode($application->awards, true);
    @endphp
    @if (!empty($awards))
        <ul class="list-disc list-inside text-sm space-y-1">
            @foreach ($awards as $item)
                <li>{{ $item }}</li>
            @endforeach
        </ul>
    @else
        <p>No awards submitted.</p>
    @endif
</div>


        <!-- Navigation -->
        <div class="flex justify-between items-center px-6">
            <button @click="sectionIndex = Math.max(sectionIndex - 1, 0)"
                class="px-4 py-2 bg-gray-400 border border-gray-300 text-sm font-semibold rounded hover:bg-gray-300 transition"
                :disabled="sectionIndex === 0">← Back</button>
            <div class="text-sm font-semibold text-gray-600">
                Step <span x-text="sectionIndex + 1"></span> of 4
            </div>
            <button @click="sectionIndex = Math.min(sectionIndex + 1, 3)"
                class="px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded hover:bg-blue-400 transition"
                :disabled="sectionIndex === 3">Next →</button>
        </div>

    </div>

    <!-- RIGHT SIDE -->
    <div class="col-span-1 space-y-6">

        <!-- 📑 Documents -->
<div class="bg-white/30 backdrop-blur-md border border-white/20 shadow-md rounded-2xl p-6">
    <h3 class="text-lg font-bold mb-4">Documents</h3>

    @php
        use Illuminate\Support\Str;

        $documents = [
            'Evaluation Sheet' => $application->evaluation_file_path ?? null,
            'Scoresheet' => $application->scoresheet_file_path ?? null,
            'Passport Picture' => $application->passport_picture ?? null,
            'Form 137' => $application->form137 ?? null,
            'Certificate of Employment' => $application->cert_employment ?? null,
            'Certificate of Purpose' => $application->cert_purpose ?? null,
        ];
    @endphp

    @foreach ($documents as $label => $file)
        <div class="mb-4">
            <div class="flex justify-between items-center">
                <p class="text-sm font-medium">{{ $label }}</p>
                <div class="flex items-center gap-3">
                    @if ($file)
                        <a href="{{ Str::startsWith($file, 'storage/') ? asset($file) : asset('storage/' . $file) }}" target="_blank" class="text-blue-600 hover:underline text-sm">View</a>
                        <label class="inline-flex items-center text-sm cursor-pointer">
                            <input type="checkbox" class="peer hidden checkbox-tracker">
                            <div class="w-2.5 h-2.5 rounded-full border border-gray-400 peer-checked:bg-green-500 peer-checked:border-green-500 transition"></div>
                            <span class="ml-2 text-xs text-gray-600 peer-checked:text-green-600 font-semibold">Verified</span>
                        </label>
                    @else
                        <span class="text-sm text-gray-400">No file submitted</span>
                        <label class="inline-flex items-center text-sm opacity-50 cursor-not-allowed">
                            <input type="checkbox" disabled class="hidden">
                            <div class="w-2.5 h-2.5 rounded-full border border-gray-300 bg-gray-200"></div>
                            <span class="ml-2 text-xs text-gray-400 font-semibold">No file</span>
                        </label>
                    @endif
                </div>
            </div>
        </div>
    @endforeach

        </div>

        <!-- Application Info -->
        <div class="bg-white/30 backdrop-blur-md border border-white/20 shadow-md rounded-2xl p-6">
            <h3 class="text-lg font-bold mb-3">Application Info</h3>
            <div class="mb-3 flex items-center gap-3">
                <strong class="text-sm">Status:</strong>
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
            </div>

            <form action="{{ route('admin.applications.update-status', $application->application_form_id) }}" method="POST" class="flex items-center gap-2">
                @csrf
                <strong class="text-sm">Remarks:</strong>
                <input type="text" name="remarks" class="text-xs border px-3 py-1 rounded w-64" placeholder="Type your message here..." value="{{ $application->remarks }}">
                <button type="submit" class="text-xs text-white bg-blue-500 hover:bg-blue-600 px-3 py-1 rounded transition">Send</button>
            </form>
        </div>

        <!-- ✅ Quick Actions -->
        <div class="bg-white/30 backdrop-blur-md border border-white/20 shadow-md rounded-2xl p-6">
            <h3 class="text-lg font-bold mb-4">Quick Actions</h3>
            <div class="space-y-2">
                <a href="#" class="block w-full text-center bg-gray-50 border border-gray-200 text-sm text-gray-800 rounded-md px-4 py-2 hover:bg-gray-100 transition">📄 Print Application</a>
                <a href="#" class="block w-full text-center bg-gray-50 border border-gray-200 text-sm text-gray-800 rounded-md px-4 py-2 hover:bg-gray-100 transition">📥 Download Documents</a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkboxes = document.querySelectorAll('.checkbox-tracker');
        const actionButtons = document.getElementById('actionButtons');

        function toggleActionButtons() {
            let allChecked = true;
            checkboxes.forEach(cb => {
                if (!cb.checked) {
                    allChecked = false;
                }
            });
            actionButtons.classList.toggle('hidden', !allChecked);
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', toggleActionButtons);
        });
    });
</script>
@endpush
