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

                <div class="no-print flex gap-3">
                    <button id="printBtn"
                        class="bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-2.5 rounded-lg transition shadow-sm">
                        Print
                    </button>

                    <button id="resetCols"
                        class="bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-2.5 rounded-lg transition shadow-sm">
                        Reset Columns
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
                                <option value="1st" {{ request('semester') == '1st' ? 'selected' : '' }}>1st Semester
                                </option>
                                <option value="2nd" {{ request('semester') == '2nd' ? 'selected' : '' }}>2nd Semester
                                </option>
                            </select>
                        </div>

                        <!-- Academic Year -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Academic Year</label>
                            <select name="academic_year"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="" {{ request('academic_year') == '' ? 'selected' : '' }}>All Years
                                </option>

                                @foreach (range(date('Y'), date('Y') - 10) as $year)
                                    <option value="{{ $year }}-{{ $year + 1 }}"
                                        {{ request('academic_year') == $year . '-' . ($year + 1) ? 'selected' : '' }}>
                                        {{ $year }}-{{ $year + 1 }}
                                    </option>
                                @endforeach
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
                        class="view-tab px-6 py-2.5 rounded-lg font-semibold transition bg-blue-600 text-white">
                        Personal Information
                    </button>

                    <button id="viewGradereport"
                        class="view-tab px-6 py-2.5 rounded-lg font-semibold transition bg-gray-200 text-gray-700 hover:bg-gray-300">
                        SIKAP DHEI Grade Report
                    </button>

                    <button id="viewEnrollment"
                        class="view-tab px-6 py-2.5 rounded-lg font-semibold transition bg-gray-200 text-gray-700 hover:bg-gray-300">
                        SIKAP DHEI Enrollment Report
                    </button>

                    <button id="viewContinuing"
                        class="view-tab px-6 py-2.5 rounded-lg font-semibold transition bg-gray-200 text-gray-700 hover:bg-gray-300">
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
                        'village' => 'Village',
                        'town' => 'Town',
                        'province' => 'Province',
                        'zipcode' => 'Zipcode',
                        'district' => 'District',
                        'region' => 'Region',
                        'email' => 'Email',
                        'birthday' => 'Birthday',
                        'contact_no' => 'Contact No.',
                        'gender' => 'Gender',
                    ];

                    $gradeReportCols = [
                        'gr_no' => 'No.',
                        'gr_name' => 'Name',
                        'gr_application_number' => 'Application Number',
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
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                @foreach ($personalCols as $col => $label)
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider col-{{ $col }}">
                                        {{ $label }}
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($scholars ?? [] as $index => $scholar)
                                <tr class="hover:bg-gray-50">
                                    @foreach ($personalCols as $col => $label)
                                        <td
                                            class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 col-{{ $col }}">
                                            @if ($col === 'no')
                                                {{ $index + 1 }}
                                            @elseif($col === 'birthday')
                                                {{ $scholar->birthday ? \Carbon\Carbon::parse($scholar->birthday)->format('m/d/Y') : '' }}
                                            @else
                                                {{ data_get($scholar, $col) ?? '' }}
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ count($personalCols) }}" class="px-4 py-8 text-center text-gray-500">
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
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                @foreach ($gradeReportCols as $col => $label)
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider col-{{ $col }}">
                                        {{ $label }}
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($scholars ?? [] as $index => $scholar)
                                <tr class="hover:bg-gray-50">
                                    @foreach ($gradeReportCols as $col => $label)
                                        <td class="px-4 py-3 text-sm text-gray-900 col-{{ $col }}">
                                            @if ($col === 'gr_no')
                                                {{ $index + 1 }}
                                            @elseif($col === 'gr_name')
                                                {{ trim(($scholar->first_name ?? '') . ' ' . ($scholar->middle_name ?? '') . ' ' . ($scholar->last_name ?? '') . ' ' . ($scholar->suffix ?? '')) }}
                                            @elseif($col === 'gr_application_number')
                                                {{ $scholar->application_number ?? '' }}
                                            @elseif($col === 'gr_degree_program')
                                                {{ $scholar->degree_program ?? '' }}
                                            @elseif($col === 'gr_enrolled_subjects')
                                                {{ $scholar->enrolled_subjects ?? '' }}
                                            @elseif($col === 'gr_subjects_passed')
                                                {{ $scholar->subjects_passed ?? '' }}
                                            @elseif($col === 'gr_incomplete_grades')
                                                {{ $scholar->incomplete_grades ?? '' }}
                                            @elseif($col === 'gr_subjects_failed')
                                                {{ $scholar->subjects_failed ?? '' }}
                                            @elseif($col === 'gr_no_grades')
                                                {{ $scholar->no_grades ?? '' }}
                                            @elseif($col === 'gr_not_credited')
                                                {{ $scholar->not_credited_subjects ?? '' }}
                                            @elseif($col === 'gr_status')
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    {{ ($scholar->status ?? '') === 'Active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ $scholar->status ?? '' }}
                                                </span>
                                            @elseif($col === 'gr_gpa')
                                                {{ $scholar->gpa ? number_format($scholar->gpa, 2) : '' }}
                                            @elseif($col === 'gr_remarks')
                                                {{ $scholar->remarks ?? '' }}
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ count($gradeReportCols) }}"
                                        class="px-4 py-8 text-center text-gray-500">
                                        No grade reports found for the selected filters.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="table-enrollment" class="view-table hidden">
                {{-- TABLE A: Enrolled Scholars, With No Issues --}}
                <div class="mb-8">
                    <h2 class="text-lg font-bold text-gray-900 bg-gray-100 px-4 py-3 border-b-2 border-gray-300">
                        A. Enrolled Scholars, With No Issues
                    </h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    @foreach ($enrollmentACols as $col => $label)
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider col-{{ $col }}">
                                            {{ $label }}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse (($scholars_enrollment_a ?? []) as $index => $scholar)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 text-sm text-gray-900 col-enr_a_no">{{ $index + 1 }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900 col-enr_a_name">
                                            {{ trim(($scholar->first_name ?? '') . ' ' . ($scholar->middle_name ?? '') . ' ' . ($scholar->last_name ?? '') . ' ' . ($scholar->suffix ?? '')) }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900 col-enr_a_application_number">
                                            {{ $scholar->application_number ?? '' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900 col-enr_a_degree_program">
                                            {{ $scholar->degree_program ?? '' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900 col-enr_a_status">
                                            {{ $scholar->enrollment_status ?? '' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900 col-enr_a_units_enrolled">
                                            {{ $scholar->units_enrolled ?? '' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900 col-enr_a_retaken_subjects">
                                            {{ $scholar->retaken_subjects ?? '' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900 col-enr_a_remarks">
                                            {{ $scholar->remarks ?? '' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-4 py-8 text-center text-gray-500">
                                            No enrolled scholars with no issues found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- TABLE B: Enrolled Scholars, But With Issues --}}
                <div class="mb-8">
                    <h2 class="text-lg font-bold text-gray-900 bg-gray-100 px-4 py-3 border-b-2 border-gray-300">
                        B. Enrolled Scholars, But With Issues
                    </h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    @foreach ($enrollmentBCols as $col => $label)
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider col-{{ $col }}">
                                            {{ $label }}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse (($scholars_enrollment_b ?? []) as $index => $scholar)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 text-sm text-gray-900 col-enr_b_no">{{ $index + 1 }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900 col-enr_b_name">
                                            {{ trim(($scholar->first_name ?? '') . ' ' . ($scholar->middle_name ?? '') . ' ' . ($scholar->last_name ?? '') . ' ' . ($scholar->suffix ?? '')) }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900 col-enr_b_application_number">
                                            {{ $scholar->application_number ?? '' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900 col-enr_b_degree_program">
                                            {{ $scholar->degree_program ?? '' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900 col-enr_b_status">
                                            {{ $scholar->issue_status ?? '' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900 col-enr_b_others_status">
                                            {{ $scholar->others_status ?? '' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900 col-enr_b_description">
                                            {{ $scholar->status_description ?? '' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                            No enrolled scholars with issues found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- TABLE C: Expected to Enroll, But Did Not Enroll --}}
                <div class="mb-8">
                    <h2 class="text-lg font-bold text-gray-900 bg-gray-100 px-4 py-3 border-b-2 border-gray-300">
                        C. Expected to Enroll, But Did Not Enroll
                    </h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    @foreach ($enrollmentCCols as $col => $label)
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider col-{{ $col }}">
                                            {{ $label }}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse (($scholars_enrollment_c ?? []) as $index => $scholar)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 text-sm text-gray-900 col-enr_c_no">{{ $index + 1 }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900 col-enr_c_name">
                                            {{ trim(($scholar->first_name ?? '') . ' ' . ($scholar->middle_name ?? '') . ' ' . ($scholar->last_name ?? '') . ' ' . ($scholar->suffix ?? '')) }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900 col-enr_c_application_number">
                                            {{ $scholar->application_number ?? '' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900 col-enr_c_degree_program">
                                            {{ $scholar->degree_program ?? '' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900 col-enr_c_status">
                                            {{ $scholar->non_enrollment_status ?? '' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900 col-enr_c_others_status">
                                            {{ $scholar->others_status ?? '' }}</td>
                                        <td class="px-4 py-3 text-sm text-sm text-gray-900 col-enr_c_description">
                                            {{ $scholar->status_description ?? '' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                            No scholars expected to enroll but did not enroll found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- TABLE D: Scholars No Longer Expected to Enroll --}}
                <div class="mb-8">
                    <h2 class="text-lg font-bold text-gray-900 bg-gray-100 px-4 py-3 border-b-2 border-gray-300">
                        D. Scholars No Longer Expected to Enroll
                    </h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    @foreach ($enrollmentDCols as $col => $label)
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider col-{{ $col }}">
                                            {{ $label }}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse (($scholars_enrollment_d ?? []) as $index => $scholar)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 text-sm text-gray-900 col-enr_d_no">{{ $index + 1 }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900 col-enr_d_name">
                                            {{ trim(($scholar->first_name ?? '') . ' ' . ($scholar->middle_name ?? '') . ' ' . ($scholar->last_name ?? '') . ' ' . ($scholar->suffix ?? '')) }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900 col-enr_d_application_number">
                                            {{ $scholar->application_number ?? '' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900 col-enr_d_degree_program">
                                            {{ $scholar->degree_program ?? '' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900 col-enr_d_status">
                                            {{ $scholar->termination_status ?? '' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900 col-enr_d_others_status">
                                            {{ $scholar->others_status ?? '' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900 col-enr_d_description">
                                            {{ $scholar->status_description ?? '' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                            No scholars no longer expected to enroll found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div id="table-continuing" class="view-table hidden">
                {{-- TABLE A: Continuing Scholars --}}
                <div class="mb-8">
                    <h2 class="text-lg font-bold text-gray-900 bg-gray-100 px-4 py-3 border-b-2 border-gray-300">
                        A. Continuing Scholars
                    </h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    @foreach ($continuingACols as $col => $label)
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider col-{{ $col }}">
                                            {{ $label }}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse (($scholars_continuing_a ?? []) as $index => $scholar)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 text-sm text-gray-900 col-cont_a_name">
                                            {{ trim(($scholar->first_name ?? '') . ' ' . ($scholar->middle_name ?? '') . ' ' . ($scholar->last_name ?? '') . ' ' . ($scholar->suffix ?? '')) }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900 col-cont_a_application_number">
                                            {{ $scholar->application_number ?? '' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900 col-cont_a_scholarship_type">
                                            {{ $scholar->scholarship_type ?? '' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900 col-cont_a_degree_program">
                                            {{ $scholar->degree_program ?? '' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900 col-cont_a_year_approval">
                                            {{ $scholar->year_of_approval ?? '' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900 col-cont_a_last_term">
                                            {{ $scholar->last_term_enrollment ?? '' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900 col-cont_a_good_standing">
                                            @if (isset($scholar->good_academic_standing))
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $scholar->good_academic_standing ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ $scholar->good_academic_standing ? 'Yes' : 'No' }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900 col-cont_a_standing_explanation">
                                            {{ $scholar->standing_explanation ?? '' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900 col-cont_a_finish_on_time">
                                            @if (isset($scholar->finish_on_time))
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $scholar->finish_on_time ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                    {{ $scholar->finish_on_time ? 'Yes' : 'No' }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900 col-cont_a_finish_explanation">
                                            {{ $scholar->finish_explanation ?? '' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900 col-cont_a_recommendation">
                                            {{ $scholar->recommendation ?? '' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900 col-cont_a_rationale">
                                            {{ $scholar->rationale ?? '' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="12" class="px-4 py-8 text-center text-gray-500">
                                            No continuing scholars found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- TABLE B: Scholars Who Have Completed Their Degrees --}}
                <div class="mb-8">
                    <h2 class="text-lg font-bold text-gray-900 bg-gray-100 px-4 py-3 border-b-2 border-gray-300">
                        B. Scholars Who Have Completed Their Degrees
                    </h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    @foreach ($continuingBCols as $col => $label)
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider col-{{ $col }}">
                                            {{ $label }}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse (($scholars_continuing_b ?? []) as $index => $scholar)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 text-sm text-gray-900 col-cont_b_no">
                                            {{ $index + 1 }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900 col-cont_b_name">
                                            {{ trim(($scholar->first_name ?? '') . ' ' . ($scholar->middle_name ?? '') . ' ' . ($scholar->last_name ?? '') . ' ' . ($scholar->suffix ?? '')) }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900 col-cont_b_application_number">
                                            {{ $scholar->application_number ?? '' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900 col-cont_b_scholarship_type">
                                            {{ $scholar->scholarship_type ?? '' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900 col-cont_b_degree_program">
                                            {{ $scholar->degree_program ?? '' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900 col-cont_b_academic_year">
                                            {{ $scholar->academic_year_graduation ?? '' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900 col-cont_b_term_graduation">
                                            {{ $scholar->term_of_graduation ?? '' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900 col-cont_b_remarks">
                                            {{ $scholar->remarks ?? '' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-4 py-8 text-center text-gray-500">
                                            No scholars who have completed their degrees found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Current active view
            let currentView = 'personal';

            // Tab switching
            const viewTabs = document.querySelectorAll('.view-tab');
            const viewFields = document.querySelectorAll('.view-fields');
            const viewTables = document.querySelectorAll('.view-table');

            viewTabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    const viewName = this.id.replace('view', '').toLowerCase();

                    // Update active tab styling
                    viewTabs.forEach(t => {
                        t.classList.remove('bg-blue-600', 'text-white');
                        t.classList.add('bg-gray-200', 'text-gray-700',
                            'hover:bg-gray-300');
                    });
                    this.classList.remove('bg-gray-200', 'text-gray-700', 'hover:bg-gray-300');
                    this.classList.add('bg-blue-600', 'text-white');

                    // Show corresponding fields and table
                    viewFields.forEach(field => field.classList.add('hidden'));
                    viewTables.forEach(table => table.classList.add('hidden'));

                    document.getElementById(`fields-${viewName}`).classList.remove('hidden');
                    document.getElementById(`table-${viewName}`).classList.remove('hidden');

                    currentView = viewName;
                });
            });

            // Column visibility toggle
            const fieldChecks = document.querySelectorAll('.field-check');

            fieldChecks.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const colName = this.dataset.col;
                    const isChecked = this.checked;

                    // Get all column elements for this column (both th and td)
                    const columns = document.querySelectorAll(`.col-${colName}`);

                    columns.forEach(col => {
                        if (isChecked) {
                            col.classList.remove('col-hidden');
                        } else {
                            col.classList.add('col-hidden');
                        }
                    });
                });
            });

            // Print functionality with view-specific handling
            document.getElementById('printBtn').addEventListener('click', function() {
                // Add a print title based on current view
                const printTitles = {
                    'personal': 'CHED Monitoring Scholars - Personal Information',
                    'gradereport': 'CHED Monitoring Scholars - SIKAP DHEI Grade Report',
                    'enrollment': 'CHED Monitoring Scholars - SIKAP DHEI Enrollment Report',
                    'continuing': 'CHED Monitoring Scholars - SIKAP Continuing Eligibility Report'
                };

                // Store original title
                const originalTitle = document.title;

                // Set print-specific title
                document.title = printTitles[currentView] || originalTitle;

                // Print
                window.print();

                // Restore original title
                document.title = originalTitle;
            });

            // Reset columns - enhanced for enrollment view with multiple tables
            document.getElementById('resetCols').addEventListener('click', function() {
                // Check all checkboxes for current view
                const currentFieldChecks = document.querySelectorAll(
                    `.field-check[data-view="${currentView}"]`);

                currentFieldChecks.forEach(checkbox => {
                    checkbox.checked = true;
                    const colName = checkbox.dataset.col;

                    // Show all columns
                    const columns = document.querySelectorAll(`.col-${colName}`);
                    columns.forEach(col => {
                        col.classList.remove('col-hidden');
                    });
                });

                // Show success feedback
                showFeedback('All columns have been reset and are now visible.');
            });

            // Helper function to show feedback messages
            function showFeedback(message) {
                // Remove existing feedback if any
                const existingFeedback = document.getElementById('feedback-message');
                if (existingFeedback) {
                    existingFeedback.remove();
                }

                // Create feedback element
                const feedback = document.createElement('div');
                feedback.id = 'feedback-message';
                feedback.className =
                    'fixed top-4 right-4 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg z-50 transition-opacity duration-300';
                feedback.textContent = message;

                // Add to page
                document.body.appendChild(feedback);

                // Auto-remove after 3 seconds
                setTimeout(() => {
                    feedback.style.opacity = '0';
                    setTimeout(() => feedback.remove(), 300);
                }, 3000);
            }

            // Initialize: Ensure all columns are visible on page load
            document.querySelectorAll('.field-check').forEach(checkbox => {
                if (checkbox.checked) {
                    const colName = checkbox.dataset.col;
                    const columns = document.querySelectorAll(`.col-${colName}`);
                    columns.forEach(col => {
                        col.classList.remove('col-hidden');
                    });
                }
            });

            // Add keyboard shortcuts (optional enhancement)
            document.addEventListener('keydown', function(e) {
                // Ctrl/Cmd + P for print
                if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
                    e.preventDefault();
                    document.getElementById('printBtn').click();
                }

                // Ctrl/Cmd + R for reset columns
                if ((e.ctrlKey || e.metaKey) && e.key === 'r') {
                    e.preventDefault();
                    document.getElementById('resetCols').click();
                }
            });

            // Log current view for debugging (optional - remove in production)
            console.log('Initial view:', currentView);
        });
    </script>
@endsection
