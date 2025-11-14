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


                <!-- Field Selection Section -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-3">Select Fields to Display</label>

                    <!-- ================================
                     PERSONAL INFORMATION FIELDS
                ================================= -->
                    <div id="personalFields" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-3 text-sm">

                        @foreach ([
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
        ] as $col => $label)
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="checkbox" class="field-toggle rounded text-blue-600 focus:ring-blue-500"
                                    data-view="personal" data-col="{{ $col }}" checked>
                                <span>{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>

                    <!-- ================================
                     GRADE REPORT FIELDS
                ================================= -->
                    <div id="gradeReportFields" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-3 text-sm hidden">

                        @foreach ([
            'no' => 'No.',
            'name' => 'Name',
            'application_number' => 'Application Number',
            'degree_program' => 'Degree Program',
            'enrolled_subjects' => 'No. of Enrolled Subjects',
            'subjects_passed' => 'No. of Subjects Passed',
            'incomplete_grades' => 'No. of Incomplete Grades',
            'subjects_failed' => 'No. of Subjects Failed',
            'no_grades' => 'No. of No Grades',
            'not_credited' => 'No. of Not Credited',
            'status' => 'Status',
            'gpa' => 'Grade Point Average',
            'remarks' => 'Remarks',
        ] as $col => $label)
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="checkbox" class="field-toggle rounded text-blue-600 focus:ring-blue-500"
                                    data-view="grade" data-col="{{ $col }}" checked>
                                <span>{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>

                    <!-- ================================
                     ENROLLMENT REPORT FIELDS
                ================================= -->
                    <div id="enrollmentFields" class="text-sm hidden">

                        <!-- ========== SET A ========== -->
                        <div class="mb-4">
                            <h4 class="font-semibold text-gray-800 mb-2">A. ENROLLED SCHOLARS, WITH NO ISSUES</h4>

                            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-3">
                                @foreach ([
            'no' => 'No.',
            'name' => 'Name',
            'application_number' => 'Application Number',
            'degree_program' => 'Degree Program',
            'status' => 'Status',
            'units_enrolled' => 'Number of Units Enrolled',
            'retaken_subjects' => 'Re-enrolled/Retaken?',
            'remarks' => 'Remarks',
        ] as $col => $label)
                                    <label class="flex items-center space-x-2 cursor-pointer">
                                        <input type="checkbox"
                                            class="field-toggle rounded text-blue-600 focus:ring-blue-500"
                                            data-view="enrollment" data-set="a" data-col="{{ $col }}" checked>
                                        <span>{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- ========== SET B ========== -->
                        <div class="mb-4">
                            <h4 class="font-semibold text-gray-800 mb-2">B. ENROLLED SCHOLARS, BUT WITH ISSUES</h4>

                            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-3">
                                @foreach ([
            'no' => 'No.',
            'name' => 'Name',
            'application_number' => 'Application Number',
            'degree_program' => 'Degree Program',
            'status' => 'Status',
            'other_status' => 'If Others, State Status',
            'description' => 'Short Description',
        ] as $col => $label)
                                    <label class="flex items-center space-x-2 cursor-pointer">
                                        <input type="checkbox"
                                            class="field-toggle rounded text-blue-600 focus:ring-blue-500"
                                            data-view="enrollment" data-set="b" data-col="{{ $col }}" checked>
                                        <span>{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- ========== SET C ========== -->
                        <div class="mb-4">
                            <h4 class="font-semibold text-gray-800 mb-2">C. SCHOLARS EXPECTED TO ENROLL, BUT DID NOT ENROLL
                            </h4>

                            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-3">
                                @foreach ([
            'no' => 'No.',
            'name' => 'Name',
            'application_number' => 'Application Number',
            'degree_program' => 'Degree Program',
            'status' => 'Status',
            'other_status' => 'If Others, State Status',
            'description' => 'Short Description',
        ] as $col => $label)
                                    <label class="flex items-center space-x-2 cursor-pointer">
                                        <input type="checkbox"
                                            class="field-toggle rounded text-blue-600 focus:ring-blue-500"
                                            data-view="enrollment" data-set="c" data-col="{{ $col }}" checked>
                                        <span>{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- ========== SET D ========== -->
                        <div class="mb-4">
                            <h4 class="font-semibold text-gray-800 mb-2">D. SCHOLARS NO LONGER EXPECTED TO ENROLL</h4>

                            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-3">
                                @foreach ([
            'no' => 'No.',
            'name' => 'Name',
            'application_number' => 'Application Number',
            'degree_program' => 'Degree Program',
            'status' => 'Status',
            'other_status' => 'If Others, State Status',
            'description' => 'Short Description',
        ] as $col => $label)
                                    <label class="flex items-center space-x-2 cursor-pointer">
                                        <input type="checkbox"
                                            class="field-toggle rounded text-blue-600 focus:ring-blue-500"
                                            data-view="enrollment" data-set="d" data-col="{{ $col }}" checked>
                                        <span>{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                    </div>
                </div>
                <!-- Table Card -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <div class="print-header">
                            <h2 class="text-lg font-bold">DETAILED STATUS REPORT OF CHED SCHOLARSHIP PROGRAM</h2>
                            <div class="text-sm mt-1">University of Science and Technology of Southern Philippines
                                (USTP)
                            </div>
                            <div style="height: 12px;"></div>
                        </div>

                        <table id="scholarsTable" class="min-w-full text-sm">
                            <thead class="bg-blue-50">
                                <tr>
                                    <!-- Personal Information Headers -->
                                    <th data-col="no" data-view="personal"
                                        class="px-3 py-3 text-center font-semibold border">No.</th>
                                    <th data-col="last_name" data-view="personal"
                                        class="px-3 py-3 text-left font-semibold border">Last Name</th>
                                    <th data-col="first_name" data-view="personal"
                                        class="px-3 py-3 text-left font-semibold border">First Name</th>
                                    <th data-col="middle_name" data-view="personal"
                                        class="px-3 py-3 text-left font-semibold border">Middle Name</th>
                                    <th data-col="suffix" data-view="personal"
                                        class="px-3 py-3 text-center font-semibold border">Suffix</th>
                                    <th data-col="street" data-view="personal"
                                        class="px-3 py-3 text-left font-semibold border">Street</th>
                                    <th data-col="village" data-view="personal"
                                        class="px-3 py-3 text-left font-semibold border">Village</th>
                                    <th data-col="town" data-view="personal"
                                        class="px-3 py-3 text-left font-semibold border">Town</th>
                                    <th data-col="province" data-view="personal"
                                        class="px-3 py-3 text-left font-semibold border">Province</th>
                                    <th data-col="zipcode" data-view="personal"
                                        class="px-3 py-3 text-center font-semibold border">Zipcode</th>
                                    <th data-col="district" data-view="personal"
                                        class="px-3 py-3 text-left font-semibold border">District</th>
                                    <th data-col="region" data-view="personal"
                                        class="px-3 py-3 text-left font-semibold border">Region</th>
                                    <th data-col="email" data-view="personal"
                                        class="px-3 py-3 text-left font-semibold border">Email</th>
                                    <th data-col="birthday" data-view="personal"
                                        class="px-3 py-3 text-center font-semibold border">Birthday</th>
                                    <th data-col="contact_no" data-view="personal"
                                        class="px-3 py-3 text-center font-semibold border">Contact No.</th>
                                    <th data-col="gender" data-view="personal"
                                        class="px-3 py-3 text-center font-semibold border">Gender</th>

                                    <!-- Grade Report Headers -->
                                    <th data-col="no" data-view="grade"
                                        class="px-3 py-3 text-center font-semibold border">
                                        No.</th>
                                    <th data-col="name" data-view="grade"
                                        class="px-3 py-3 text-left font-semibold border">
                                        Name</th>
                                    <th data-col="application_number" data-view="grade"
                                        class="px-3 py-3 text-center font-semibold border">Application Number</th>
                                    <th data-col="degree_program" data-view="grade"
                                        class="px-3 py-3 text-left font-semibold border">Degree Program</th>
                                    <th data-col="enrolled_subjects" data-view="grade"
                                        class="px-3 py-3 text-center font-semibold border">No. of Enrolled Subjects
                                    </th>
                                    <th data-col="subjects_passed" data-view="grade"
                                        class="px-3 py-3 text-center font-semibold border">No. of Subjects Passed</th>
                                    <th data-col="incomplete_grades" data-view="grade"
                                        class="px-3 py-3 text-center font-semibold border">No. of Incomplete Grades
                                    </th>
                                    <th data-col="subjects_failed" data-view="grade"
                                        class="px-3 py-3 text-center font-semibold border">No. of Subjects Failed</th>
                                    <th data-col="no_grades" data-view="grade"
                                        class="px-3 py-3 text-center font-semibold border">No. of No Grades</th>
                                    <th data-col="not_credited" data-view="grade"
                                        class="px-3 py-3 text-center font-semibold border">No. of Not Credited</th>
                                    <th data-col="status" data-view="grade"
                                        class="px-3 py-3 text-center font-semibold border">Status</th>
                                    <th data-col="gpa" data-view="grade"
                                        class="px-3 py-3 text-center font-semibold border">
                                        Grade Point Average</th>
                                    <th data-col="remarks" data-view="grade"
                                        class="px-3 py-3 text-left font-semibold border">Remarks</th>

                                    <!-- Enrollment Report Headers - Section A -->
                                    <th data-col="section_a_no" data-view="enrollment"
                                        class="px-3 py-3 text-center font-semibold border bg-green-50" colspan="8">
                                        A.
                                        ENROLLED SCHOLARS, WITH NO ISSUES</th>
                                    <th data-col="no" data-view="enrollment"
                                        class="px-3 py-3 text-center font-semibold border hidden">No.</th>
                                    <th data-col="name" data-view="enrollment"
                                        class="px-3 py-3 text-left font-semibold border hidden">Name</th>
                                    <th data-col="application_number" data-view="enrollment"
                                        class="px-3 py-3 text-center font-semibold border hidden">Application Number
                                    </th>
                                    <th data-col="degree_program" data-view="enrollment"
                                        class="px-3 py-3 text-left font-semibold border hidden">Degree Program</th>
                                    <th data-col="enrollment_status_a" data-view="enrollment"
                                        class="px-3 py-3 text-left font-semibold border hidden">Status</th>
                                    <th data-col="units_enrolled" data-view="enrollment"
                                        class="px-3 py-3 text-center font-semibold border hidden">Number of Units
                                        Enrolled
                                    </th>
                                    <th data-col="retaken_subjects" data-view="enrollment"
                                        class="px-3 py-3 text-center font-semibold border hidden">Is Re-enrolled or
                                        Retaken?
                                    </th>
                                    <th data-col="remarks_a" data-view="enrollment"
                                        class="px-3 py-3 text-left font-semibold border hidden">Remarks</th>

                                    <!-- Enrollment Report Headers - Section B -->
                                    <th data-col="section_b_no" data-view="enrollment"
                                        class="px-3 py-3 text-center font-semibold border bg-yellow-50" colspan="7">B.
                                        ENROLLED SCHOLARS, BUT WITH ISSUES</th>
                                    <th data-col="no_b" data-view="enrollment"
                                        class="px-3 py-3 text-center font-semibold border hidden">No.</th>
                                    <th data-col="name_b" data-view="enrollment"
                                        class="px-3 py-3 text-left font-semibold border hidden">Name</th>
                                    <th data-col="application_number_b" data-view="enrollment"
                                        class="px-3 py-3 text-center font-semibold border hidden">Application Number
                                    </th>
                                    <th data-col="degree_program_b" data-view="enrollment"
                                        class="px-3 py-3 text-left font-semibold border hidden">Degree Program</th>
                                    <th data-col="enrollment_status_b" data-view="enrollment"
                                        class="px-3 py-3 text-left font-semibold border hidden">Status</th>
                                    <th data-col="status_b_others" data-view="enrollment"
                                        class="px-3 py-3 text-left font-semibold border hidden">If Others, State Status
                                    </th>
                                    <th data-col="description_b" data-view="enrollment"
                                        class="px-3 py-3 text-left font-semibold border hidden">Short Description</th>

                                    <!-- Enrollment Report Headers - Section C -->
                                    <th data-col="section_c_no" data-view="enrollment"
                                        class="px-3 py-3 text-center font-semibold border bg-orange-50" colspan="7">C.
                                        SCHOLARS EXPECTED TO ENROLL, BUT DID NOT ENROLL</th>
                                    <th data-col="no_c" data-view="enrollment"
                                        class="px-3 py-3 text-center font-semibold border hidden">No.</th>
                                    <th data-col="name_c" data-view="enrollment"
                                        class="px-3 py-3 text-left font-semibold border hidden">Name</th>
                                    <th data-col="application_number_c" data-view="enrollment"
                                        class="px-3 py-3 text-center font-semibold border hidden">Application Number
                                    </th>
                                    <th data-col="degree_program_c" data-view="enrollment"
                                        class="px-3 py-3 text-left font-semibold border hidden">Degree Program</th>
                                    <th data-col="enrollment_status_c" data-view="enrollment"
                                        class="px-3 py-3 text-left font-semibold border hidden">Status</th>
                                    <th data-col="status_c_others" data-view="enrollment"
                                        class="px-3 py-3 text-left font-semibold border hidden">If Others, State Status
                                    </th>
                                    <th data-col="description_c" data-view="enrollment"
                                        class="px-3 py-3 text-left font-semibold border hidden">Short Description</th>

                                    <!-- Enrollment Report Headers - Section D -->
                                    <th data-col="section_d_no" data-view="enrollment"
                                        class="px-3 py-3 text-center font-semibold border bg-red-50" colspan="7">D.
                                        SCHOLARS NO LONGER EXPECTED TO ENROLL</th>
                                    <th data-col="no_d" data-view="enrollment"
                                        class="px-3 py-3 text-center font-semibold border hidden">No.</th>
                                    <th data-col="name_d" data-view="enrollment"
                                        class="px-3 py-3 text-left font-semibold border hidden">Name</th>
                                    <th data-col="application_number_d" data-view="enrollment"
                                        class="px-3 py-3 text-center font-semibold border hidden">Application Number
                                    </th>
                                    <th data-col="degree_program_d" data-view="enrollment"
                                        class="px-3 py-3 text-left font-semibold border hidden">Degree Program</th>
                                    <th data-col="enrollment_status_d" data-view="enrollment"
                                        class="px-3 py-3 text-left font-semibold border hidden">Status</th>
                                    <th data-col="status_d_others" data-view="enrollment"
                                        class="px-3 py-3 text-left font-semibold border hidden">If Others, State Status
                                    </th>
                                    <th data-col="description_d" data-view="enrollment"
                                        class="px-3 py-3 text-left font-semibold border hidden">Short Description</th>

                                    <!-- Continuing Eligibility Headers - Section A -->
                                    <th data-col="section_a_continuing" data-view="continuing"
                                        class="px-3 py-3 text-center font-semibold border bg-blue-50" colspan="12">
                                        A.
                                        CONTINUING SCHOLARS</th>
                                    <th data-col="scholar_name" data-view="continuing"
                                        class="px-3 py-3 text-left font-semibold border hidden">Name of Scholar</th>
                                    <th data-col="application_number_cont" data-view="continuing"
                                        class="px-3 py-3 text-center font-semibold border hidden">Application Number
                                    </th>
                                    <th data-col="scholarship_type" data-view="continuing"
                                        class="px-3 py-3 text-center font-semibold border hidden">Scholarship Type</th>
                                    <th data-col="degree_program_cont" data-view="continuing"
                                        class="px-3 py-3 text-left font-semibold border hidden">Degree Program</th>
                                    <th data-col="year_approval" data-view="continuing"
                                        class="px-3 py-3 text-center font-semibold border hidden">Year of Approval</th>
                                    <th data-col="last_term" data-view="continuing"
                                        class="px-3 py-3 text-center font-semibold border hidden">Last Term of
                                        Enrollment
                                    </th>
                                    <th data-col="good_standing" data-view="continuing"
                                        class="px-3 py-3 text-center font-semibold border hidden">Good Academic
                                        Standing?
                                    </th>
                                    <th data-col="not_good_standing_reason" data-view="continuing"
                                        class="px-3 py-3 text-left font-semibold border hidden">If Not Good Standing,
                                        Explanation</th>
                                    <th data-col="expected_on_time" data-view="continuing"
                                        class="px-3 py-3 text-center font-semibold border hidden">Expected to Complete
                                        On
                                        Time?
                                    </th>
                                    <th data-col="not_on_time_reason" data-view="continuing"
                                        class="px-3 py-3 text-left font-semibold border hidden">Why Not On Time</th>
                                    <th data-col="recommendation" data-view="continuing"
                                        class="px-3 py-3 text-left font-semibold border hidden">Recommendation for
                                        Status
                                    </th>
                                    <th data-col="recommendation_rationale" data-view="continuing"
                                        class="px-3 py-3 text-left font-semibold border hidden">Rationale for
                                        Recommendation
                                    </th>

                                    <!-- Continuing Eligibility Headers - Section B -->
                                    <th data-col="section_b_continuing" data-view="continuing"
                                        class="px-3 py-3 text-center font-semibold border bg-green-50" colspan="8">
                                        B.
                                        SCHOLARS WHO HAVE COMPLETED THEIR DEGREES</th>
                                    <th data-col="no_completed" data-view="continuing"
                                        class="px-3 py-3 text-center font-semibold border hidden">No.</th>
                                    <th data-col="scholar_name_completed" data-view="continuing"
                                        class="px-3 py-3 text-left font-semibold border hidden">Name of Scholar</th>
                                    <th data-col="application_number_completed" data-view="continuing"
                                        class="px-3 py-3 text-center font-semibold border hidden">Application Number
                                    </th>
                                    <th data-col="scholarship_type_completed" data-view="continuing"
                                        class="px-3 py-3 text-center font-semibold border hidden">Scholarship Type</th>
                                    <th data-col="degree_program_completed" data-view="continuing"
                                        class="px-3 py-3 text-left font-semibold border hidden">Degree Program</th>
                                    <th data-col="academic_year_grad" data-view="continuing"
                                        class="px-3 py-3 text-center font-semibold border hidden">Academic Year of
                                        Graduation
                                    </th>
                                    <th data-col="term_grad" data-view="continuing"
                                        class="px-3 py-3 text-center font-semibold border hidden">Term of Graduation
                                    </th>
                                    <th data-col="remarks_completed" data-view="continuing"
                                        class="px-3 py-3 text-left font-semibold border hidden">Remarks</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($monitorings as $index => $monitor)
                                    <tr class="odd:bg-white even:bg-gray-50 hover:bg-blue-50 transition">
                                        <!-- Personal Information Cells -->
                                        <td data-col="no" data-view="personal" class="px-3 py-2 text-center border">
                                            {{ $index + 1 }}</td>
                                        <td data-col="last_name" data-view="personal" class="px-3 py-2 border">
                                            {{ $monitor->last_name }}</td>
                                        <td data-col="first_name" data-view="personal" class="px-3 py-2 border">
                                            {{ $monitor->first_name }}</td>
                                        <td data-col="middle_name" data-view="personal" class="px-3 py-2 border">
                                            {{ $monitor->middle_name }}</td>
                                        <td data-col="suffix" data-view="personal" class="px-3 py-2 text-center border">
                                            {{ $monitor->suffix ?? '-' }}</td>
                                        <td data-col="street" data-view="personal" class="px-3 py-2 border">
                                            {{ $monitor->street ?? '-' }}</td>
                                        <td data-col="village" data-view="personal" class="px-3 py-2 border">
                                            {{ $monitor->village ?? '-' }}</td>
                                        <td data-col="town" data-view="personal" class="px-3 py-2 border">
                                            {{ $monitor->town ?? '-' }}</td>
                                        <td data-col="province" data-view="personal" class="px-3 py-2 border">
                                            {{ $monitor->province ?? '-' }}</td>
                                        <td data-col="zipcode" data-view="personal" class="px-3 py-2 text-center border">
                                            {{ $monitor->zipcode ?? '-' }}</td>
                                        <td data-col="district" data-view="personal" class="px-3 py-2 border">
                                            {{ $monitor->district ?? '-' }}</td>
                                        <td data-col="region" data-view="personal" class="px-3 py-2 border">
                                            {{ $monitor->region ?? '-' }}</td>
                                        <td data-col="email" data-view="personal" class="px-3 py-2 border">
                                            {{ $monitor->email ?? '-' }}</td>
                                        <td data-col="birthday" data-view="personal"
                                            class="px-3 py-2 text-center border">
                                            {{ $monitor->birthday ?? '-' }}</td>
                                        <td data-col="contact_no" data-view="personal"
                                            class="px-3 py-2 text-center border">
                                            {{ $monitor->contact_no ?? '-' }}</td>
                                        <td data-col="gender" data-view="personal" class="px-3 py-2 text-center border">
                                            {{ $monitor->gender ?? '-' }}</td>

                                        <!-- Grade Report Cells -->
                                        <td data-col="no" data-view="grade" class="px-3 py-2 text-center border">
                                            {{ $index + 1 }}</td>
                                        <td data-col="name" data-view="grade" class="px-3 py-2 border">
                                            {{ $monitor->first_name }} {{ $monitor->last_name }}</td>
                                        <td data-col="application_number" data-view="grade"
                                            class="px-3 py-2 text-center border">
                                            {{ $monitor->application_number ?? '-' }}
                                        </td>
                                        <td data-col="degree_program" data-view="grade" class="px-3 py-2 border">
                                            {{ $monitor->degree_program ?? '-' }}</td>
                                        <td data-col="enrolled_subjects" data-view="grade"
                                            class="px-3 py-2 text-center border">
                                            {{ $monitor->enrolled_subjects ?? '-' }}
                                        </td>
                                        <td data-col="subjects_passed" data-view="grade"
                                            class="px-3 py-2 text-center border">
                                            {{ $monitor->subjects_passed ?? '-' }}
                                        </td>
                                        <td data-col="incomplete_grades" data-view="grade"
                                            class="px-3 py-2 text-center border">
                                            {{ $monitor->incomplete_grades ?? '-' }}
                                        </td>
                                        <td data-col="subjects_failed" data-view="grade"
                                            class="px-3 py-2 text-center border">
                                            {{ $monitor->subjects_failed ?? '-' }}
                                        </td>
                                        <td data-col="no_grades" data-view="grade" class="px-3 py-2 text-center border">
                                            {{ $monitor->no_grades ?? '-' }}</td>
                                        <td data-col="not_credited" data-view="grade"
                                            class="px-3 py-2 text-center border">
                                            {{ $monitor->not_credited ?? '-' }}</td>
                                        <td data-col="status" data-view="grade" class="px-3 py-2 text-center border">
                                            {{ $monitor->status ?? '-' }}</td>
                                        <td data-col="gpa" data-view="grade" class="px-3 py-2 text-center border">
                                            {{ $monitor->gpa ?? '-' }}</td>
                                        <td data-col="remarks" data-view="grade" class="px-3 py-2 border">
                                            {{ $monitor->remarks ?? '-' }}</td>

                                        <!-- Enrollment Report Cells - You'll need to add the actual data here -->
                                        <!-- Section A cells would go here -->
                                        <!-- Section B cells would go here -->
                                        <!-- Section C cells would go here -->
                                        <!-- Section D cells would go here -->

                                        <!-- Continuing Eligibility Cells - You'll need to add the actual data here -->
                                        <!-- Section A cells would go here -->
                                        <!-- Section B cells would go here -->
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="100" class="text-center p-6 text-gray-500">No CHED monitoring
                                            data
                                            found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <script>
                    (function() {
                        const table = document.getElementById('scholarsTable');
                        const printBtn = document.getElementById('printBtn');
                        const resetBtn = document.getElementById('resetCols');

                        // View buttons
                        const viewMonitoringBtn = document.getElementById('viewMonitoring');
                        const viewPersonalBtn = document.getElementById('viewPersonal');
                        const viewGradereportBtn = document.getElementById('viewGradereport');
                        const viewEnrollmentBtn = document.getElementById('viewEnrollment');
                        const viewContinuingBtn = document.getElementById('viewContinuing');

                        // Field sections
                        const monitoringFields = document.getElementById('monitoringFields');
                        const personalFields = document.getElementById('personalFields');
                        const gradeReportFields = document.getElementById('gradeReportFields');
                        const enrollmentFields = document.getElementById('enrollmentFields');
                        const continuingFields = document.getElementById('continuingFields');

                        const STORAGE_KEY_VIEW = 'ched_monitoring_view_v1';
                        const STORAGE_KEY_COLS = 'ched_monitoring_cols_v1';

                        let currentView = localStorage.getItem(STORAGE_KEY_VIEW) || 'personal';

                        // Default columns for each view
                        const defaultMonitoringCols = [
                            'no', 'last_name', 'first_name', 'middle_name', 'level', 'course', 'school',
                            'new_lateral', 'pt_ft', 'duration', 'date_started', 'expected_completion', 'status', 'remarks'
                        ];

                        const defaultPersonalCols = [
                            'no', 'last_name', 'first_name', 'middle_name', 'suffix', 'street', 'village',
                            'town', 'province', 'zipcode', 'district', 'region', 'email', 'birthday', 'contact_no', 'gender'
                        ];

                        const defaultGradeReportCols = [
                            'no', 'name', 'application_number', 'degree_program', 'enrolled_subjects',
                            'subjects_passed', 'incomplete_grades', 'subjects_failed', 'no_grades',
                            'not_credited', 'status', 'gpa', 'remarks'
                        ];

                        const defaultEnrollmentCols = [
                            'no', 'name', 'application_number' // Add more as needed
                        ];

                        const defaultContinuingCols = [
                            'no', 'name', 'application_number' // Add more as needed
                        ];

                        // Load saved preferences or use defaults
                        let saved = localStorage.getItem(STORAGE_KEY_COLS);
                        let visibleCols = saved ? JSON.parse(saved) : {
                            monitoring: defaultMonitoringCols.slice(),
                            personal: defaultPersonalCols.slice(),
                            grade: defaultGradeReportCols.slice(),
                            enrollment: defaultEnrollmentCols.slice(),
                            continuing: defaultContinuingCols.slice()
                        };

                        function switchView(view) {
                            currentView = view;
                            localStorage.setItem(STORAGE_KEY_VIEW, view);

                            // Remove active class from all buttons
                            const allButtons = [viewMonitoringBtn, viewPersonalBtn, viewGradereportBtn, viewEnrollmentBtn,
                                viewContinuingBtn
                            ];
                            allButtons.forEach(btn => {
                                if (btn) {
                                    btn.classList.remove('bg-blue-600', 'text-white');
                                    btn.classList.add('bg-gray-200', 'text-gray-700');
                                }
                            });

                            // Hide all field sections
                            const allFields = [monitoringFields, personalFields, gradeReportFields, enrollmentFields,
                                continuingFields
                            ];
                            allFields.forEach(field => {
                                if (field) field.classList.add('hidden');
                            });

                            // Activate the selected view
                            if (view === 'monitoring' && viewMonitoringBtn && monitoringFields) {
                                viewMonitoringBtn.classList.add('bg-blue-600', 'text-white');
                                viewMonitoringBtn.classList.remove('bg-gray-200', 'text-gray-700');
                                monitoringFields.classList.remove('hidden');
                            } else if (view === 'personal' && viewPersonalBtn && personalFields) {
                                viewPersonalBtn.classList.add('bg-blue-600', 'text-white');
                                viewPersonalBtn.classList.remove('bg-gray-200', 'text-gray-700');
                                personalFields.classList.remove('hidden');
                            } else if (view === 'grade' && viewGradereportBtn && gradeReportFields) {
                                viewGradereportBtn.classList.add('bg-blue-600', 'text-white');
                                viewGradereportBtn.classList.remove('bg-gray-200', 'text-gray-700');
                                gradeReportFields.classList.remove('hidden');
                            } else if (view === 'enrollment' && viewEnrollmentBtn && enrollmentFields) {
                                viewEnrollmentBtn.classList.add('bg-blue-600', 'text-white');
                                viewEnrollmentBtn.classList.remove('bg-gray-200', 'text-gray-700');
                                enrollmentFields.classList.remove('hidden');
                            } else if (view === 'continuing' && viewContinuingBtn && continuingFields) {
                                viewContinuingBtn.classList.add('bg-blue-600', 'text-white');
                                viewContinuingBtn.classList.remove('bg-gray-200', 'text-gray-700');
                                continuingFields.classList.remove('hidden');
                            }

                            initCheckboxes();
                            applyColumnVisibility();
                        }

                        function initCheckboxes() {
                            document.querySelectorAll('.col-toggle').forEach(cb => {
                                const col = cb.getAttribute('data-col');
                                cb.checked = visibleCols[currentView].includes(col);
                            });
                        }

                        function applyColumnVisibility() {
                            table.querySelectorAll('th[data-view]').forEach(th => {
                                const view = th.getAttribute('data-view');
                                const col = th.getAttribute('data-col');

                                if (view !== currentView) {
                                    th.classList.add('hidden');
                                } else if (!visibleCols[currentView].includes(col)) {
                                    th.classList.add('col-hidden');
                                    th.classList.remove('hidden');
                                } else {
                                    th.classList.remove('col-hidden', 'hidden');
                                }
                            });

                            table.querySelectorAll('td[data-view]').forEach(td => {
                                const view = td.getAttribute('data-view');
                                const col = td.getAttribute('data-col');

                                if (view !== currentView) {
                                    td.classList.add('hidden');
                                } else if (!visibleCols[currentView].includes(col)) {
                                    td.classList.add('col-hidden');
                                    td.classList.remove('hidden');
                                } else {
                                    td.classList.remove('col-hidden', 'hidden');
                                }
                            });
                        }

                        function savePrefs() {
                            localStorage.setItem(STORAGE_KEY_COLS, JSON.stringify(visibleCols));
                        }

                        // Checkbox change event
                        document.querySelectorAll('.col-toggle').forEach(cb => {
                            cb.addEventListener('change', function() {
                                const col = this.getAttribute('data-col');
                                if (this.checked) {
                                    if (!visibleCols[currentView].includes(col)) {
                                        visibleCols[currentView].push(col);
                                    }
                                } else {
                                    visibleCols[currentView] = visibleCols[currentView].filter(c => c !== col);
                                }
                                applyColumnVisibility();
                                savePrefs();
                            });
                        });

                        // View button event listeners
                        if (viewMonitoringBtn) {
                            viewMonitoringBtn.addEventListener('click', () => switchView('monitoring'));
                        }
                        if (viewPersonalBtn) {
                            viewPersonalBtn.addEventListener('click', () => switchView('personal'));
                        }
                        if (viewGradereportBtn) {
                            viewGradereportBtn.addEventListener('click', () => switchView('grade'));
                        }
                        if (viewEnrollmentBtn) {
                            viewEnrollmentBtn.addEventListener('click', () => switchView('enrollment'));
                        }
                        if (viewContinuingBtn) {
                            viewContinuingBtn.addEventListener('click', () => switchView('continuing'));
                        }

                        // Reset button
                        if (resetBtn) {
                            resetBtn.addEventListener('click', function() {
                                if (currentView === 'monitoring') {
                                    visibleCols.monitoring = defaultMonitoringCols.slice();
                                } else if (currentView === 'personal') {
                                    visibleCols.personal = defaultPersonalCols.slice();
                                } else if (currentView === 'grade') {
                                    visibleCols.grade = defaultGradeReportCols.slice();
                                } else if (currentView === 'enrollment') {
                                    visibleCols.enrollment = defaultEnrollmentCols.slice();
                                } else if (currentView === 'continuing') {
                                    visibleCols.continuing = defaultContinuingCols.slice();
                                }
                                initCheckboxes();
                                applyColumnVisibility();
                                savePrefs();
                            });
                        }

                        // Print button
                        if (printBtn) {
                            printBtn.addEventListener('click', function() {
                                savePrefs();
                                window.print();
                            });
                        }

                        // Initialize on load
                        switchView(currentView);
                    })();
                </script>
            @endsection
