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
                    @if($application->status === 'pending')
                        <a href="{{ route('applicant.application.edit', ['id' => $application->application_form_id]) }}"
                            class="inline-block mt-2 bg-yellow-500 hover:bg-yellow-600 text-white text-xs px-3 py-1 rounded print:hidden">
                            ✏️ Edit Application
                        </a>
                    @endif
                </div>
            </div>

           <!-- BUTTON -->
<div class="flex justify-end gap-2 mb-6 print:hidden">
    <button 
        onclick="window.print()" 
        class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded-md">
        🖨️ Print Form
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
                        <div class="border border-gray-400 px-2 py-0.5 min-h-[24px]">
                            {{ $application->last_name ?? '—' }}
                        </div>
                    </div>
                    <div>
                        <label class="block text-[12px] font-semibold">First Name</label>
                        <div class="border border-gray-400 px-2 py-0.5 min-h-[24px]">
                            {{ $application->first_name ?? '—' }}
                        </div>
                    </div>
                    <div>
                        <label class="block text-[12px] font-semibold">Middle Name</label>
                        <div class="border border-gray-400 px-2 py-0.5 min-h-[24px]">
                            {{ $application->middle_name ?? '—' }}
                        </div>
                    </div>
                </div>

                <!-- Row b -->
                <div class="grid grid-cols-6 gap-2 p-1.5">
                    <div class="col-span-2">
                        <label class="block text-[12px] font-semibold">Permanent Address</label>
                        <div class="border border-gray-400 px-2 py-0.5 min-h-[24px]">
                            {{ $application->address_no ?? '—' }}
                        </div>
                    </div>
                    <div>
                        <label class="block text-[12px] font-semibold">Street</label>
                        <div class="border border-gray-400 px-2 py-0.5 min-h-[24px]">
                            {{ $application->address_street ?? '—' }}
                        </div>
                    </div>
                    <div>
                        <label class="block text-[12px] font-semibold">Barangay</label>
                        <div class="border border-gray-400 px-2 py-0.5 min-h-[24px]">
                            {{ $application->barangay ?? '—' }}
                        </div>
                    </div>
                    <div>
                        <label class="block text-[12px] font-semibold">City/Municipality</label>
                        <div class="border border-gray-400 px-2 py-0.5 min-h-[24px]">
                            {{ $application->city ?? '—' }}
                        </div>
                    </div>
                    <div>
                        <label class="block text-[12px] font-semibold">Province</label>
                        <div class="border border-gray-400 px-2 py-0.5 min-h-[24px]">
                            {{ $application->province ?? '—' }}
                        </div>
                    </div>
                </div>

                <!-- Row c -->
                <div class="grid grid-cols-6 gap-2 p-1.5">
                    <div>
                        <label class="block text-[12px] font-semibold">Zip Code</label>
                        <div class="border border-gray-400 px-2 py-0.5 min-h-[24px]">
                            {{ $application->zip_code ?? '—' }}
                        </div>
                    </div>
                    <div>
                        <label class="block text-[12px] font-semibold">Region</label>
                        <div class="border border-gray-400 px-2 py-0.5 min-h-[24px]">
                            {{ $application->region ?? '—' }}
                        </div>
                    </div>
                    <div>
                        <label class="block text-[12px] font-semibold">District</label>
                        <div class="border border-gray-400 px-2 py-0.5 min-h-[24px]">—</div>
                    </div>
                    <div>
                        <label class="block text-[12px] font-semibold">Passport No.</label>
                        <div class="border border-gray-400 px-2 py-0.5 min-h-[24px]">—</div>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-[12px] font-semibold">E-mail Address</label>
                        <div class="border border-gray-400 px-2 py-0.5 min-h-[24px]">
                            {{ $application->email_address ?? '—' }}
                        </div>
                    </div>
                </div>

                <!-- Row d -->
                <div class="p-1.5">
                    <label class="block text-[12px] font-semibold">Current Mailing Address</label>
                    <div class="border border-gray-400 px-2 py-0.5 min-h-[24px]">
                        {{ $application->current_address ?? '—' }}
                    </div>
                </div>

                <!-- Row e -->
                <div class="grid grid-cols-2 gap-2 p-1.5">
                    <div>
                        <label class="block text-[12px] font-semibold">Telephone Nos. (Landline/Mobile)</label>
                        <div class="border border-gray-400 px-2 py-0.5 min-h-[24px]">
                            {{ $application->telephone_nos ?? '—' }}
                        </div>
                    </div>
                    <div>
                        <label class="block text-[12px] font-semibold">Alternate Contact No.</label>
                        <div class="border border-gray-400 px-2 py-0.5 min-h-[24px]">—</div>
                    </div>
                </div>

                <!-- Row f -->
                <div class="grid grid-cols-4 gap-2 p-1.5">
                    <div>
                        <label class="block text-[12px] font-semibold">Civil Status</label>
                        <div class="border border-gray-400 px-2 py-0.5 min-h-[24px]">
                            {{ $application->civil_status ?? '—' }}
                        </div>
                    </div>
                    <div>
                        <label class="block text-[12px] font-semibold">Date of Birth</label>
                        <div class="border border-gray-400 px-2 py-0.5 min-h-[24px]">
                            {{ $application->date_of_birth ?? '—' }}
                        </div>
                    </div>
                    <div>
                        <label class="block text-[12px] font-semibold">Age</label>
                        <div class="border border-gray-400 px-2 py-0.5 min-h-[24px]">
                            {{ $application->age ?? '—' }}
                        </div>
                    </div>
                    <div>
                        <label class="block text-[12px] font-semibold">Sex</label>
                        <div class="border border-gray-400 px-2 py-0.5 min-h-[24px]">
                            {{ $application->sex ?? '—' }}
                        </div>
                    </div>
                </div>


                <div class="grid grid-cols-2 gap-2 p-1.5">
                    <div>
                        <label class="block text-[12px] font-semibold">Father’s Name</label>
                        <div class="border border-gray-400 px-2 py-0.5 min-h-[24px]">
                            {{ $application->father_name ?? '—' }}
                        </div>
                    </div>
                    <div>
                        <label class="block text-[12px] font-semibold">Mother’s Name</label>
                        <div class="border border-gray-400 px-2 py-0.5 min-h-[24px]">
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
                            <td class="border px-2 py-1">{{ $application->{$level.'_period'} ?? '—' }}</td>
                            <td class="border px-2 py-1">{{ $application->{$level.'_field'} ?? '—' }}</td>
                            <td class="border px-2 py-1">{{ $application->{$level.'_university'} ?? '—' }}</td>
                            <td class="border px-2 py-1">
                                @if(is_array($application->{$level.'_scholarship_type'}))
                                    {{ implode(', ', $application->{$level.'_scholarship_type'}) }}
                                @else
                                    {{ $application->{$level.'_scholarship_type'} ?? '—' }}
                                @endif
                            </td>
                            <td class="border px-2 py-1">{{ $application->{$level.'_remarks'} ?? '—' }}</td>
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

<!-- Form-style box -->
<div class="border border-gray-400 rounded text-[13px] text-gray-800 p-3 leading-tight">

    <!-- Header Row -->
    <div class="grid grid-cols-3 gap-2 text-center font-semibold border-b border-gray-400 pb-1 mb-2">
        <div>STRAND CATEGORY</div>
        <div>TYPE OF APPLICANT<br><span class="font-normal text-xs">(for STRAND 2 only)</span></div>
        <div>TYPE OF SCHOLARSHIP APPLIED FOR</div>
    </div>

    <!-- Checkboxes/Values -->
    <div class="grid grid-cols-3 gap-2 mb-3">
        <div>
            <div class="flex items-center gap-2">
                <input type="checkbox" disabled {{ $application->strand_category == 'STRAND 1' ? 'checked' : '' }}>
                <span>STRAND 1</span>
            </div>
            <div class="flex items-center gap-2">
                <input type="checkbox" disabled {{ $application->strand_category == 'STRAND 2' ? 'checked' : '' }}>
                <span>STRAND 2</span>
            </div>
        </div>

        <div>
            <div class="flex items-center gap-2">
                <input type="checkbox" disabled {{ $application->applicant_type == 'Student' ? 'checked' : '' }}>
                <span>Student</span>
            </div>
            <div class="flex items-center gap-2">
                <input type="checkbox" disabled {{ $application->applicant_type == 'Faculty' ? 'checked' : '' }}>
                <span>Faculty</span>
            </div>
        </div>

        <div>
            <div class="flex items-center gap-2">
                <input type="checkbox" disabled {{ $application->scholarship_type == 'MS' ? 'checked' : '' }}>
                <span>MS</span>
            </div>
            <div class="flex items-center gap-2">
                <input type="checkbox" disabled {{ $application->scholarship_type == 'PhD' ? 'checked' : '' }}>
                <span>PhD</span>
            </div>
        </div>
    </div>

    <!-- New Applicant -->
    <p class="font-semibold border-t border-gray-400 pt-1 mt-2">New Applicant</p>
    <div class="grid grid-cols-4 gap-2">
        <p class="col-span-3"><strong>a.</strong> University where you applied/intend to enroll for graduate studies:</p>
        <div class="border border-gray-400 col-span-4 p-1">
            {{ $application->new_applicant_university ?? '—' }}
        </div>
        <p><strong>b.</strong> Course/Degree:</p>
        <div class="border border-gray-400 col-span-3 p-1">
            {{ $application->new_applicant_course ?? '—' }}
        </div>
    </div>

    <!-- Lateral Applicant -->
    <p class="font-semibold border-t border-gray-400 pt-1 mt-2">Lateral Applicant</p>
    <div class="grid grid-cols-4 gap-2">
        <p><strong>a.</strong> University enrolled in:</p>
        <div class="border border-gray-400 col-span-3 p-1">
            {{ $application->lateral_university_enrolled ?? '—' }}
        </div>

        <p><strong>b.</strong> Course/Degree:</p>
        <div class="border border-gray-400 col-span-3 p-1">
            {{ $application->lateral_course_degree ?? '—' }}
        </div>

        <p><strong>c.</strong> Number of units earned:</p>
        <div class="border border-gray-400 p-1">
            {{ $application->lateral_units_earned ?? '—' }}
        </div>

        <p><strong>d.</strong> No. of remaining units/sems:</p>
        <div class="border border-gray-400 p-1">
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

<!-- Bordered box (exact same width and padding as Section III) -->
<div class="border border-gray-400 rounded text-[13px] text-gray-800 p-3 leading-tight" style="width: 100%; max-width: 860px; margin: 0 auto; box-sizing: border-box;">

    <!-- a. Present Employment Status -->
    <p class="font-semibold mb-1">a. Present Employment Status</p>
    <div class="grid grid-cols-5 gap-2 mb-3">
        <label class="flex items-center gap-1">
            <input type="checkbox" disabled {{ $application->employment_status == 'Permanent' ? 'checked' : '' }}>
            <span>Permanent</span>
        </label>
        <label class="flex items-center gap-1">
            <input type="checkbox" disabled {{ $application->employment_status == 'Contractual' ? 'checked' : '' }}>
            <span>Contractual</span>
        </label>
        <label class="flex items-center gap-1">
            <input type="checkbox" disabled {{ $application->employment_status == 'Probationary' ? 'checked' : '' }}>
            <span>Probationary</span>
        </label>
        <label class="flex items-center gap-1">
            <input type="checkbox" disabled {{ $application->employment_status == 'Self-employed' ? 'checked' : '' }}>
            <span>Self-employed</span>
        </label>
        <label class="flex items-center gap-1">
            <input type="checkbox" disabled {{ $application->employment_status == 'Unemployed' ? 'checked' : '' }}>
            <span>Unemployed</span>
        </label>
    </div>

    <!-- a.1 For those who are presently employed -->
    <p class="font-semibold border-t border-gray-400 pt-1 mt-2">a.1 For those who are presently employed*</p>

    <div class="grid grid-cols-12 gap-2 mb-2">
        <div class="col-span-2"><strong>Position:</strong></div>
        <div class="col-span-6 border border-gray-400 p-1">{{ $application->employed_position ?? '—' }}</div>
        <div class="col-span-2 text-right"><strong>Length of Service:</strong></div>
        <div class="col-span-2 border border-gray-400 p-1">{{ $application->employed_length_of_service ?? '—' }}</div>

        <div class="col-span-3"><strong>Name of Company/Office:</strong></div>
        <div class="col-span-9 border border-gray-400 p-1">{{ $application->employed_company_name ?? '—' }}</div>

        <div class="col-span-3"><strong>Address of Company/Office:</strong></div>
        <div class="col-span-9 border border-gray-400 p-1">{{ $application->employed_company_address ?? '—' }}</div>

        <div class="col-span-1"><strong>Email:</strong></div>
        <div class="col-span-5 border border-gray-400 p-1">{{ $application->employed_email ?? '—' }}</div>
        <div class="col-span-1"><strong>Website:</strong></div>
        <div class="col-span-5 border border-gray-400 p-1">{{ $application->employed_website ?? '—' }}</div>

        <div class="col-span-2"><strong>Telephone No.:</strong></div>
        <div class="col-span-4 border border-gray-400 p-1">{{ $application->employed_telephone ?? '—' }}</div>
        <div class="col-span-1"><strong>Fax No.:</strong></div>
        <div class="col-span-5 border border-gray-400 p-1">{{ $application->employed_fax ?? '—' }}</div>
    </div>

    <!-- a.2 For those who are self-employed -->
    <p class="font-semibold border-t border-gray-400 pt-1 mt-2">a.2 For those who are self-employed</p>

    <div class="grid grid-cols-12 gap-2 mb-2">
        <div class="col-span-2"><strong>Business Name:</strong></div>
        <div class="col-span-10 border border-gray-400 p-1">{{ $application->self_employed_business_name ?? '—' }}</div>

        <div class="col-span-2"><strong>Address:</strong></div>
        <div class="col-span-10 border border-gray-400 p-1">{{ $application->self_employed_address ?? '—' }}</div>

        <div class="col-span-2"><strong>Email/Website:</strong></div>
        <div class="col-span-3 border border-gray-400 p-1">{{ $application->self_employed_email_website ?? '—' }}</div>
        <div class="col-span-2"><strong>Telephone No.:</strong></div>
        <div class="col-span-3 border border-gray-400 p-1">{{ $application->self_employed_telephone ?? '—' }}</div>
        <div class="col-span-1"><strong>Fax No.:</strong></div>
        <div class="col-span-1 border border-gray-400 p-1">{{ $application->self_employed_fax ?? '—' }}</div>

        <div class="col-span-2"><strong>Type of Business:</strong></div>
        <div class="col-span-4 border border-gray-400 p-1">{{ $application->self_employed_type_of_business ?? '—' }}</div>
        <div class="col-span-2"><strong>Years of Operation:</strong></div>
        <div class="col-span-4 border border-gray-400 p-1">{{ $application->self_employed_years_of_operation ?? '—' }}</div>
    </div>

    <!-- Note -->
    <div class="mt-3 text-[12px] italic border-t border-gray-300 pt-2">
        <span class="font-semibold">*Once accepted in the scholarship program,</span>
        the scholar must obtain permission to go on a Leave of Absence (LOA)
        from his/her employer and become a full-time student.
        The scholar must submit a letter from his/her employer approving the LOA.
    </div>
</div>


            <!-- V. RESEARCH AND DEVELOPMENT INVOLVEMENT -->
            <h2 class="text-base font-semibold text-blue-700 mt-6 mb-3 border-b pb-1">V. RESEARCH AND DEVELOPMENT INVOLVEMENT (Last 5 Years)</h2>
            <table class="w-full border border-gray-400 text-sm text-gray-700 mb-4">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-2 py-1 text-left">Field and Title of Research</th>
                        <th class="border px-2 py-1 text-left">Location/Duration</th>
                        <th class="border px-2 py-1 text-left">Fund Source</th>
                        <th class="border px-2 py-1 text-left">Nature of Involvement</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($application->research_involvements ?? [] as $r)
                        <tr>
                            <td class="border px-2 py-1">{{ $r['field_title'] ?? '—' }}</td>
                            <td class="border px-2 py-1">{{ $r['location_duration'] ?? '—' }}</td>
                            <td class="border px-2 py-1">{{ $r['fund_source'] ?? '—' }}</td>
                            <td class="border px-2 py-1">{{ $r['nature_of_involvement'] ?? '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- VI. PUBLICATIONS -->
            <h2 class="text-base font-semibold text-blue-700 mt-6 mb-3 border-b pb-1">VI. PUBLICATIONS (Last 5 Years)</h2>
            <table class="w-full border border-gray-400 text-sm text-gray-700 mb-4">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-2 py-1 text-left">Title of Article</th>
                        <th class="border px-2 py-1 text-left">Name/Year of Publication</th>
                        <th class="border px-2 py-1 text-left">Nature of Involvement</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($application->publications ?? [] as $p)
                        <tr>
                            <td class="border px-2 py-1">{{ $p['title'] ?? '—' }}</td>
                            <td class="border px-2 py-1">{{ $p['name_year'] ?? '—' }}</td>
                            <td class="border px-2 py-1">{{ $p['nature_of_involvement'] ?? '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- VII. AWARDS -->
            <h2 class="text-base font-semibold text-blue-700 mt-6 mb-3 border-b pb-1">VII. AWARDS RECEIVED</h2>
            <table class="w-full border border-gray-400 text-sm text-gray-700 mb-4">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-2 py-1 text-left">Title of Award</th>
                        <th class="border px-2 py-1 text-left">Award Giving Body</th>
                        <th class="border px-2 py-1 text-left">Year</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($application->awards ?? [] as $a)
                        <tr>
                            <td class="border px-2 py-1">{{ $a['title'] ?? '—' }}</td>
                            <td class="border px-2 py-1">{{ $a['giving_body'] ?? '—' }}</td>
                            <td class="border px-2 py-1">{{ $a['year'] ?? '—' }}</td>
                        </tr>
                    @endforeach
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

            <!-- FOOTER: ATTACHMENTS SECTION -->
<div class="mt-10 border border-gray-400 rounded p-4 text-xs text-gray-800 leading-relaxed print-serif">
    <p class="font-semibold underline text-center mb-2">ATTACHED DOCUMENTS</p>

    <p class="mb-2 text-justify">
        The following documents are required as part of your application. Click to view or download.
    </p>

    <ul class="list-disc ml-6 space-y-1">
        <li>
            Form A – Research Plans:
            @if(!empty($application->form_a_research_plans_pdf))
                <a href="{{ asset('storage/' . $application->form_a_research_plans_pdf) }}" target="_blank" class="text-blue-600 underline">View File</a>
            @else
                <span class="text-gray-500">Not uploaded</span>
            @endif
        </li>

        <li>
            Form B – Career Plans:
            @if(!empty($application->form_b_career_plans_pdf))
                <a href="{{ asset('storage/' . $application->form_b_career_plans_pdf) }}" target="_blank" class="text-blue-600 underline">View File</a>
            @else
                <span class="text-gray-500">Not uploaded</span>
            @endif
        </li>

        <li>
            Form C – Certification of Health Status:
            @if(!empty($application->form_c_health_status_pdf))
                <a href="{{ asset('storage/' . $application->form_c_health_status_pdf) }}" target="_blank" class="text-blue-600 underline">View File</a>
            @else
                <span class="text-gray-500">Not uploaded</span>
            @endif
        </li>

        <li>
            NBI Clearance:
            @if(!empty($application->nbi_clearance_pdf))
                <a href="{{ asset('storage/' . $application->nbi_clearance_pdf) }}" target="_blank" class="text-blue-600 underline">View File</a>
            @else
                <span class="text-gray-500">Not uploaded</span>
            @endif
        </li>

        <li>
            Transcript of Records:
            @if(!empty($application->transcript_of_record_pdf))
                <a href="{{ asset('storage/' . $application->transcript_of_record_pdf) }}" target="_blank" class="text-blue-600 underline">View File</a>
            @else
                <span class="text-gray-500">Not uploaded</span>
            @endif
        </li>

        <li>
            Birth Certificate:
            @if(!empty($application->birth_certificate_pdf))
                <a href="{{ asset('storage/' . $application->birth_certificate_pdf) }}" target="_blank" class="text-blue-600 underline">View File</a>
            @else
                <span class="text-gray-500">Not uploaded</span>
            @endif
        </li>

        <li>
            Endorsement 1:
            @if(!empty($application->endorsement_1_pdf))
                <a href="{{ asset('storage/' . $application->endorsement_1_pdf) }}" target="_blank" class="text-blue-600 underline">View File</a>
            @else
                <span class="text-gray-500">Not uploaded</span>
            @endif
        </li>

        <li>
            Endorsement 2:
            @if(!empty($application->endorsement_2_pdf))
                <a href="{{ asset('storage/' . $application->endorsement_2_pdf) }}" target="_blank" class="text-blue-600 underline">View File</a>
            @else
                <span class="text-gray-500">Not uploaded</span>
            @endif
        </li>
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
</x-app-layout>
