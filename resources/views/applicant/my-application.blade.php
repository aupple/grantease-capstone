<x-app-layout :headerTitle="'Application Status'">
@php
    if (!isset($application)) {
        if (isset($applications) && $applications instanceof \Illuminate\Support\Collection && $applications->count()) {
            $application = $applications->first();
        } else {
            $application = null;
        }
    }
@endphp

@if (!$application)
    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto bg-white rounded-lg shadow p-6 border border-gray-200">
            <h1 class="text-xl font-semibold mb-4">No application found</h1>
            <p class="text-gray-600">You don't have an application to display yet.</p>
            <div class="mt-4">
                <a href="{{ route('applicant.dashboard') }}" class="text-blue-600 hover:underline">← Back to Dashboard</a>
            </div>
        </div>
    </div>
    @php return; @endphp
@endif


<style>
/* ✅ Hide everything except the form container when printing */
@media print {
    body * {
        visibility: hidden !important;
    }
    .print-area, .print-area * {
        visibility: visible !important;
    }
    .print-area {
        position: absolute;
        left: 0;
        top: 0;
        width: 210mm;
        min-height: 297mm;
        margin: 0;
        padding: 20mm;
        background: white;
        box-shadow: none !important;
    }

    /* Hide print buttons and navigation */
    .print:hidden { display: none !important; }
    nav, header, footer, aside { display: none !important; }

    @page {
        size: A4;
        margin: 10mm;
    }
}
</style>


<div class="py-6 px-4 sm:px-6 lg:px-8 bg-gray-50 min-h-screen">
    <div class="max-w-[210mm] mx-auto bg-white shadow rounded-lg border border-gray-300 print:p-12 print-area">

        <!-- ✅ PAGE 1 -->
        <div class="p-8 print-page">

            <div class="flex justify-between items-start border-b border-gray-400 pb-4 mb-6">
    <!-- Left: DOST Logo -->
    <div class="w-[20%] flex justify-start">
        <img src="{{ asset('images/DOST.png') }}" 
             alt="DOST Logo" 
             class="w-[90px] h-[90px] object-contain border border-gray-400 p-1 bg-white">
    </div>

    <!-- Center: Title -->
    <div class="text-center w-[60%]">
        <p class="text-sm font-semibold">DEPARTMENT OF SCIENCE AND TECHNOLOGY</p>
        <p class="text-sm font-semibold">SCIENCE EDUCATION INSTITUTE</p>
        <p class="text-xs">Bicutan, Taguig City</p>
        <h1 class="text-base font-bold underline mt-1">APPLICATION FORM</h1>
        <p class="text-xs mt-1">for the</p>
        <h2 class="text-sm font-bold mt-1 leading-tight">
            SCIENCE AND TECHNOLOGY REGIONAL ALLIANCE OF UNIVERSITIES FOR NATIONAL DEVELOPMENT (STRAND)
        </h2>
    </div>

    <!-- Right: Photo Upload Box -->
    <div class="w-[20%] text-center border border-gray-400 p-2 text-xs">
        <p class="font-semibold mb-1">Attach here</p>
        <p>1 latest passport size picture</p>
        <div class="border border-gray-300 h-[100px] mt-2 bg-gray-50"></div>
    </div>
</div>

            <!-- DETAILS ROW -->
<div class="flex justify-between text-sm mb-4">
    <div>
        <p><strong>Application No.:</strong> {{ $application->application_no ?? '—' }}</p>
        <p><strong>Academic Year:</strong> {{ $application->academic_year ?? '—' }}</p>
        <p><strong>School Term:</strong> {{ $application->school_term ?? '—' }}</p>
    </div>
    <div class="text-right">
        <p><strong>Status:</strong>
            <span class="px-2 py-1 rounded text-xs font-semibold
                @if($application->status === 'approved') bg-green-100 text-green-800
                @elseif($application->status === 'rejected') bg-red-100 text-red-800
                @elseif($application->status === 'under review') bg-blue-100 text-blue-800
                @else bg-yellow-100 text-yellow-800
                @endif">
                {{ ucfirst(str_replace('_', ' ', $application->status)) }}
            </span>
        </p>
    </div>
</div>


           <!-- BUTTON -->
<div class="flex justify-end gap-2 mb-6 print:hidden">
    <button 
        onclick="window.print()" 
        class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded-md">
        🖨️ Print Form
    </button>

    <button 
        id="toggleEditBtn" 
        type="button"
        class="bg-green-600 hover:bg-green-700 text-white text-sm px-4 py-2 rounded-md">
        ✏️ Edit Form
    </button>
</div>

            <!-- I. PERSONAL INFORMATION -->
<h2 class="text-base font-semibold text-blue-700 mb-2 border-b pb-1">
    I. PERSONAL INFORMATION
</h2>

<div class="border border-gray-400 text-[13px] text-gray-800 rounded-sm divide-y divide-gray-400 leading-snug print-serif">

    <!-- Row a -->
    <div class="grid grid-cols-3 gap-2 p-1.5">
        <div>
            <label class="block text-[12px] font-semibold">Last Name</label>
            <div class="editable-field border border-gray-400 px-2 py-0.5 min-h-[24px]" data-field="last_name">
                {{ $application->last_name ?? '—' }}
            </div>
        </div>
        <div>
            <label class="block text-[12px] font-semibold">First Name</label>
            <div class="editable-field border border-gray-400 px-2 py-0.5 min-h-[24px]" data-field="first_name">
                {{ $application->first_name ?? '—' }}
            </div>
        </div>
        <div>
            <label class="block text-[12px] font-semibold">Middle Name</label>
            <div class="editable-field border border-gray-400 px-2 py-0.5 min-h-[24px]" data-field="middle_name">
                {{ $application->middle_name ?? '—' }}
            </div>
        </div>
    </div>

    <!-- Row b -->
    <div class="grid grid-cols-6 gap-2 p-1.5">
        <div class="col-span-2">
            <label class="block text-[12px] font-semibold">Permanent Address</label>
            <div class="editable-field border border-gray-400 px-2 py-0.5 min-h-[24px]" data-field="address_no">
                {{ $application->address_no ?? '—' }}
            </div>
        </div>
        <div>
            <label class="block text-[12px] font-semibold">Street</label>
            <div class="editable-field border border-gray-400 px-2 py-0.5 min-h-[24px]" data-field="address_street">
                {{ $application->address_street ?? '—' }}
            </div>
        </div>
        <div>
            <label class="block text-[12px] font-semibold">Barangay</label>
            <div class="editable-field border border-gray-400 px-2 py-0.5 min-h-[24px]" data-field="barangay">
                {{ $application->barangay ?? '—' }}
            </div>
        </div>
        <div>
            <label class="block text-[12px] font-semibold">City/Municipality</label>
            <div class="editable-field border border-gray-400 px-2 py-0.5 min-h-[24px]" data-field="city">
                {{ $application->city ?? '—' }}
            </div>
        </div>
        <div>
            <label class="block text-[12px] font-semibold">Province</label>
            <div class="editable-field border border-gray-400 px-2 py-0.5 min-h-[24px]" data-field="province">
                {{ $application->province ?? '—' }}
            </div>
        </div>
    </div>

    <!-- Row c -->
    <div class="grid grid-cols-6 gap-2 p-1.5">
        <div>
            <label class="block text-[12px] font-semibold">Zip Code</label>
            <div class="editable-field border border-gray-400 px-2 py-0.5 min-h-[24px]" data-field="zip_code">
                {{ $application->zip_code ?? '—' }}
            </div>
        </div>
        <div>
            <label class="block text-[12px] font-semibold">Region</label>
            <div class="editable-field border border-gray-400 px-2 py-0.5 min-h-[24px]" data-field="region" data-type="select-region">
                {{ $application->region ?? '—' }}
            </div>
        </div>
        <div>
            <label class="block text-[12px] font-semibold">District</label>
            <div class="editable-field border border-gray-400 px-2 py-0.5 min-h-[24px]" data-field="district">
                {{ $application->district ?? '—' }}
            </div>
        </div>
        <div>
            <label class="block text-[12px] font-semibold">Passport No.</label>
            <div class="editable-field border border-gray-400 px-2 py-0.5 min-h-[24px]" data-field="passport_no">
                {{ $application->passport_no ?? '—' }}
            </div>
        </div>
        <div class="col-span-2">
            <label class="block text-[12px] font-semibold">E-mail Address</label>
            <div class="editable-field border border-gray-400 px-2 py-0.5 min-h-[24px]" data-field="email_address">
                {{ $application->email_address ?? '—' }}
            </div>
        </div>
    </div>

    <!-- Row d -->
    <div class="p-1.5">
        <label class="block text-[12px] font-semibold">Current Mailing Address</label>
        <div class="editable-field border border-gray-400 px-2 py-0.5 min-h-[24px]" data-field="current_address">
            {{ $application->current_address ?? '—' }}
        </div>
    </div>

    <!-- Row e -->
    <div class="grid grid-cols-2 gap-2 p-1.5">
        <div>
            <label class="block text-[12px] font-semibold">Telephone Nos. (Landline/Mobile)</label>
            <div class="editable-field border border-gray-400 px-2 py-0.5 min-h-[24px]" data-field="telephone_nos">
                {{ $application->telephone_nos ?? '—' }}
            </div>
        </div>
        <div>
            <label class="block text-[12px] font-semibold">Alternate Contact No.</label>
            <div class="editable-field border border-gray-400 px-2 py-0.5 min-h-[24px]" data-field="alternate_contact">
                {{ $application->alternate_contact ?? '—' }}
            </div>
        </div>
    </div>

    <!-- Row f -->
    <div class="grid grid-cols-4 gap-2 p-1.5">
        <div>
            <label class="block text-[12px] font-semibold">Civil Status</label>
            <div class="editable-field border border-gray-400 px-2 py-0.5 min-h-[24px]" data-field="civil_status" data-type="select-civil">
                {{ $application->civil_status ?? '—' }}
            </div>
        </div>
        <div>
            <label class="block text-[12px] font-semibold">Date of Birth</label>
            <div class="editable-field border border-gray-400 px-2 py-0.5 min-h-[24px]" data-field="date_of_birth" data-type="date">
                {{ $application->date_of_birth ?? '—' }}
            </div>
        </div>
        <div>
            <label class="block text-[12px] font-semibold">Age</label>
            <div class="editable-field border border-gray-400 px-2 py-0.5 min-h-[24px]" data-field="age">
                {{ $application->age ?? '—' }}
            </div>
        </div>
        <div>
            <label class="block text-[12px] font-semibold">Sex</label>
            <div class="editable-field border border-gray-400 px-2 py-0.5 min-h-[24px]" data-field="sex" data-type="select-sex">
                {{ $application->sex ?? '—' }}
            </div>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-2 p-1.5">
        <div>
            <label class="block text-[12px] font-semibold">Father’s Name</label>
            <div class="editable-field border border-gray-400 px-2 py-0.5 min-h-[24px]" data-field="father_name">
                {{ $application->father_name ?? '—' }}
            </div>
        </div>
        <div>
            <label class="block text-[12px] font-semibold">Mother’s Name</label>
            <div class="editable-field border border-gray-400 px-2 py-0.5 min-h-[24px]" data-field="mother_name">
                {{ $application->mother_name ?? '—' }}
            </div>
        </div>
    </div>
</div>

<!-- II. EDUCATIONAL BACKGROUND -->
<h2 class="text-base font-semibold text-blue-700 mt-6 mb-3 border-b pb-1">II. EDUCATIONAL BACKGROUND</h2>
<table class="w-full border border-gray-400 text-sm text-gray-700">
    <thead class="bg-gray-100">
        <tr>
            <th class="border px-2 py-1 text-left">Level</th>
            <th class="border px-2 py-1 text-left">Period</th>
            <th class="border px-2 py-1 text-left">Field</th>
            <th class="border px-2 py-1 text-left">University/School</th>
            <th class="border px-2 py-1 text-left">Scholarship</th>
            <th class="border px-2 py-1 text-left">Remarks</th>
        </tr>
    </thead>
    <tbody>
        @foreach(['bs', 'ms', 'phd'] as $level)
        <tr>
            <td class="border px-2 py-1">{{ strtoupper($level) }}</td>
            <td class="border px-2 py-1 editable-field" data-field="{{ $level }}_period">
                {{ $application->{$level.'_period'} ?? '—' }}
            </td>
            <td class="border px-2 py-1 editable-field" data-field="{{ $level }}_field">
                {{ $application->{$level.'_field'} ?? '—' }}
            </td>
            <td class="border px-2 py-1 editable-field" data-field="{{ $level }}_university">
                {{ $application->{$level.'_university'} ?? '—' }}
            </td>
            <td class="border px-2 py-1 editable-field" data-field="{{ $level }}_scholarship_type">
                @if(is_array($application->{$level.'_scholarship_type'}))
                    {{ implode(', ', $application->{$level.'_scholarship_type'}) }}
                @else
                    {{ $application->{$level.'_scholarship_type'} ?? '—' }}
                @endif
            </td>
            <td class="border px-2 py-1 editable-field" data-field="{{ $level }}_remarks">
                {{ $application->{$level.'_remarks'} ?? '—' }}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

            <!-- III. GRADUATE SCHOLARSHIP INTENTIONS -->
<h2 class="text-base font-semibold text-blue-700 mt-6 mb-3 border-b pb-1">
    III. GRADUATE SCHOLARSHIP INTENTIONS DATA
</h2>

<!-- Notes -->
<div class="text-[13px] text-gray-700 mb-2">
    <p class="italic"><strong>Notes:</strong></p>
    <ol class="list-decimal ml-5 space-y-1">
        <li>
            An applicant for a graduate program should elect to go to another university if he/she earned his/her
            1<sup>st</sup> (BS) and/or 2<sup>nd</sup> (MS) degrees from the same university to avoid inbreeding.
        </li>
        <li>
            A faculty-applicant for a graduate program should elect to go to any of the member universities of the
            ASTHRDP, ERDT, or CBPSME Consortium, or to a foreign university with a good track record and/or recognized
            higher education/institution in the specialized field in S&T to be pursued.
        </li>
    </ol>
</div>

<!-- Editable Form -->
<div class="border border-gray-400 rounded text-[13px] text-gray-800 p-3 leading-tight">

    <!-- Header -->
    <div class="grid grid-cols-3 gap-2 text-center font-semibold border-b border-gray-400 pb-1 mb-2">
        <div>STRAND CATEGORY</div>
        <div>TYPE OF APPLICANT<br><span class="font-normal text-xs">(for STRAND 2 only)</span></div>
        <div>TYPE OF SCHOLARSHIP APPLIED FOR</div>
    </div>

    <!-- Editable Options -->
    <div class="grid grid-cols-3 gap-2 mb-3">
        <div class="editable-field border border-gray-400 p-1 text-center" data-field="strand_category" data-type="select-strand">
            {{ $application->strand_category ?? '—' }}
        </div>

        <div class="editable-field border border-gray-400 p-1 text-center" data-field="applicant_type" data-type="select-applicant-type">
            {{ $application->applicant_type ?? '—' }}
        </div>

        <div class="editable-field border border-gray-400 p-1 text-center" data-field="scholarship_type" data-type="select-scholarship-type">
            {{ $application->scholarship_type ?? '—' }}
        </div>
    </div>

    <!-- New Applicant -->
    <p class="font-semibold border-t border-gray-400 pt-1 mt-2">New Applicant</p>
    <div class="grid grid-cols-4 gap-2">
        <p class="col-span-3"><strong>a.</strong> University where you applied/intend to enroll for graduate studies:</p>
        <div class="editable-field border border-gray-400 col-span-4 p-1" data-field="new_applicant_university">
            {{ $application->new_applicant_university ?? '—' }}
        </div>

        <p><strong>b.</strong> Course/Degree:</p>
        <div class="editable-field border border-gray-400 col-span-3 p-1" data-field="new_applicant_course">
            {{ $application->new_applicant_course ?? '—' }}
        </div>
    </div>

    <!-- Lateral Applicant -->
    <p class="font-semibold border-t border-gray-400 pt-1 mt-2">Lateral Applicant</p>
    <div class="grid grid-cols-4 gap-2">
        <p><strong>a.</strong> University enrolled in:</p>
        <div class="editable-field border border-gray-400 col-span-3 p-1" data-field="lateral_university_enrolled">
            {{ $application->lateral_university_enrolled ?? '—' }}
        </div>

        <p><strong>b.</strong> Course/Degree:</p>
        <div class="editable-field border border-gray-400 col-span-3 p-1" data-field="lateral_course_degree">
            {{ $application->lateral_course_degree ?? '—' }}
        </div>

        <p><strong>c.</strong> Number of units earned:</p>
        <div class="editable-field border border-gray-400 p-1" data-field="lateral_units_earned">
            {{ $application->lateral_units_earned ?? '—' }}
        </div>

        <p><strong>d.</strong> No. of remaining units/sems:</p>
        <div class="editable-field border border-gray-400 p-1" data-field="lateral_remaining_units">
            {{ $application->lateral_remaining_units ?? '—' }}
        </div>
    </div>
</div>


        <!-- ✅ PAGE 2 -->
        <div class="p-8 print-page">
<!-- IV. CAREER / EMPLOYMENT INFORMATION -->
<h2 class="text-base font-semibold text-blue-700 mt-6 mb-3 border-b pb-1">
    IV. CAREER / EMPLOYMENT INFORMATION
</h2>

<div class="border border-gray-400 text-[13px] text-gray-800 rounded-sm divide-y divide-gray-400 leading-snug print-serif">

    <!-- a. Present Employment Status -->
    <div class="p-1.5">
        <label class="block text-[12px] font-semibold mb-1">a. Present Employment Status</label>
        <div class="grid grid-cols-5 gap-2">
            @foreach (['Permanent', 'Contractual', 'Probationary', 'Self-employed', 'Unemployed'] as $status)
                <label class="flex items-center gap-1 text-[12px]">
                    <input type="radio" name="employment_status" value="{{ $status }}" 
                        {{ $application->employment_status == $status ? 'checked' : '' }} disabled>
                    <span>{{ $status }}</span>
                </label>
            @endforeach
        </div>
    </div>

    <!-- a.1 For those who are presently employed -->
    <div class="p-1.5">
        <label class="block text-[12px] font-semibold mb-1">a.1 For those who are presently employed*</label>

        <div class="grid grid-cols-12 gap-2 mb-1">
            <div class="col-span-2 text-[12px] font-semibold">Position</div>
            <div class="col-span-4 editable-field border border-gray-400 px-2 py-0.5 min-h-[24px]" data-field="employed_position">
                {{ $application->employed_position ?? '—' }}
            </div>

            <div class="col-span-3 text-[12px] font-semibold text-right">Length of Service</div>
            <div class="col-span-3 editable-field border border-gray-400 px-2 py-0.5 min-h-[24px]" data-field="employed_length_of_service">
                {{ $application->employed_length_of_service ?? '—' }}
            </div>

            <div class="col-span-3 text-[12px] font-semibold">Name of Company/Office</div>
            <div class="col-span-9 editable-field border border-gray-400 px-2 py-0.5 min-h-[24px]" data-field="employed_company_name">
                {{ $application->employed_company_name ?? '—' }}
            </div>

            <div class="col-span-3 text-[12px] font-semibold">Address of Company/Office</div>
            <div class="col-span-9 editable-field border border-gray-400 px-2 py-0.5 min-h-[24px]" data-field="employed_company_address">
                {{ $application->employed_company_address ?? '—' }}
            </div>

            <div class="col-span-1 text-[12px] font-semibold">Email</div>
            <div class="col-span-5 editable-field border border-gray-400 px-2 py-0.5 min-h-[24px]" data-field="employed_email">
                {{ $application->employed_email ?? '—' }}
            </div>

            <div class="col-span-1 text-[12px] font-semibold">Website</div>
            <div class="col-span-5 editable-field border border-gray-400 px-2 py-0.5 min-h-[24px]" data-field="employed_website">
                {{ $application->employed_website ?? '—' }}
            </div>

            <div class="col-span-2 text-[12px] font-semibold">Telephone No.</div>
            <div class="col-span-4 editable-field border border-gray-400 px-2 py-0.5 min-h-[24px]" data-field="employed_telephone">
                {{ $application->employed_telephone ?? '—' }}
            </div>

            <div class="col-span-1 text-[12px] font-semibold">Fax No.</div>
            <div class="col-span-5 editable-field border border-gray-400 px-2 py-0.5 min-h-[24px]" data-field="employed_fax">
                {{ $application->employed_fax ?? '—' }}
            </div>
        </div>
    </div>

    <!-- a.2 For those who are self-employed -->
    <div class="p-1.5">
        <label class="block text-[12px] font-semibold mb-1">a.2 For those who are self-employed</label>

        <div class="grid grid-cols-12 gap-2 mb-1">
            <div class="col-span-2 text-[12px] font-semibold">Business Name</div>
            <div class="col-span-10 editable-field border border-gray-400 px-2 py-0.5 min-h-[24px]" data-field="self_employed_business_name">
                {{ $application->self_employed_business_name ?? '—' }}
            </div>

            <div class="col-span-2 text-[12px] font-semibold">Address</div>
            <div class="col-span-10 editable-field border border-gray-400 px-2 py-0.5 min-h-[24px]" data-field="self_employed_address">
                {{ $application->self_employed_address ?? '—' }}
            </div>

            <div class="col-span-2 text-[12px] font-semibold">Email/Website</div>
            <div class="col-span-3 editable-field border border-gray-400 px-2 py-0.5 min-h-[24px]" data-field="self_employed_email_website">
                {{ $application->self_employed_email_website ?? '—' }}
            </div>

            <div class="col-span-2 text-[12px] font-semibold">Telephone No.</div>
            <div class="col-span-3 editable-field border border-gray-400 px-2 py-0.5 min-h-[24px]" data-field="self_employed_telephone">
                {{ $application->self_employed_telephone ?? '—' }}
            </div>

            <div class="col-span-1 text-[12px] font-semibold">Fax No.</div>
            <div class="col-span-1 editable-field border border-gray-400 px-2 py-0.5 min-h-[24px]" data-field="self_employed_fax">
                {{ $application->self_employed_fax ?? '—' }}
            </div>

            <div class="col-span-2 text-[12px] font-semibold">Type of Business</div>
            <div class="col-span-4 editable-field border border-gray-400 px-2 py-0.5 min-h-[24px]" data-field="self_employed_type_of_business">
                {{ $application->self_employed_type_of_business ?? '—' }}
            </div>

            <div class="col-span-2 text-[12px] font-semibold">Years of Operation</div>
            <div class="col-span-4 editable-field border border-gray-400 px-2 py-0.5 min-h-[24px]" data-field="self_employed_years_of_operation">
                {{ $application->self_employed_years_of_operation ?? '—' }}
            </div>
        </div>
    </div>

    <!-- Note -->
    <div class="p-1.5 text-[12px] italic">
        <span class="font-semibold">*Once accepted in the scholarship program,</span>
        the scholar must obtain permission to go on a Leave of Absence (LOA)
        from his/her employer and become a full-time student.
        The scholar must submit a letter from his/her employer approving the LOA.
    </div>
</div>

<!-- V. RESEARCH AND DEVELOPMENT INVOLVEMENT -->
<h2 class="text-base font-semibold text-blue-700 mt-6 mb-3 border-b pb-1">
    V. RESEARCH AND DEVELOPMENT INVOLVEMENT (Last 5 Years)
</h2>

<table class="w-full border border-gray-400 text-[13px] text-gray-800 mb-4 leading-snug">
    <thead class="bg-gray-100">
        <tr>
            <th class="border border-gray-400 px-2 py-1 text-left font-semibold text-[12px]">Field and Title of Research</th>
            <th class="border border-gray-400 px-2 py-1 text-left font-semibold text-[12px]">Location/Duration</th>
            <th class="border border-gray-400 px-2 py-1 text-left font-semibold text-[12px]">Fund Source</th>
            <th class="border border-gray-400 px-2 py-1 text-left font-semibold text-[12px]">Nature of Involvement</th>
        </tr>
    </thead>
    <tbody>
        @if(!empty($application->research_involvements) && is_array($application->research_involvements))
            @foreach($application->research_involvements as $index => $r)
                <tr>
                    <td class="border border-gray-400 px-2 py-1 editable-field" data-field="research_involvements[{{ $index }}][field_title]">
                        {{ $r['field_title'] ?? '—' }}
                    </td>
                    <td class="border border-gray-400 px-2 py-1 editable-field" data-field="research_involvements[{{ $index }}][location_duration]">
                        {{ $r['location_duration'] ?? '—' }}
                    </td>
                    <td class="border border-gray-400 px-2 py-1 editable-field" data-field="research_involvements[{{ $index }}][fund_source]">
                        {{ $r['fund_source'] ?? '—' }}
                    </td>
                    <td class="border border-gray-400 px-2 py-1 editable-field" data-field="research_involvements[{{ $index }}][nature_of_involvement]">
                        {{ $r['nature_of_involvement'] ?? '—' }}
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td class="border border-gray-400 px-2 py-1 text-center" colspan="4">— No records —</td>
            </tr>
        @endif
    </tbody>
</table>

<!-- VI. PUBLICATIONS -->
<h2 class="text-base font-semibold text-blue-700 mt-6 mb-3 border-b pb-1">
    VI. PUBLICATIONS (Last 5 Years)
</h2>

<table class="w-full border border-gray-400 text-[13px] text-gray-800 mb-4 leading-snug">
    <thead class="bg-gray-100">
        <tr>
            <th class="border border-gray-400 px-2 py-1 text-left font-semibold text-[12px]">Title of Article</th>
            <th class="border border-gray-400 px-2 py-1 text-left font-semibold text-[12px]">Name/Year of Publication</th>
            <th class="border border-gray-400 px-2 py-1 text-left font-semibold text-[12px]">Nature of Involvement</th>
        </tr>
    </thead>
    <tbody>
        @if(!empty($application->publications) && is_array($application->publications))
            @foreach($application->publications as $index => $p)
                <tr>
                    <td class="border border-gray-400 px-2 py-1 editable-field" data-field="publications[{{ $index }}][title]">
                        {{ $p['title'] ?? '—' }}
                    </td>
                    <td class="border border-gray-400 px-2 py-1 editable-field" data-field="publications[{{ $index }}][name_year]">
                        {{ $p['name_year'] ?? '—' }}
                    </td>
                    <td class="border border-gray-400 px-2 py-1 editable-field" data-field="publications[{{ $index }}][nature_of_involvement]">
                        {{ $p['nature_of_involvement'] ?? '—' }}
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="3" class="border border-gray-400 px-2 py-1 text-center">— No records —</td>
            </tr>
        @endif
    </tbody>
</table>

<!-- VII. AWARDS -->
<h2 class="text-base font-semibold text-blue-700 mt-6 mb-3 border-b pb-1">
    VII. AWARDS RECEIVED
</h2>

<table class="w-full border border-gray-400 text-[13px] text-gray-800 mb-4 leading-snug">
    <thead class="bg-gray-100">
        <tr>
            <th class="border border-gray-400 px-2 py-1 text-left font-semibold text-[12px]">Title of Award</th>
            <th class="border border-gray-400 px-2 py-1 text-left font-semibold text-[12px]">Award Giving Body</th>
            <th class="border border-gray-400 px-2 py-1 text-left font-semibold text-[12px]">Year</th>
        </tr>
    </thead>
    <tbody>
        @if(!empty($application->awards) && is_array($application->awards))
            @foreach($application->awards as $index => $a)
                <tr>
                    <td class="border border-gray-400 px-2 py-1 editable-field" data-field="awards[{{ $index }}][title]">
                        {{ $a['title'] ?? '—' }}
                    </td>
                    <td class="border border-gray-400 px-2 py-1 editable-field" data-field="awards[{{ $index }}][giving_body]">
                        {{ $a['giving_body'] ?? '—' }}
                    </td>
                    <td class="border border-gray-400 px-2 py-1 editable-field" data-field="awards[{{ $index }}][year]">
                        {{ $a['year'] ?? '—' }}
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="3" class="border border-gray-400 px-2 py-1 text-center">— No records —</td>
            </tr>
        @endif
    </tbody>
</table>


            <!-- VIII. DECLARATION -->
            <h2 class="text-base font-semibold text-blue-700 mt-6 mb-3 border-b pb-1">VIII. TRUTHFULNESS OF DATA AND DATA PRIVACY</h2>
            <p class="text-sm text-gray-700 mb-4">
                I hereby certify that all information given above are true and correct to the best of my knowledge.
                Any misinformation or withholding of information will automatically disqualify me from the program.
                Moreover, I authorize DOST-SEI to process my personal data as stated in the Data Privacy Act of 2012.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm text-gray-700">
                               <p><strong>Applicant Name:</strong> 
                    {{ trim(($application->first_name ?? '') . ' ' . ($application->middle_name ?? '') . ' ' . ($application->last_name ?? '')) ?: '—' }}
                </p>
                <p><strong>Applicant Signature:</strong> {{ $application->applicant_signature ?? '—' }}</p>
                <p><strong>Date:</strong> {{ $application->declaration_date ?? '—' }}</p>
            </div>

         <!-- FOOTER: ATTACHMENTS SECTION (Editable) -->
<div class="mt-10 border border-gray-400 rounded p-4 text-xs text-gray-800 leading-relaxed print-serif">
    <p class="font-semibold underline text-center mb-2">ATTACHED DOCUMENTS</p>

    <p class="mb-2 text-justify">
        The following documents are required as part of your application. You can view or replace uploaded files below.
    </p>

    <ul class="list-disc ml-6 space-y-3">
        @php
            $attachments = [
                'form_a_research_plans_pdf' => 'Form A – Research Plans',
                'form_b_career_plans_pdf' => 'Form B – Career Plans',
                'form_c_health_status_pdf' => 'Form C – Certification of Health Status',
                'nbi_clearance_pdf' => 'NBI Clearance',
                'transcript_of_record_pdf' => 'Transcript of Records',
                'birth_certificate_pdf' => 'Birth Certificate',
                'endorsement_1_pdf' => 'Endorsement 1',
                'endorsement_2_pdf' => 'Endorsement 2',
            ];
        @endphp

        @foreach($attachments as $field => $label)
            <li>
                <strong>{{ $label }}:</strong>
                @if(!empty($application->$field))
                    <a href="{{ asset('storage/' . $application->$field) }}" target="_blank" class="text-blue-600 underline">View File</a>
                @else
                    <span class="text-gray-500">Not uploaded</span>
                @endif

                <!-- File upload field for replacement -->
                <div class="mt-1">
                    <input type="file" 
                           name="{{ $field }}" 
                           accept="application/pdf" 
                           class="text-[11px] border border-gray-300 p-1 w-full cursor-pointer">
                </div>
            </li>
        @endforeach
    </ul>
</div>



            <div class="mt-8 print:hidden">
                <a href="{{ route('applicant.dashboard') }}" 
                   class="text-blue-600 hover:underline text-sm">
                    ← Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", () => {
    const editBtn = document.getElementById("toggleEditBtn");
    const fields = document.querySelectorAll(".editable-field");
    let editMode = false;

    editBtn.addEventListener("click", () => {
        editMode = !editMode;
        if (editMode) {
            editBtn.textContent = "💾 Save Changes";
            enableEditMode();
        } else {
            saveChanges();
        }
    });

    function enableEditMode() {
        fields.forEach(div => {
            const value = div.textContent.trim() === "—" ? "" : div.textContent.trim();
            const field = div.dataset.field;
            const type = div.dataset.type || "text";
            let input = "";

            if (type === "select-sex") {
                input = `
                    <select name="${field}" class="w-full border px-2 py-1 rounded text-sm">
                        <option value="">Select</option>
                        <option value="Male" ${value === "Male" ? "selected" : ""}>Male</option>
                        <option value="Female" ${value === "Female" ? "selected" : ""}>Female</option>
                    </select>`;
            } else if (type === "select-civil") {
                const options = ["Single", "Married", "Separated", "Widowed"];
                input = `<select name="${field}" class="w-full border px-2 py-1 rounded text-sm">
                    <option value="">Select</option>
                    ${options.map(opt => `<option value="${opt}" ${value === opt ? "selected" : ""}>${opt}</option>`).join("")}
                </select>`;
            } else if (type === "select-region") {
                const regions = ["Region I", "Region II", "Region III", "Region IV-A", "Region IV-B", "Region V", "Region VI", "Region VII", "Region VIII", "Region IX", "Region X", "Region XI", "Region XII", "CARAGA", "BARMM"];
                input = `<select name="${field}" class="w-full border px-2 py-1 rounded text-sm">
                    <option value="">Select</option>
                    ${regions.map(r => `<option value="${r}" ${value === r ? "selected" : ""}>${r}</option>`).join("")}
                </select>`;
            }

            // ✅ Added for Graduate Scholarship Intention Section
            else if (type === "select-strand") {
                const options = ["STRAND 1", "STRAND 2"];
                input = `<select name="${field}" class="w-full border px-2 py-1 rounded text-sm">
                    <option value="">Select</option>
                    ${options.map(opt => `<option value="${opt}" ${value === opt ? "selected" : ""}>${opt}</option>`).join("")}
                </select>`;
            } else if (type === "select-applicant-type") {
                const options = ["Student", "Faculty"];
                input = `<select name="${field}" class="w-full border px-2 py-1 rounded text-sm">
                    <option value="">Select</option>
                    ${options.map(opt => `<option value="${opt}" ${value === opt ? "selected" : ""}>${opt}</option>`).join("")}
                </select>`;
            } else if (type === "select-scholarship-type") {
                const options = ["MS", "PhD"];
                input = `<select name="${field}" class="w-full border px-2 py-1 rounded text-sm">
                    <option value="">Select</option>
                    ${options.map(opt => `<option value="${opt}" ${value === opt ? "selected" : ""}>${opt}</option>`).join("")}
                </select>`;
            }

            // ✅ Added for Career / Employment Information Section
            else if (type === "select-employment-status") {
                const options = ["Permanent", "Contractual", "Probationary", "Self-employed", "Unemployed"];
                input = `<select name="${field}" class="w-full border px-2 py-1 rounded text-sm">
                    <option value="">Select Employment Status</option>
                    ${options.map(opt => `<option value="${opt}" ${value === opt ? "selected" : ""}>${opt}</option>`).join("")}
                </select>`;
            }

            else if (type === "date") {
                input = `<input type="date" name="${field}" value="${value}" class="w-full border px-2 py-1 rounded text-sm"/>`;
            } else {
                input = `<input type="text" name="${field}" value="${value}" class="w-full border px-2 py-1 rounded text-sm"/>`;
            }

            div.innerHTML = input;
        });
    }

    function saveChanges() {
        const formData = {};
        fields.forEach(div => {
            const input = div.querySelector("input, select");
            if (input) formData[input.name] = input.value;
        });

        fetch("{{ route('application.update', $application->id ?? 0) }}", {
            method: "PATCH",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify(formData)
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert("✅ Changes saved successfully!");
                editBtn.textContent = "✏️ Edit Form";
                editMode = false;
                fields.forEach(div => {
                    const field = div.dataset.field;
                    div.innerHTML = formData[field] || "—";
                });
            } else {
                alert("⚠️ Failed to save changes.");
            }
        })
        .catch(err => {
            alert("❌ Error saving changes.");
            console.error(err);
        });
    }
});
</script>

</x-app-layout>
