@extends('layouts.admin-layout')

@section('content')
    <style>
        @media print {
            @page {
                size: A4 landscape;
                margin: 1cm;
            }

            body {
                font-size: 12px;
            }

            .no-print {
                display: none !important;
            }

            .print-header {
                display: block !important;
                text-align: center;
                margin-bottom: 8px;
            }

            table {
                border-collapse: collapse !important;
                width: 100% !important;
            }

            th,
            td {
                border: 1px solid #000 !important;
                padding: 6px !important;
                vertical-align: middle;
            }
        }

        .print-header {
            display: none;
        }

        .col-hidden {
            display: none !important;
        }
    </style>

    <div class="container mx-auto px-4 py-6">
        <!-- Header Card -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-3xl font-bold text-gray-900">CHED Monitoring Scholars</h1>

                <div class="no-print flex gap-2">
                    <button id="printBtn"
                        class="bg-green-600 hover:bg-green-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition shadow-sm flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Download
                    </button>

                    <button id="resetCols"
                        class="bg-red-600 hover:bg-red-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition shadow-sm flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                            </path>
                        </svg>
                        Reset
                    </button>
                </div>
            </div>

            <!-- Filters Section -->
            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <form id="filtersForm" method="GET" action="{{ route('admin.reports.ched-monitoring') }}">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">

                        <!-- Semester -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Semester</label>
                            <select name="semester"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="" {{ request('semester') == '' ? 'selected' : '' }}>All Semesters
                                </option>
                                <option value="1st Semester" {{ request('semester') == '1st Semester' ? 'selected' : '' }}>
                                    1st Semester</option>
                                <option value="2nd Semester" {{ request('semester') == '2nd Semester' ? 'selected' : '' }}>
                                    2nd Semester</option>
                            </select>
                        </div>

                        <!-- Academic Year -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Academic Year</label>
                            <select name="academic_year"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="" {{ request('academic_year') == '' ? 'selected' : '' }}>All Years
                                </option>
                                <option value="2024-2025" {{ request('academic_year') == '2024-2025' ? 'selected' : '' }}>
                                    2024-2025</option>
                                <option value="2025-2026" {{ request('academic_year') == '2025-2026' ? 'selected' : '' }}>
                                    2025-2026</option>
                                <option value="2026-2027" {{ request('academic_year') == '2026-2027' ? 'selected' : '' }}>
                                    2026-2027</option>
                            </select>
                        </div>

                        <!-- Filter Button -->
                        <div class="flex items-end">
                            <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-8 py-2.5 rounded-lg transition shadow-sm">
                                Filter
                            </button>
                        </div>

                    </div>
                </form>
            </div>

            <!-- Select View Tabs -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-3">Select View:</label>

                <div class="flex gap-2 flex-wrap">
                    <button id="viewPersonal"
                        class="view-tab px-6 py-2 rounded-lg font-semibold transition bg-blue-600 text-white">
                        Personal Information
                    </button>

                    <button id="viewGradereport"
                        class="view-tab px-6 py-2 rounded-lg font-semibold transition bg-gray-200 text-gray-700 hover:bg-gray-300">
                        SIKAP DHEI Grade Report
                    </button>

                    <button id="viewEnrollment"
                        class="view-tab px-6 py-2 rounded-lg font-semibold transition bg-gray-200 text-gray-700 hover:bg-gray-300">
                        SIKAP DHEI Enrollment Report
                    </button>

                    <button id="viewContinuing"
                        class="view-tab px-6 py-2 rounded-lg font-semibold transition bg-gray-200 text-gray-700 hover:bg-gray-300">
                        SIKAP Continuing Eligibility Report
                    </button>
                </div>
            </div>

            {{-- Field Selection Section --}}
            <div class="bg-gray-50 rounded-lg p-4 mb-6 no-print">
                <label class="block text-sm font-medium text-gray-700 mb-3">Select Fields to Display</label>

                @php
                    $personalCols = [
                        'no' => 'No.',
                        'last_name' => 'Last Name',
                        'first_name' => 'First Name',
                        'middle_name' => 'Middle Name',
                        'suffix' => 'Suffix',
                        'street' => 'Street',
                        'barangay' => 'Village',
                        'city' => 'Town',
                        'province' => 'Province',
                        'zip_code' => 'Zipcode',
                        'district' => 'District',
                        'region' => 'Region',
                        'email' => 'Email',
                        'date_of_birth' => 'Birthday',
                        'contact_no' => 'Contact No.',
                        'sex' => 'Gender',
                        'age' => 'Age',
                    ];

                    $gradeReportCols = [
                        'gr_no' => 'No.',
                        'gr_name' => 'Name',
                        'application_no' => 'Application Number',
                        'gr_degree_program' => 'Degree Program',
                        'gr_enrolled_subjects' => 'Enrolled Subjects',
                        'gr_subjects_passed' => 'Subjects Passed',
                        'gr_incomplete_grades' => 'Incomplete Grades',
                        'gr_subjects_failed' => 'Subjects Failed',
                        'gr_no_grades' => 'No Grades',
                        'gr_not_credited' => 'Not Credited Subjects',
                        'gr_status' => 'Status',
                        'gr_gpa' => 'Grade Point Average',
                        'gr_remarks' => 'Remarks',
                    ];
                @endphp
                @php
                    // Enrollment Report Tables
                    $enrollmentACols = [
                        'enr_a_no' => 'No.',
                        'enr_a_name' => 'Name',
                        'enr_a_application_number' => 'Application Number',
                        'enr_a_degree_program' => 'Degree Program',
                        'enr_a_status' => 'Status (Units/Residency/Others)',
                        'enr_a_units_enrolled' => 'Units Enrolled',
                        'enr_a_retaken_subjects' => 'Retaken Subjects?',
                        'enr_a_remarks' => 'Remarks',
                    ];

                    $enrollmentBCols = [
                        'enr_b_no' => 'No.',
                        'enr_b_name' => 'Name',
                        'enr_b_application_number' => 'Application Number',
                        'enr_b_degree_program' => 'Degree Program',
                        'enr_b_status' => 'Status (Extension/Retaken/Withdrew/Others)',
                        'enr_b_others_status' => 'If Others, State Status',
                        'enr_b_description' => 'Short Description of Status',
                    ];

                    $enrollmentCCols = [
                        'enr_c_no' => 'No.',
                        'enr_c_name' => 'Name',
                        'enr_c_application_number' => 'Application Number',
                        'enr_c_degree_program' => 'Degree Program',
                        'enr_c_status' => 'Status (LOA, AWOL, Did Not Enroll, Others)',
                        'enr_c_others_status' => 'If Others, State Status',
                        'enr_c_description' => 'Short Description of Status',
                    ];

                    $enrollmentDCols = [
                        'enr_d_no' => 'No.',
                        'enr_d_name' => 'Name',
                        'enr_d_application_number' => 'Application Number',
                        'enr_d_degree_program' => 'Degree Program',
                        'enr_d_status' => 'Status (Ineligible/Graduated/Withdrawn/Others)',
                        'enr_d_others_status' => 'If Others, State Status',
                        'enr_d_description' => 'Short Description of Status',
                    ];
                @endphp
                @php
                    // Continuing Eligibility Report Tables
                    $continuingACols = [
                        'cont_a_name' => 'Name of Scholar',
                        'cont_a_application_number' => 'Application Number',
                        'cont_a_scholarship_type' => 'Scholarship Type',
                        'cont_a_degree_program' => 'Degree Program (no abbreviation)',
                        'cont_a_year_approval' => 'Year of Approval',
                        'cont_a_last_term' => 'Last Term of Enrollment',
                        'cont_a_good_standing' => 'Good Academic Standing?',
                        'cont_a_standing_explanation' => 'If NOT, Explanation',
                        'cont_a_finish_on_time' => 'Finish On Time?',
                        'cont_a_finish_explanation' => 'If NOT, Why?',
                        'cont_a_recommendation' => 'Recommendation',
                        'cont_a_rationale' => 'Rationale',
                    ];

                    $continuingBCols = [
                        'cont_b_no' => 'No.',
                        'cont_b_name' => 'Name of Scholar',
                        'cont_b_application_number' => 'Application Number',
                        'cont_b_scholarship_type' => 'Scholarship Type',
                        'cont_b_degree_program' => 'Degree Program (no abbreviation)',
                        'cont_b_academic_year' => 'Academic Year of Graduation',
                        'cont_b_term_graduation' => 'Term of Graduation',
                        'cont_b_remarks' => 'Remarks',
                    ];
                @endphp

                {{-- PERSONAL INFORMATION FIELDS --}}
                <div id="fields-personal" class="view-fields">
                    @foreach ($personalCols as $col => $label)
                        <label class="inline-flex items-center mr-4 mb-2">
                            <input type="checkbox" class="field-check rounded" data-col="{{ $col }}"
                                data-view="personal" checked>
                            <span class="ml-2">{{ $label }}</span>
                        </label>
                    @endforeach
                </div>

                {{-- SIKAP DHEI GRADE REPORT FIELDS --}}
                <div id="fields-gradereport" class="view-fields hidden">
                    @foreach ($gradeReportCols as $col => $label)
                        <label class="inline-flex items-center mr-4 mb-2">
                            <input type="checkbox" class="field-check rounded" data-col="{{ $col }}"
                                data-view="gradereport" checked>
                            <span class="ml-2">{{ $label }}</span>
                        </label>
                    @endforeach
                </div>

                {{-- SIKAP DHEI ENROLLMENT REPORT FIELDS --}}
                <div id="fields-enrollment" class="view-fields hidden">
                    <div class="mb-4">
                        <h3 class="text-sm font-semibold text-gray-800 mb-2">A. Enrolled Scholars, With No Issues</h3>
                        @php
                            $enrollmentACols = [
                                'enr_a_no' => 'No.',
                                'enr_a_name' => 'Name',
                                'enr_a_application_number' => 'Application Number',
                                'enr_a_degree_program' => 'Degree Program',
                                'enr_a_status' => 'Status (Units/Residency/Others)',
                                'enr_a_units_enrolled' => 'Units Enrolled',
                                'enr_a_retaken_subjects' => 'Retaken Subjects?',
                                'enr_a_remarks' => 'Remarks',
                            ];
                        @endphp
                        @foreach ($enrollmentACols as $col => $label)
                            <label class="inline-flex items-center mr-4 mb-2">
                                <input type="checkbox" class="field-check rounded" data-col="{{ $col }}"
                                    data-view="enrollment" data-table="a" checked>
                                <span class="ml-2">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>

                    <div class="mb-4">
                        <h3 class="text-sm font-semibold text-gray-800 mb-2">B. Enrolled Scholars, But With Issues</h3>
                        @php
                            $enrollmentBCols = [
                                'enr_b_no' => 'No.',
                                'enr_b_name' => 'Name',
                                'enr_b_application_number' => 'Application Number',
                                'enr_b_degree_program' => 'Degree Program',
                                'enr_b_status' => 'Status (Extension/Retaken/Withdrew/Others)',
                                'enr_b_others_status' => 'If Others, State Status',
                                'enr_b_description' => 'Short Description of Status',
                            ];
                        @endphp
                        @foreach ($enrollmentBCols as $col => $label)
                            <label class="inline-flex items-center mr-4 mb-2">
                                <input type="checkbox" class="field-check rounded" data-col="{{ $col }}"
                                    data-view="enrollment" data-table="b" checked>
                                <span class="ml-2">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>

                    <div class="mb-4">
                        <h3 class="text-sm font-semibold text-gray-800 mb-2">C. Expected to Enroll, But Did Not Enroll</h3>
                        @php
                            $enrollmentCCols = [
                                'enr_c_no' => 'No.',
                                'enr_c_name' => 'Name',
                                'enr_c_application_number' => 'Application Number',
                                'enr_c_degree_program' => 'Degree Program',
                                'enr_c_status' => 'Status (LOA, AWOL, Did Not Enroll, Others)',
                                'enr_c_others_status' => 'If Others, State Status',
                                'enr_c_description' => 'Short Description of Status',
                            ];
                        @endphp
                        @foreach ($enrollmentCCols as $col => $label)
                            <label class="inline-flex items-center mr-4 mb-2">
                                <input type="checkbox" class="field-check rounded" data-col="{{ $col }}"
                                    data-view="enrollment" data-table="c" checked>
                                <span class="ml-2">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>

                    <div class="mb-4">
                        <h3 class="text-sm font-semibold text-gray-800 mb-2">D. Scholars No Longer Expected to Enroll</h3>
                        @php
                            $enrollmentDCols = [
                                'enr_d_no' => 'No.',
                                'enr_d_name' => 'Name',
                                'enr_d_application_number' => 'Application Number',
                                'enr_d_degree_program' => 'Degree Program',
                                'enr_d_status' => 'Status (Ineligible/Graduated/Withdrawn/Others)',
                                'enr_d_others_status' => 'If Others, State Status',
                                'enr_d_description' => 'Short Description of Status',
                            ];
                        @endphp
                        @foreach ($enrollmentDCols as $col => $label)
                            <label class="inline-flex items-center mr-4 mb-2">
                                <input type="checkbox" class="field-check rounded" data-col="{{ $col }}"
                                    data-view="enrollment" data-table="d" checked>
                                <span class="ml-2">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
                {{-- SIKAP CONTINUING ELIGIBILITY REPORT FIELDS --}}
                <div id="fields-continuing" class="view-fields hidden">
                    <div class="mb-4">
                        <h3 class="text-sm font-semibold text-gray-800 mb-2">A. Continuing Scholars</h3>
                        @php
                            $continuingACols = [
                                'cont_a_name' => 'Name of Scholar',
                                'cont_a_application_number' => 'Application Number',
                                'cont_a_scholarship_type' => 'Scholarship Type',
                                'cont_a_degree_program' => 'Degree Program (no abbreviation)',
                                'cont_a_year_approval' => 'Year of Approval',
                                'cont_a_last_term' => 'Last Term of Enrollment',
                                'cont_a_good_standing' => 'Good Academic Standing?',
                                'cont_a_standing_explanation' => 'If NOT, Explanation',
                                'cont_a_finish_on_time' => 'Finish On Time?',
                                'cont_a_finish_explanation' => 'If NOT, Why?',
                                'cont_a_recommendation' => 'Recommendation',
                                'cont_a_rationale' => 'Rationale',
                            ];
                        @endphp
                        @foreach ($continuingACols as $col => $label)
                            <label class="inline-flex items-center mr-4 mb-2">
                                <input type="checkbox" class="field-check rounded" data-col="{{ $col }}"
                                    data-view="continuing" data-table="a" checked>
                                <span class="ml-2">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>

                    <div class="mb-4">
                        <h3 class="text-sm font-semibold text-gray-800 mb-2">B. Scholars Who Have Completed Their Degrees
                        </h3>
                        @php
                            $continuingBCols = [
                                'cont_b_no' => 'No.',
                                'cont_b_name' => 'Name of Scholar',
                                'cont_b_application_number' => 'Application Number',
                                'cont_b_scholarship_type' => 'Scholarship Type',
                                'cont_b_degree_program' => 'Degree Program (no abbreviation)',
                                'cont_b_academic_year' => 'Academic Year of Graduation',
                                'cont_b_term_graduation' => 'Term of Graduation',
                                'cont_b_remarks' => 'Remarks',
                            ];
                        @endphp
                        @foreach ($continuingBCols as $col => $label)
                            <label class="inline-flex items-center mr-4 mb-2">
                                <input type="checkbox" class="field-check rounded" data-col="{{ $col }}"
                                    data-view="continuing" data-table="b" checked>
                                <span class="ml-2">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Table Card --}}
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            {{-- PERSONAL INFORMATION TABLE --}}
            <div id="table-personal" class="view-table">
                <div class="overflow-x-auto">
                    <table class="w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                @foreach ($personalCols as $col => $label)
                                    <th
                                        class="px-2 py-1.5 text-left text-xs font-medium text-gray-700 uppercase tracking-tight col-{{ $col }}">
                                        {{ $label }}
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($scholars ?? [] as $index => $scholar)
                                <tr class="hover:bg-gray-50">
                                    @foreach ($personalCols as $col => $label)
                                        <td class="px-2 py-1.5 text-xs text-gray-900 col-{{ $col }}">
                                            @if ($col === 'no')
                                                {{ $index + 1 }}
                                            @elseif($col === 'Birthday')
                                                {{ $scholar->date_of_birth ? \Carbon\Carbon::parse($scholar->date_of_birth)->format('m/d/Y') : '' }}
                                            @else
                                                {{ $scholar->$col ?? '' }}
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ count($personalCols) }}"
                                        class="px-2 py-6 text-center text-gray-500 text-sm">
                                        No scholars found for the selected filters.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- SIKAP DHEI GRADE REPORT TABLE --}}
            <div id="table-gradereport" class="view-table hidden">
                <div class="overflow-x-auto">
                    <table class="w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                @foreach ($gradeReportCols as $col => $label)
                                    <th
                                        class="px-2 py-1.5 text-left text-xs font-medium text-gray-700 uppercase tracking-tight col-{{ $col }}">
                                        {{ $label }}
                                    </th>
                                @endforeach
                                <th
                                    class="px-2 py-1.5 text-left text-xs font-medium text-gray-700 uppercase tracking-tight">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($scholars ?? [] as $index => $scholar)
                                <tr class="hover:bg-gray-50" data-scholar-id="{{ $scholar->id }}">
                                    @foreach ($gradeReportCols as $col => $label)
                                        <td class="px-2 py-1.5 text-xs text-gray-900 col-{{ $col }}">
                                            @if ($col === 'gr_no')
                                                {{ $index + 1 }}
                                            @elseif($col === 'gr_name')
                                                {{-- Name is always visible, never editable --}}
                                                {{ trim(($scholar->first_name ?? '') . ' ' . ($scholar->middle_name ?? '') . ' ' . ($scholar->last_name ?? '') . ' ' . ($scholar->suffix ?? '')) }}
                                            @elseif($col === 'application_no')
                                                {{-- Application Number is always visible, never editable --}}
                                                {{ $scholar->application_no ?? '' }}
                                            @elseif($col === 'gr_degree_program')
                                                <span class="view-mode">{{ $scholar->grade_degree_program ?? '' }}</span>
                                                <input type="text"
                                                    class="edit-mode hidden w-full px-1.5 py-1 text-xs border rounded"
                                                    name="degree_program"
                                                    value="{{ $scholar->grade_degree_program ?? '' }}">
                                            @elseif($col === 'gr_enrolled_subjects')
                                                <span class="view-mode">{{ $scholar->enrolled_subjects ?? '' }}</span>
                                                <input type="number"
                                                    class="edit-mode hidden w-full px-1.5 py-1 text-xs border rounded"
                                                    name="enrolled_subjects"
                                                    value="{{ $scholar->enrolled_subjects ?? '' }}">
                                            @elseif($col === 'gr_subjects_passed')
                                                <span class="view-mode">{{ $scholar->subjects_passed ?? '' }}</span>
                                                <input type="number"
                                                    class="edit-mode hidden w-full px-1.5 py-1 text-xs border rounded"
                                                    name="subjects_passed" value="{{ $scholar->subjects_passed ?? '' }}">
                                            @elseif($col === 'gr_incomplete_grades')
                                                <span class="view-mode">{{ $scholar->incomplete_grades ?? '' }}</span>
                                                <input type="number"
                                                    class="edit-mode hidden w-full px-1.5 py-1 text-xs border rounded"
                                                    name="incomplete_grades"
                                                    value="{{ $scholar->incomplete_grades ?? '' }}">
                                            @elseif($col === 'gr_subjects_failed')
                                                <span class="view-mode">{{ $scholar->subjects_failed ?? '' }}</span>
                                                <input type="number"
                                                    class="edit-mode hidden w-full px-1.5 py-1 text-xs border rounded"
                                                    name="subjects_failed" value="{{ $scholar->subjects_failed ?? '' }}">
                                            @elseif($col === 'gr_no_grades')
                                                <span class="view-mode">{{ $scholar->no_grades ?? '' }}</span>
                                                <input type="number"
                                                    class="edit-mode hidden w-full px-1.5 py-1 text-xs border rounded"
                                                    name="no_grades" value="{{ $scholar->no_grades ?? '' }}">
                                            @elseif($col === 'gr_not_credited')
                                                <span class="view-mode">{{ $scholar->not_credited_subjects ?? '' }}</span>
                                                <input type="number"
                                                    class="edit-mode hidden w-full px-1.5 py-1 text-xs border rounded"
                                                    name="not_credited_subjects"
                                                    value="{{ $scholar->not_credited_subjects ?? '' }}">
                                            @elseif($col === 'gr_status')
                                                <span class="view-mode">{{ $scholar->grade_status ?? '' }}</span>
                                                <select class="edit-mode hidden w-full px-1.5 py-1 text-xs border rounded"
                                                    name="status">
                                                    <option value="">Select Status</option>
                                                    <option value="Passed"
                                                        {{ ($scholar->grade_status ?? '') == 'Passed' ? 'selected' : '' }}>
                                                        Passed</option>
                                                    <option value="Probationary"
                                                        {{ ($scholar->grade_status ?? '') == 'Probationary' ? 'selected' : '' }}>
                                                        Probationary</option>
                                                    <option value="Failed"
                                                        {{ ($scholar->grade_status ?? '') == 'Failed' ? 'selected' : '' }}>
                                                        Failed</option>
                                                    <option value="Ineligible to Enroll"
                                                        {{ ($scholar->grade_status ?? '') == 'Ineligible to Enroll' ? 'selected' : '' }}>
                                                        Ineligible to Enroll</option>
                                                </select>
                                            @elseif($col === 'gr_gpa')
                                                <span
                                                    class="view-mode">{{ $scholar->gpa ? number_format($scholar->gpa, 2) : '' }}</span>
                                                <input type="number" step="0.01"
                                                    class="edit-mode hidden w-full px-1.5 py-1 text-xs border rounded"
                                                    name="gpa" value="{{ $scholar->gpa ?? '' }}">
                                            @elseif($col === 'gr_remarks')
                                                <span class="view-mode">{{ $scholar->grade_remarks ?? '' }}</span>
                                                <textarea class="edit-mode hidden w-full px-1.5 py-1 text-xs border rounded" name="remarks" rows="2">{{ $scholar->grade_remarks ?? '' }}</textarea>
                                            @endif
                                        </td>
                                    @endforeach
                                    <td class="px-2 py-1.5 text-xs whitespace-nowrap">
                                        <button
                                            class="edit-btn view-mode bg-blue-600 hover:bg-blue-700 text-white px-2 py-1 rounded text-xs">
                                            Edit
                                        </button>
                                        <div class="edit-mode hidden flex gap-1">
                                            <button
                                                class="save-btn bg-green-600 hover:bg-green-700 text-white px-2 py-1 rounded text-xs">
                                                Save
                                            </button>
                                            <button
                                                class="cancel-btn bg-gray-600 hover:bg-gray-700 text-white px-2 py-1 rounded text-xs">
                                                Cancel
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ count($gradeReportCols) + 1 }}"
                                        class="px-2 py-6 text-center text-gray-500 text-sm">
                                        No grade reports found for the selected filters.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- SIKAP DHEI Enrollment Report --}}
            <div id="table-enrollment" class="view-table hidden">
                {{-- TABLE A: Enrolled Scholars, With No Issues --}}
                <div class="mb-8">
                    <div class="flex justify-between items-center bg-gray-100 px-4 py-3 border-b-2 border-gray-300">
                        <h2 class="text-lg font-bold text-gray-900">
                            A. Enrolled Scholars, With No Issues
                        </h2>
                        <button
                            class="add-scholar-btn no-print bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm font-semibold"
                            data-table="a">
                            + Add Scholar
                        </button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full divide-y divide-gray-200" data-table="a">
                            <thead class="bg-gray-50">
                                <tr>
                                    @foreach ($enrollmentACols as $col => $label)
                                        <th
                                            class="px-2 py-1.5 text-left text-xs font-medium text-gray-700 uppercase tracking-tight col-{{ $col }}">
                                            {{ $label }}
                                        </th>
                                    @endforeach
                                    <th
                                        class="px-2 py-1.5 text-left text-xs font-medium text-gray-700 uppercase tracking-tight">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse (($scholars_enrollment_a ?? []) as $index => $scholar)
                                    <tr class="hover:bg-gray-50" data-scholar-id="{{ $scholar->id }}">
                                        <td class="px-2 py-1.5 text-xs text-gray-900 col-enr_a_no">{{ $index + 1 }}
                                        </td>
                                        <td class="px-2 py-1.5 text-xs text-gray-900 col-enr_a_name">
                                            {{ trim(($scholar->first_name ?? '') . ' ' . ($scholar->middle_name ?? '') . ' ' . ($scholar->last_name ?? '') . ' ' . ($scholar->suffix ?? '')) }}
                                        </td>
                                        <td class="px-2 py-1.5 text-xs text-gray-900 col-enr_a_application_number">
                                            {{ $scholar->application_no ?? '' }}
                                        </td>
                                        <td class="px-2 py-1.5 text-xs text-gray-900 col-enr_a_degree_program">
                                            <span class="view-mode">{{ $scholar->degree_program ?? '' }}</span>
                                            <input type="text"
                                                class="edit-mode hidden w-full px-1.5 py-1 text-xs border rounded"
                                                name="degree_program" value="{{ $scholar->degree_program ?? '' }}">
                                        </td>
                                        <td class="px-2 py-1.5 text-xs text-gray-900 col-enr_a_status">
                                            <span class="view-mode">{{ $scholar->enrollment_status ?? '' }}</span>
                                            <select class="edit-mode hidden w-full px-1.5 py-1 text-xs border rounded"
                                                name="enrollment_status">
                                                <option value="">Select Status</option>
                                                <option value="Units"
                                                    {{ ($scholar->enrollment_status ?? '') == 'Units' ? 'selected' : '' }}>
                                                    Enrolled: with Units</option>
                                                <option value="Residency"
                                                    {{ ($scholar->enrollment_status ?? '') == 'Residency' ? 'selected' : '' }}>
                                                    Enrolled: under Residency</option>
                                                <option value="Others"
                                                    {{ ($scholar->enrollment_status ?? '') == 'Others' ? 'selected' : '' }}>
                                                    Others: please specify</option>
                                            </select>
                                        </td>
                                        <td class="px-2 py-1.5 text-xs text-gray-900 col-enr_a_units_enrolled">
                                            <span class="view-mode">{{ $scholar->units_enrolled ?? '' }}</span>
                                            <input type="number"
                                                class="edit-mode hidden w-full px-1.5 py-1 text-xs border rounded"
                                                name="units_enrolled" value="{{ $scholar->units_enrolled ?? '' }}">
                                        </td>
                                        <td class="px-2 py-1.5 text-xs text-gray-900 col-enr_a_retaken_subjects">
                                            <span class="view-mode">{{ $scholar->retaken_subjects ?? '' }}</span>
                                            <select class="edit-mode hidden w-full px-1.5 py-1 text-xs border rounded"
                                                name="retaken_subjects">
                                                <option value="">Select</option>
                                                <option value="Yes"
                                                    {{ ($scholar->retaken_subjects ?? '') == 'Yes' ? 'selected' : '' }}>Yes
                                                </option>
                                                <option value="No"
                                                    {{ ($scholar->retaken_subjects ?? '') == 'No' ? 'selected' : '' }}>No
                                                </option>
                                            </select>
                                        </td>
                                        <td class="px-2 py-1.5 text-xs text-gray-900 col-enr_a_remarks">
                                            <span class="view-mode">{{ $scholar->remarks ?? '' }}</span>
                                            <textarea class="edit-mode hidden w-full px-1.5 py-1 text-xs border rounded" name="remarks" rows="2">{{ $scholar->remarks ?? '' }}</textarea>
                                        </td>
                                        <td class="px-2 py-1.5 text-xs whitespace-nowrap">
                                            <button
                                                class="edit-btn view-mode bg-blue-600 hover:bg-blue-700 text-white px-2 py-1 rounded text-xs">
                                                Edit
                                            </button>
                                            <div class="edit-mode hidden flex gap-1">
                                                <button
                                                    class="save-btn bg-green-600 hover:bg-green-700 text-white px-2 py-1 rounded text-xs">
                                                    Save
                                                </button>
                                                <button
                                                    class="cancel-btn bg-gray-600 hover:bg-gray-700 text-white px-2 py-1 rounded text-xs">
                                                    Cancel
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="px-2 py-6 text-center text-gray-500 text-sm">
                                            No enrolled scholars with no issues found. Click "Add Scholar" to add one.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- TABLE B --}}
                <div class="mb-8">
                    <div class="flex justify-between items-center bg-gray-100 px-4 py-3 border-b-2 border-gray-300">
                        <h2 class="text-lg font-bold text-gray-900">
                            B. Enrolled Scholars, But With Issues
                        </h2>
                        <button
                            class="add-scholar-btn no-print bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm font-semibold"
                            data-table="b">
                            + Add Scholar
                        </button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full divide-y divide-gray-200" data-table="b">
                            <thead class="bg-gray-50">
                                <tr>
                                    @foreach ($enrollmentBCols as $col => $label)
                                        <th
                                            class="px-2 py-1.5 text-left text-xs font-medium text-gray-700 uppercase tracking-tight col-{{ $col }}">
                                            {{ $label }}
                                        </th>
                                    @endforeach
                                    <th
                                        class="px-2 py-1.5 text-left text-xs font-medium text-gray-700 uppercase tracking-tight">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse (($scholars_enrollment_b ?? []) as $index => $scholar)
                                    {{-- Same structure as Table A but with Table B fields --}}
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-2 py-6 text-center text-gray-500 text-sm">
                                            No enrolled scholars with issues found. Click "Add Scholar" to add one.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- TABLE C --}}
                <div class="mb-8">
                    <div class="flex justify-between items-center bg-gray-100 px-4 py-3 border-b-2 border-gray-300">
                        <h2 class="text-lg font-bold text-gray-900">
                            C. Expected to Enroll, But Did Not Enroll
                        </h2>
                        <button
                            class="add-scholar-btn no-print bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm font-semibold"
                            data-table="c">
                            + Add Scholar
                        </button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full divide-y divide-gray-200" data-table="c">
                            <thead class="bg-gray-50">
                                <tr>
                                    @foreach ($enrollmentCCols as $col => $label)
                                        <th
                                            class="px-2 py-1.5 text-left text-xs font-medium text-gray-700 uppercase tracking-tight col-{{ $col }}">
                                            {{ $label }}
                                        </th>
                                    @endforeach
                                    <th
                                        class="px-2 py-1.5 text-left text-xs font-medium text-gray-700 uppercase tracking-tight">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse (($scholars_enrollment_c ?? []) as $index => $scholar)
                                    {{-- Same structure as Table A but with Table C fields --}}
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-2 py-6 text-center text-gray-500 text-sm">
                                            No scholars expected to enroll but did not enroll found. Click "Add Scholar" to
                                            add one.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- TABLE D --}}
                <div class="mb-8">
                    <div class="flex justify-between items-center bg-gray-100 px-4 py-3 border-b-2 border-gray-300">
                        <h2 class="text-lg font-bold text-gray-900">
                            D. Scholars No Longer Expected to Enroll
                        </h2>
                        <button
                            class="add-scholar-btn no-print bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm font-semibold"
                            data-table="d">
                            + Add Scholar
                        </button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full divide-y divide-gray-200" data-table="d">
                            <thead class="bg-gray-50">
                                <tr>
                                    @foreach ($enrollmentDCols as $col => $label)
                                        <th
                                            class="px-2 py-1.5 text-left text-xs font-medium text-gray-700 uppercase tracking-tight col-{{ $col }}">
                                            {{ $label }}
                                        </th>
                                    @endforeach
                                    <th
                                        class="px-2 py-1.5 text-left text-xs font-medium text-gray-700 uppercase tracking-tight">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse (($scholars_enrollment_d ?? []) as $index => $scholar)
                                    {{-- Same structure as Table A but with Table D fields --}}
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-2 py-6 text-center text-gray-500 text-sm">
                                            No scholars no longer expected to enroll found. Click "Add Scholar" to add one.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Add Scholar Modal --}}
            <div id="addScholarModal"
                class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
                <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-md bg-white">
                    <div class="flex justify-between items-center pb-3 border-b">
                        <h3 class="text-xl font-bold">Select Scholar to Add</h3>
                        <button class="close-modal text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
                    </div>

                    <div class="mt-4">
                        <input type="text" id="scholarSearch" placeholder="Search by name or application number..."
                            class="w-full px-4 py-2 border rounded-lg mb-4">

                        <div id="scholarList" class="max-h-96 overflow-y-auto">
                            @foreach ($scholars ?? [] as $scholar)
                                <div class="scholar-item p-3 hover:bg-gray-100 cursor-pointer border-b flex justify-between items-center"
                                    data-scholar-id="{{ $scholar->id }}"
                                    data-name="{{ trim(($scholar->first_name ?? '') . ' ' . ($scholar->middle_name ?? '') . ' ' . ($scholar->last_name ?? '') . ' ' . ($scholar->suffix ?? '')) }}"
                                    data-application="{{ $scholar->application_no }}">
                                    <div>
                                        <div class="font-semibold">
                                            {{ trim(($scholar->first_name ?? '') . ' ' . ($scholar->middle_name ?? '') . ' ' . ($scholar->last_name ?? '') . ' ' . ($scholar->suffix ?? '')) }}
                                        </div>
                                        <div class="text-sm text-gray-600">{{ $scholar->application_no }}</div>
                                    </div>
                                    <button
                                        class="select-scholar-btn bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">
                                        Select
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- SIKAP Continuing Eligibility Report --}}
            <div id="table-continuing" class="view-table hidden">
                {{-- TABLE A: Continuing Scholars --}}
                <div class="mb-8">
                    <div class="flex justify-between items-center bg-gray-100 px-4 py-3 border-b-2 border-gray-300">
                        <h2 class="text-lg font-bold text-gray-900">
                            A. Continuing Scholars
                        </h2>
                        <button
                            class="add-continuing-btn no-print bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm font-semibold"
                            data-table="a">
                            + Add Scholar
                        </button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200" data-table="a">
                            <thead class="bg-gray-50">
                                <tr>
                                    @foreach ($continuingACols as $col => $label)
                                        <th
                                            class="px-1 py-1.5 text-left text-xs font-medium text-gray-700 uppercase tracking-tight col-{{ $col }}">
                                            {{ $label }}
                                        </th>
                                    @endforeach
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse (($scholars_continuing_a ?? []) as $index => $scholar)
                                    <tr class="hover:bg-gray-50" data-scholar-id="{{ $scholar->id }}">
                                        <td class="px-2 py-1.5 text-xs text-gray-900 col-cont_a_no">
                                            {{ $index + 1 }}
                                        </td>
                                        <td class="px-2 py-1.5 text-xs text-gray-900 col-cont_a_name">
                                            {{ trim(($scholar->first_name ?? '') . ' ' . ($scholar->middle_name ?? '') . ' ' . ($scholar->last_name ?? '') . ' ' . ($scholar->suffix ?? '')) }}
                                        </td>
                                        <td class="px-2 py-1.5 text-xs text-gray-900 col-cont_a_application_number">
                                            {{ $scholar->application_no ?? '' }}
                                        </td>
                                        <td class="px-2 py-1.5 text-xs text-gray-900 col-cont_a_scholarship_type">
                                            <span class="view-mode">{{ $scholar->scholarship_type ?? '' }}</span>
                                            <input type="text"
                                                class="edit-mode hidden w-full px-1.5 py-1 text-xs border rounded"
                                                name="scholarship_type" value="{{ $scholar->scholarship_type ?? '' }}">
                                        </td>
                                        <td class="px-2 py-1.5 text-xs text-gray-900 col-cont_a_degree_program">
                                            <span class="view-mode">{{ $scholar->degree_program ?? '' }}</span>
                                            <input type="text"
                                                class="edit-mode hidden w-full px-1.5 py-1 text-xs border rounded"
                                                name="degree_program" value="{{ $scholar->degree_program ?? '' }}">
                                        </td>
                                        <td class="px-2 py-1.5 text-xs text-gray-900 col-cont_a_year_approval">
                                            <span class="view-mode">{{ $scholar->year_of_approval ?? '' }}</span>
                                            <input type="text"
                                                class="edit-mode hidden w-full px-1.5 py-1 text-xs border rounded"
                                                name="year_of_approval" value="{{ $scholar->year_of_approval ?? '' }}">
                                        </td>
                                        <td class="px-2 py-1.5 text-xs text-gray-900 col-cont_a_last_term">
                                            <span class="view-mode">{{ $scholar->last_term_enrollment ?? '' }}</span>
                                            <input type="text"
                                                class="edit-mode hidden w-full px-1.5 py-1 text-xs border rounded"
                                                name="last_term_enrollment"
                                                value="{{ $scholar->last_term_enrollment ?? '' }}">
                                        </td>
                                        <td class="px-2 py-1.5 text-xs text-gray-900 col-cont_a_good_standing">
                                            <span class="view-mode">
                                                @if (isset($scholar->good_academic_standing))
                                                    <span
                                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $scholar->good_academic_standing ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                        {{ $scholar->good_academic_standing ? 'Yes' : 'No' }}
                                                    </span>
                                                @endif
                                            </span>
                                            <select class="edit-mode hidden w-full px-1.5 py-1 text-xs border rounded"
                                                name="good_academic_standing">
                                                <option value="">Select</option>
                                                <option value="1"
                                                    {{ ($scholar->good_academic_standing ?? '') == 1 ? 'selected' : '' }}>
                                                    Yes</option>
                                                <option value="0"
                                                    {{ ($scholar->good_academic_standing ?? '') == 0 ? 'selected' : '' }}>
                                                    No</option>
                                            </select>
                                        </td>
                                        <td class="px-2 py-1.5 text-xs text-gray-900 col-cont_a_standing_explanation">
                                            <span class="view-mode">{{ $scholar->standing_explanation ?? '' }}</span>
                                            <textarea class="edit-mode hidden w-full px-1.5 py-1 text-xs border rounded" name="standing_explanation"
                                                rows="2">{{ $scholar->standing_explanation ?? '' }}</textarea>
                                        </td>
                                        <td class="px-2 py-1.5 text-xs text-gray-900 col-cont_a_finish_on_time">
                                            <span class="view-mode">
                                                @if (isset($scholar->finish_on_time))
                                                    <span
                                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $scholar->finish_on_time ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                        {{ $scholar->finish_on_time ? 'Yes' : 'No' }}
                                                    </span>
                                                @endif
                                            </span>
                                            <select class="edit-mode hidden w-full px-1.5 py-1 text-xs border rounded"
                                                name="finish_on_time">
                                                <option value="">Select</option>
                                                <option value="1"
                                                    {{ ($scholar->finish_on_time ?? '') == 1 ? 'selected' : '' }}>Yes
                                                </option>
                                                <option value="0"
                                                    {{ ($scholar->finish_on_time ?? '') == 0 ? 'selected' : '' }}>No
                                                </option>
                                            </select>
                                        </td>
                                        <td class="px-2 py-1.5 text-xs text-gray-900 col-cont_a_finish_explanation">
                                            <span class="view-mode">{{ $scholar->finish_explanation ?? '' }}</span>
                                            <textarea class="edit-mode hidden w-full px-1.5 py-1 text-xs border rounded" name="finish_explanation"
                                                rows="2">{{ $scholar->finish_explanation ?? '' }}</textarea>
                                        </td>
                                        <td class="px-2 py-1.5 text-xs text-gray-900 col-cont_a_recommendation">
                                            <span class="view-mode">{{ $scholar->recommendation ?? '' }}</span>
                                            <input type="text"
                                                class="edit-mode hidden w-full px-1.5 py-1 text-xs border rounded"
                                                name="recommendation" value="{{ $scholar->recommendation ?? '' }}">
                                        </td>
                                        <td class="px-2 py-1.5 text-xs text-gray-900 col-cont_a_rationale">
                                            <span class="view-mode">{{ $scholar->rationale ?? '' }}</span>
                                            <textarea class="edit-mode hidden w-full px-1.5 py-1 text-xs border rounded" name="rationale" rows="2">{{ $scholar->rationale ?? '' }}</textarea>
                                        </td>
                                        <td class="px-2 py-1.5 text-xs whitespace-nowrap">
                                            <button
                                                class="edit-btn view-mode bg-blue-600 hover:bg-blue-700 text-white px-2 py-1 rounded text-xs">
                                                Edit
                                            </button>
                                            <div class="edit-mode hidden flex gap-1">
                                                <button
                                                    class="save-btn bg-green-600 hover:bg-green-700 text-white px-2 py-1 rounded text-xs">
                                                    Save
                                                </button>
                                                <button
                                                    class="cancel-btn bg-gray-600 hover:bg-gray-700 text-white px-2 py-1 rounded text-xs">
                                                    Cancel
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="14" class="px-2 py-6 text-center text-gray-500 text-sm">
                                            No continuing scholars found. Click "Add Scholar" to add one.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- TABLE B: Scholars Who Have Completed Their Degrees --}}
                <div class="mb-8">
                    <div class="flex justify-between items-center bg-gray-100 px-4 py-3 border-b-2 border-gray-300">
                        <h2 class="text-lg font-bold text-gray-900">
                            B. Scholars Who Have Completed Their Degrees
                        </h2>
                        <button
                            class="add-continuing-btn no-print bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm font-semibold"
                            data-table="b">
                            + Add Scholar
                        </button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200" data-table="b">
                            <thead class="bg-gray-50">
                                <tr>
                                    @foreach ($continuingBCols as $col => $label)
                                        <th
                                            class="px-1 py-1.5 text-left text-xs font-medium text-gray-700 uppercase tracking-tight col-{{ $col }}">
                                            {{ $label }}
                                        </th>
                                    @endforeach
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse (($scholars_continuing_b ?? []) as $index => $scholar)
                                    <tr class="hover:bg-gray-50" data-scholar-id="{{ $scholar->id }}">
                                        <td class="px-4 py-3 text-sm text-gray-900 col-cont_b_no">
                                            {{ $index + 1 }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900 col-cont_b_name">
                                            {{ trim(($scholar->first_name ?? '') . ' ' . ($scholar->middle_name ?? '') . ' ' . ($scholar->last_name ?? '') . ' ' . ($scholar->suffix ?? '')) }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900 col-cont_b_application_number">
                                            {{ $scholar->application_no ?? '' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900 col-cont_b_scholarship_type">
                                            <span class="view-mode">{{ $scholar->scholarship_type ?? '' }}</span>
                                            <input type="text" class="edit-mode hidden w-full px-2 py-1 border rounded"
                                                name="scholarship_type" value="{{ $scholar->scholarship_type ?? '' }}">
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900 col-cont_b_degree_program">
                                            <span class="view-mode">{{ $scholar->degree_program ?? '' }}</span>
                                            <input type="text" class="edit-mode hidden w-full px-2 py-1 border rounded"
                                                name="degree_program" value="{{ $scholar->degree_program ?? '' }}">
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900 col-cont_b_academic_year">
                                            <span class="view-mode">{{ $scholar->academic_year_graduation ?? '' }}</span>
                                            <input type="text" class="edit-mode hidden w-full px-2 py-1 border rounded"
                                                name="academic_year_graduation"
                                                value="{{ $scholar->academic_year_graduation ?? '' }}">
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900 col-cont_b_term_graduation">
                                            <span class="view-mode">{{ $scholar->term_of_graduation ?? '' }}</span>
                                            <select class="edit-mode hidden w-full px-2 py-1 border rounded"
                                                name="term_of_graduation">
                                                <option value="">Select Term</option>
                                                <option value="1st Semester"
                                                    {{ ($scholar->term_of_graduation ?? '') == '1st Semester' ? 'selected' : '' }}>
                                                    1st Semester</option>
                                                <option value="2nd Semester"
                                                    {{ ($scholar->term_of_graduation ?? '') == '2nd Semester' ? 'selected' : '' }}>
                                                    2nd Semester</option>
                                                <option value="Summer"
                                                    {{ ($scholar->term_of_graduation ?? '') == 'Summer' ? 'selected' : '' }}>
                                                    Summer</option>
                                            </select>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900 col-cont_b_remarks">
                                            <span class="view-mode">{{ $scholar->remarks ?? '' }}</span>
                                            <textarea class="edit-mode hidden w-full px-2 py-1 border rounded" name="remarks" rows="2">{{ $scholar->remarks ?? '' }}</textarea>
                                        </td>
                                        <td class="px-4 py-3 text-sm whitespace-nowrap">
                                            <button
                                                class="edit-btn view-mode bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs">
                                                Edit
                                            </button>
                                            <div class="edit-mode hidden flex gap-2">
                                                <button
                                                    class="save-btn bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs">
                                                    Save
                                                </button>
                                                <button
                                                    class="cancel-btn bg-gray-600 hover:bg-gray-700 text-white px-3 py-1 rounded text-xs">
                                                    Cancel
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="px-4 py-8 text-center text-gray-500">
                                            No scholars who have completed their degrees found. Click "Add Scholar" to add
                                            one.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Add Scholar Modal for Continuing Eligibility (reuse the same modal from enrollment) --}}
            <div id="addContinuingModal"
                class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
                <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-md bg-white">
                    <div class="flex justify-between items-center pb-3 border-b">
                        <h3 class="text-xl font-bold">Select Scholar to Add</h3>
                        <button class="close-continuing-modal text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
                    </div>

                    <div class="mt-4">
                        <input type="text" id="continuingScholarSearch"
                            placeholder="Search by name or application number..."
                            class="w-full px-4 py-2 border rounded-lg mb-4">

                        <div id="continuingScholarList" class="max-h-96 overflow-y-auto">
                            @foreach ($scholars ?? [] as $scholar)
                                <div class="continuing-scholar-item p-3 hover:bg-gray-100 cursor-pointer border-b flex justify-between items-center"
                                    data-scholar-id="{{ $scholar->id }}"
                                    data-name="{{ trim(($scholar->first_name ?? '') . ' ' . ($scholar->middle_name ?? '') . ' ' . ($scholar->last_name ?? '') . ' ' . ($scholar->suffix ?? '')) }}"
                                    data-application="{{ $scholar->application_no }}">
                                    <div>
                                        <div class="font-semibold">
                                            {{ trim(($scholar->first_name ?? '') . ' ' . ($scholar->middle_name ?? '') . ' ' . ($scholar->last_name ?? '') . ' ' . ($scholar->suffix ?? '')) }}
                                        </div>
                                        <div class="text-sm text-gray-600">{{ $scholar->application_no }}</div>
                                    </div>
                                    <button
                                        class="select-continuing-scholar-btn bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">
                                        Select
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endsection
        @push('scripts')
            <script src="{{ asset('js/ched-reports-common.js') }}"></script>
            <script src="{{ asset('js/ched-grade-report.js') }}"></script>
            <script src="{{ asset('js/ched-enrollment-report.js') }}"></script>
            <script src="{{ asset('js/ched-eligibility-report.js') }}"></script>
        @endpush
