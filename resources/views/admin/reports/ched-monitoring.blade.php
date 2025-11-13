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
                <div class="flex gap-2">
                    <button id="viewPersonal" class="view-tab px-6 py-2.5 rounded-lg font-semibold transition">
                        Personal Information
                    </button>
                    <button id="viewMonitoring"
                        class="view-tab px-6 py-2.5 rounded-lg font-semibold transition bg-blue-600 text-white">
                        Monitoring Data
                    </button>
                </div>
            </div>

            <!-- Field Selection Section -->
            <div class="bg-gray-50 rounded-lg p-4">
                <label class="block text-sm font-medium text-gray-700 mb-3">Select Fields to Display</label>

                <!-- Monitoring Data Fields -->
                <div id="monitoringFields" class="hidden grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-3 text-sm">
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" class="col-toggle rounded text-blue-600 focus:ring-blue-500" data-col="no"
                            checked>
                        <span>No.</span>
                    </label>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" class="col-toggle rounded text-blue-600 focus:ring-blue-500"
                            data-col="last_name" checked>
                        <span>Last Name</span>
                    </label>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" class="col-toggle rounded text-blue-600 focus:ring-blue-500"
                            data-col="first_name" checked>
                        <span>First Name</span>
                    </label>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" class="col-toggle rounded text-blue-600 focus:ring-blue-500"
                            data-col="middle_name" checked>
                        <span>Middle Name</span>
                    </label>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" class="col-toggle rounded text-blue-600 focus:ring-blue-500" data-col="level"
                            checked>
                        <span>Level (MS/PhD)</span>
                    </label>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" class="col-toggle rounded text-blue-600 focus:ring-blue-500"
                            data-col="course" checked>
                        <span>Course</span>
                    </label>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" class="col-toggle rounded text-blue-600 focus:ring-blue-500"
                            data-col="school" checked>
                        <span>School</span>
                    </label>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" class="col-toggle rounded text-blue-600 focus:ring-blue-500"
                            data-col="new_lateral" checked>
                        <span>New/Lateral</span>
                    </label>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" class="col-toggle rounded text-blue-600 focus:ring-blue-500" data-col="pt_ft"
                            checked>
                        <span>Part-Time/Full-Time</span>
                    </label>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" class="col-toggle rounded text-blue-600 focus:ring-blue-500"
                            data-col="duration" checked>
                        <span>Scholarship Duration</span>
                    </label>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" class="col-toggle rounded text-blue-600 focus:ring-blue-500"
                            data-col="date_started" checked>
                        <span>Date Started</span>
                    </label>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" class="col-toggle rounded text-blue-600 focus:ring-blue-500"
                            data-col="expected_completion" checked>
                        <span>Expected Completion</span>
                    </label>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" class="col-toggle rounded text-blue-600 focus:ring-blue-500"
                            data-col="status" checked>
                        <span>Status</span>
                    </label>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" class="col-toggle rounded text-blue-600 focus:ring-blue-500"
                            data-col="remarks" checked>
                        <span>Remarks</span>
                    </label>
                </div>

                <!-- Personal Information Fields -->
                <div id="personalFields" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-3 text-sm">
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" class="col-toggle rounded text-blue-600 focus:ring-blue-500" data-col="no"
                            checked>
                        <span>No.</span>
                    </label>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" class="col-toggle rounded text-blue-600 focus:ring-blue-500"
                            data-col="last_name" checked>
                        <span>Last Name</span>
                    </label>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" class="col-toggle rounded text-blue-600 focus:ring-blue-500"
                            data-col="first_name" checked>
                        <span>First Name</span>
                    </label>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" class="col-toggle rounded text-blue-600 focus:ring-blue-500"
                            data-col="middle_name" checked>
                        <span>Middle Name</span>
                    </label>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" class="col-toggle rounded text-blue-600 focus:ring-blue-500"
                            data-col="suffix" checked>
                        <span>Suffix</span>
                    </label>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" class="col-toggle rounded text-blue-600 focus:ring-blue-500"
                            data-col="street" checked>
                        <span>Street</span>
                    </label>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" class="col-toggle rounded text-blue-600 focus:ring-blue-500"
                            data-col="village" checked>
                        <span>Village</span>
                    </label>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" class="col-toggle rounded text-blue-600 focus:ring-blue-500"
                            data-col="town" checked>
                        <span>Town</span>
                    </label>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" class="col-toggle rounded text-blue-600 focus:ring-blue-500"
                            data-col="province" checked>
                        <span>Province</span>
                    </label>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" class="col-toggle rounded text-blue-600 focus:ring-blue-500"
                            data-col="zipcode" checked>
                        <span>Zipcode</span>
                    </label>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" class="col-toggle rounded text-blue-600 focus:ring-blue-500"
                            data-col="district" checked>
                        <span>District</span>
                    </label>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" class="col-toggle rounded text-blue-600 focus:ring-blue-500"
                            data-col="region" checked>
                        <span>Region</span>
                    </label>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" class="col-toggle rounded text-blue-600 focus:ring-blue-500"
                            data-col="email" checked>
                        <span>Email</span>
                    </label>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" class="col-toggle rounded text-blue-600 focus:ring-blue-500"
                            data-col="birthday" checked>
                        <span>Birthday</span>
                    </label>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" class="col-toggle rounded text-blue-600 focus:ring-blue-500"
                            data-col="contact_no" checked>
                        <span>Contact No.</span>
                    </label>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" class="col-toggle rounded text-blue-600 focus:ring-blue-500"
                            data-col="gender" checked>
                        <span>Gender</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Table Card -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <div class="print-header">
                    <h2 class="text-lg font-bold">DETAILED STATUS REPORT OF CHED SCHOLARSHIP PROGRAM</h2>
                    <div class="text-sm mt-1">University of Science and Technology of Southern Philippines (USTP)</div>
                    <div style="height: 12px;"></div>
                </div>

                <table id="scholarsTable" class="min-w-full text-sm">
                    <thead class="bg-blue-50">
                        <tr>
                            <!-- Monitoring Data Headers -->
                            <th data-col="no" data-view="monitoring"
                                class="px-3 py-3 text-center font-semibold border hidden">No.</th>
                            <th data-col="last_name" data-view="monitoring"
                                class="px-3 py-3 text-left font-semibold border hidden">Last Name</th>
                            <th data-col="first_name" data-view="monitoring"
                                class="px-3 py-3 text-left font-semibold border hidden">First Name</th>
                            <th data-col="middle_name" data-view="monitoring"
                                class="px-3 py-3 text-left font-semibold border hidden">Middle Name</th>
                            <th data-col="level" data-view="monitoring"
                                class="px-3 py-3 text-center font-semibold border hidden">Level (MS/PhD)</th>
                            <th data-col="course" data-view="monitoring"
                                class="px-3 py-3 text-left font-semibold border hidden">Course</th>
                            <th data-col="school" data-view="monitoring"
                                class="px-3 py-3 text-left font-semibold border hidden">School</th>
                            <th data-col="new_lateral" data-view="monitoring"
                                class="px-3 py-3 text-center font-semibold border hidden">New/Lateral</th>
                            <th data-col="pt_ft" data-view="monitoring"
                                class="px-3 py-3 text-center font-semibold border hidden">Part-Time/Full-Time</th>
                            <th data-col="duration" data-view="monitoring"
                                class="px-3 py-3 text-center font-semibold border hidden">Scholarship Duration</th>
                            <th data-col="date_started" data-view="monitoring"
                                class="px-3 py-3 text-center font-semibold border hidden">Date Started (Month & Year)</th>
                            <th data-col="expected_completion" data-view="monitoring"
                                class="px-3 py-3 text-center font-semibold border hidden">Expected Completion (Month &
                                Year)</th>
                            <th data-col="status" data-view="monitoring"
                                class="px-3 py-3 text-center font-semibold border hidden">Status</th>
                            <th data-col="remarks" data-view="monitoring"
                                class="px-3 py-3 text-left font-semibold border hidden">Remarks</th>

                            <!-- Personal Information Headers -->
                            <th data-col="no" data-view="personal" class="px-3 py-3 text-center font-semibold border">
                                No.</th>
                            <th data-col="last_name" data-view="personal"
                                class="px-3 py-3 text-left font-semibold border">Last Name</th>
                            <th data-col="first_name" data-view="personal"
                                class="px-3 py-3 text-left font-semibold border">First Name</th>
                            <th data-col="middle_name" data-view="personal"
                                class="px-3 py-3 text-left font-semibold border">Middle Name</th>
                            <th data-col="suffix" data-view="personal"
                                class="px-3 py-3 text-center font-semibold border">Suffix</th>
                            <th data-col="street" data-view="personal" class="px-3 py-3 text-left font-semibold border">
                                Street</th>
                            <th data-col="village" data-view="personal" class="px-3 py-3 text-left font-semibold border">
                                Village</th>
                            <th data-col="town" data-view="personal" class="px-3 py-3 text-left font-semibold border">
                                Town</th>
                            <th data-col="province" data-view="personal"
                                class="px-3 py-3 text-left font-semibold border">Province</th>
                            <th data-col="zipcode" data-view="personal"
                                class="px-3 py-3 text-center font-semibold border">Zipcode</th>
                            <th data-col="district" data-view="personal"
                                class="px-3 py-3 text-left font-semibold border">District</th>
                            <th data-col="region" data-view="personal" class="px-3 py-3 text-left font-semibold border">
                                Region</th>
                            <th data-col="email" data-view="personal" class="px-3 py-3 text-left font-semibold border">
                                Email</th>
                            <th data-col="birthday" data-view="personal"
                                class="px-3 py-3 text-center font-semibold border">Birthday</th>
                            <th data-col="contact_no" data-view="personal"
                                class="px-3 py-3 text-center font-semibold border">Contact No.</th>
                            <th data-col="gender" data-view="personal"
                                class="px-3 py-3 text-center font-semibold border">Gender</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($monitorings as $index => $monitor)
                            <tr class="odd:bg-white even:bg-gray-50 hover:bg-blue-50 transition">
                                <!-- Monitoring Data Cells -->
                                <td data-col="no" data-view="monitoring" class="px-3 py-2 text-center border hidden">
                                    {{ $index + 1 }}</td>
                                <td data-col="last_name" data-view="monitoring" class="px-3 py-2 border hidden">
                                    {{ $monitor->last_name }}</td>
                                <td data-col="first_name" data-view="monitoring" class="px-3 py-2 border hidden">
                                    {{ $monitor->first_name }}</td>
                                <td data-col="middle_name" data-view="monitoring" class="px-3 py-2 border hidden">
                                    {{ $monitor->middle_name }}</td>
                                <td data-col="level" data-view="monitoring" class="px-3 py-2 text-center border hidden">
                                    {{ $monitor->degree_type }}</td>
                                <td data-col="course" data-view="monitoring" class="px-3 py-2 border hidden">
                                    {{ $monitor->course }}</td>
                                <td data-col="school" data-view="monitoring" class="px-3 py-2 border hidden">
                                    {{ $monitor->school }}</td>
                                <td data-col="new_lateral" data-view="monitoring"
                                    class="px-3 py-2 text-center border hidden">{{ $monitor->new_or_lateral }}</td>
                                <td data-col="pt_ft" data-view="monitoring" class="px-3 py-2 text-center border hidden">
                                    {{ $monitor->enrollment_type }}</td>
                                <td data-col="duration" data-view="monitoring"
                                    class="px-3 py-2 text-center border hidden">{{ $monitor->scholarship_duration }}</td>
                                <td data-col="date_started" data-view="monitoring"
                                    class="px-3 py-2 text-center border hidden">{{ $monitor->date_started }}</td>
                                <td data-col="expected_completion" data-view="monitoring"
                                    class="px-3 py-2 text-center border hidden">{{ $monitor->expected_completion }}</td>
                                <td data-col="status" data-view="monitoring" class="px-3 py-2 text-center border hidden">
                                    {{ $monitor->status_code }}</td>
                                <td data-col="remarks" data-view="monitoring" class="px-3 py-2 border hidden">
                                    {{ $monitor->remarks }}</td>

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
                                <td data-col="birthday" data-view="personal" class="px-3 py-2 text-center border">
                                    {{ $monitor->birthday ?? '-' }}</td>
                                <td data-col="contact_no" data-view="personal" class="px-3 py-2 text-center border">
                                    {{ $monitor->contact_no ?? '-' }}</td>
                                <td data-col="gender" data-view="personal" class="px-3 py-2 text-center border">
                                    {{ $monitor->gender ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="30" class="text-center p-6 text-gray-500">No CHED monitoring data found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        (function() {
            const table = document.getElementById('scholarsTable');
            const printBtn = document.getElementById('printBtn');
            const resetBtn = document.getElementById('resetCols');
            const viewMonitoringBtn = document.getElementById('viewMonitoring');
            const viewPersonalBtn = document.getElementById('viewPersonal');
            const monitoringFields = document.getElementById('monitoringFields');
            const personalFields = document.getElementById('personalFields');

            const STORAGE_KEY_VIEW = 'ched_monitoring_view_v1';
            const STORAGE_KEY_COLS = 'ched_monitoring_cols_v1';

            let currentView = localStorage.getItem(STORAGE_KEY_VIEW) || 'personal';

            const defaultMonitoringCols = [
                'no', 'last_name', 'first_name', 'middle_name', 'level', 'course', 'school',
                'new_lateral', 'pt_ft', 'duration', 'date_started', 'expected_completion', 'status', 'remarks'
            ];

            const defaultPersonalCols = [
                'no', 'last_name', 'first_name', 'middle_name', 'suffix', 'street', 'village',
                'town', 'province', 'zipcode', 'district', 'region', 'email', 'birthday', 'contact_no', 'gender'
            ];

            let saved = localStorage.getItem(STORAGE_KEY_COLS);
            let visibleCols = saved ? JSON.parse(saved) : {
                monitoring: defaultMonitoringCols.slice(),
                personal: defaultPersonalCols.slice()
            };

            function switchView(view) {
                currentView = view;
                localStorage.setItem(STORAGE_KEY_VIEW, view);

                if (view === 'monitoring') {
                    viewMonitoringBtn.classList.add('bg-blue-600', 'text-white');
                    viewMonitoringBtn.classList.remove('bg-gray-200', 'text-gray-700');
                    viewPersonalBtn.classList.remove('bg-blue-600', 'text-white');
                    viewPersonalBtn.classList.add('bg-gray-200', 'text-gray-700');
                    monitoringFields.classList.remove('hidden');
                    personalFields.classList.add('hidden');
                } else {
                    viewPersonalBtn.classList.add('bg-blue-600', 'text-white');
                    viewPersonalBtn.classList.remove('bg-gray-200', 'text-gray-700');
                    viewMonitoringBtn.classList.remove('bg-blue-600', 'text-white');
                    viewMonitoringBtn.classList.add('bg-gray-200', 'text-gray-700');
                    personalFields.classList.remove('hidden');
                    monitoringFields.classList.add('hidden');
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

            viewMonitoringBtn.addEventListener('click', () => switchView('monitoring'));
            viewPersonalBtn.addEventListener('click', () => switchView('personal'));

            resetBtn.addEventListener('click', function() {
                if (currentView === 'monitoring') {
                    visibleCols.monitoring = defaultMonitoringCols.slice();
                } else {
                    visibleCols.personal = defaultPersonalCols.slice();
                }
                initCheckboxes();
                applyColumnVisibility();
                savePrefs();
            });

            printBtn.addEventListener('click', function() {
                savePrefs();
                window.print();
            });

            // Initialize on load
            switchView(currentView);
        })();
    </script>
@endsection
