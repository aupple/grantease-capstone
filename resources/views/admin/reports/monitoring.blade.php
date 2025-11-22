@extends('layouts.admin-layout')

@section('content')
    @php
        if (!function_exists('getLocationName')) {
            function getLocationName($code, $type = 'city')
            {
                $jsonUrls = [
                    'province' => 'https://psgc.gitlab.io/api/provinces/',
                    'city' => 'https://psgc.gitlab.io/api/cities-municipalities/',
                    'barangay' => 'https://psgc.gitlab.io/api/barangays/',
                    'district' => 'https://psgc.gitlab.io/api/districts/',
                ];

                if (!isset($jsonUrls[$type])) {
                    return 'Unknown';
                }
                $cacheFile = storage_path("app/psgc_$type.json");
                $data = file_exists($cacheFile) ? json_decode(file_get_contents($cacheFile), true) : null;

                if (empty($data)) {
                    try {
                        $json = file_get_contents($jsonUrls[$type]);
                        $data = json_decode($json, true);
                        if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {
                            file_put_contents($cacheFile, json_encode($data));
                        }
                    } catch (\Exception $e) {
                        return 'Unknown';
                    }
                }

                foreach ($data as $item) {
                    if (isset($item['code']) && $item['code'] == $code) {
                        return $item['name'];
                    }
                }
                return 'Unknown';
            }
        }
    @endphp
    <style>
        /* Print layout: A4 landscape and print-only header */
        @media print {
            @page {
                size: A4 landscape;
                margin: 1cm;
            }

            body {
                font-size: 12px;
            }

            /* Hide UI controls while printing */
            .no-print {
                display: none !important;
            }

            /* Show print header (title + university) */
            .print-header {
                display: block !important;
                text-align: center;
                margin-bottom: 8px;
            }

            /* Table styling for print */
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

        /* On screen: hide print header */
        .print-header {
            display: none;
        }

        /* Hidden column helper (for screen and print) */
        .col-hidden {
            display: none !important;
        }
    </style>

    <div class="space-y-6 mb-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                DOST Monitoring Scholars
            </h1>
        </div>

        <!-- Filters & Actions Card -->
        <div class="bg-white/30 backdrop-blur-lg shadow-md border border-white/20 rounded-2xl p-6">
            <form id="filtersForm" method="GET" action="{{ route('admin.reports.monitoring') }}"
                class="grid grid-cols-1 sm:grid-cols-4 gap-6">

                <!-- Academic Year filter -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Academic Year</label>
                    <select name="academic_year"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All</option>
                        <option value="2024-2025" {{ request('academic_year') == '2024-2025' ? 'selected' : '' }}>2024-2025
                        </option>
                        <option value="2025-2026" {{ request('academic_year') == '2025-2026' ? 'selected' : '' }}>2025-2026
                        </option>
                        <option value="2026-2027" {{ request('academic_year') == '2026-2027' ? 'selected' : '' }}>2026-2027
                        </option>
                    </select>
                </div>

                <!-- Semester filter -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Semester</label>
                    <select name="semester"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="" {{ request('semester') == '' ? 'selected' : '' }}>All</option>
                        <option value="First Semester" {{ request('semester') == 'First Semester' ? 'selected' : '' }}>1st
                            Semester</option>
                        <option value="Second Semester" {{ request('semester') == 'Second Semester' ? 'selected' : '' }}>2nd
                            Semester</option>
                    </select>
                </div>

                <!-- Buttons -->
                <div class="flex items-end gap-2 col-span-2">
                    <button type="submit"
                        class="bg-blue-600 font-medium text-white text-sm px-4 py-2 rounded-lg shadow-md hover:bg-blue-700 transition">
                        Filter
                    </button>
                    <button type="button" id="printBtn"
                        class="bg-green-600 font-medium text-white text-sm px-4 py-2 rounded-lg shadow-md hover:bg-green-700 transition">
                        Print
                    </button>
                    <button type="button" id="resetCols"
                        class="bg-red-600 font-medium text-white text-sm px-4 py-2 rounded-lg shadow-md hover:bg-red-700 transition">
                        Reset Columns
                    </button>
                </div>
            </form>
        </div>

        <!-- Field Selection with View Toggle -->
        <div class="bg-white/30 backdrop-blur-lg shadow-md border border-white/20 rounded-2xl p-5">
            <!-- View Toggle Buttons at the top -->
            <div class="mb-4 flex items-center gap-3">
                <h2 class="text-sm font-semibold text-gray-700">Select View:</h2>
                <button type="button" id="monitoringViewBtn"
                    class="view-toggle-btn bg-blue-600 text-white px-4 py-2 rounded-lg font-medium text-sm shadow-md hover:bg-blue-700 transition">
                    Monitoring Data
                </button>
                <button type="button" id="personalViewBtn"
                    class="view-toggle-btn bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-medium text-sm shadow-md hover:bg-gray-300 transition">
                    Personal Information
                </button>
            </div>

            <!-- Monitoring Fields (Shown by default) -->
            <div id="monitoringFields">
                <div class="flex flex-wrap gap-6">
                    <label class="inline-flex items-center"><input type="checkbox"
                            class="col-toggle rounded text-blue-600 focus:ring-blue-500" data-col="no" checked>
                        <span class="ml-2">No.</span></label>
                    <label class="inline-flex items-center"><input type="checkbox"
                            class="col-toggle rounded text-blue-600 focus:ring-blue-500" data-col="last_name" checked>
                        <span class="ml-2">Last Name</span></label>
                    <label class="inline-flex items-center"><input type="checkbox"
                            class="col-toggle rounded text-blue-600 focus:ring-blue-500" data-col="first_name" checked>
                        <span class="ml-2">First Name</span></label>
                    <label class="inline-flex items-center"><input type="checkbox"
                            class="col-toggle rounded text-blue-600 focus:ring-blue-500" data-col="middle_name" checked>
                        <span class="ml-2">Middle Name</span></label>
                    <label class="inline-flex items-center"><input type="checkbox"
                            class="col-toggle rounded text-blue-600 focus:ring-blue-500" data-col="level" checked>
                        <span class="ml-2">Level (MS/PhD)</span></label>
                    <label class="inline-flex items-center"><input type="checkbox"
                            class="col-toggle rounded text-blue-600 focus:ring-blue-500" data-col="course" checked>
                        <span class="ml-2">Course</span></label>
                    <label class="inline-flex items-center"><input type="checkbox"
                            class="col-toggle rounded text-blue-600 focus:ring-blue-500" data-col="school" checked>
                        <span class="ml-2">School</span></label>
                    <label class="inline-flex items-center"><input type="checkbox"
                            class="col-toggle rounded text-blue-600 focus:ring-blue-500" data-col="new_lateral" checked>
                        <span class="ml-2">New/Lateral</span></label>
                    <label class="inline-flex items-center"><input type="checkbox"
                            class="col-toggle rounded text-blue-600 focus:ring-blue-500" data-col="pt_ft" checked>
                        <span class="ml-2">Part-Time/Full-Time</span></label>
                    <label class="inline-flex items-center"><input type="checkbox"
                            class="col-toggle rounded text-blue-600 focus:ring-blue-500" data-col="duration" checked>
                        <span class="ml-2">Scholarship Duration</span></label>
                    <label class="inline-flex items-center"><input type="checkbox"
                            class="col-toggle rounded text-blue-600 focus:ring-blue-500" data-col="date_started" checked>
                        <span class="ml-2">Date Started</span></label>
                    <label class="inline-flex items-center"><input type="checkbox"
                            class="col-toggle rounded text-blue-600 focus:ring-blue-500" data-col="expected_completion"
                            checked>
                        <span class="ml-2">Expected Completion</span></label>
                    <label class="inline-flex items-center"><input type="checkbox"
                            class="col-toggle rounded text-blue-600 focus:ring-blue-500" data-col="status" checked>
                        <span class="ml-2">Status</span></label>
                    <label class="inline-flex items-center"><input type="checkbox"
                            class="col-toggle rounded text-blue-600 focus:ring-blue-500" data-col="remarks" checked>
                        <span class="ml-2">Remarks</span></label>
                </div>
            </div>

            <!-- Personal Fields (Hidden by default) - ADD CHECKED ATTRIBUTES -->
            <div id="personalFields" class="hidden">
                <div class="flex flex-wrap gap-6">
                    <label class="inline-flex items-center"><input type="checkbox"
                            class="personal-col-toggle rounded text-blue-600 focus:ring-blue-500" data-col="no" checked>
                        <span class="ml-2">No.</span></label>
                    <label class="inline-flex items-center"><input type="checkbox"
                            class="personal-col-toggle rounded text-blue-600 focus:ring-blue-500" data-col="last_name"
                            checked>
                        <span class="ml-2">Last Name</span></label>
                    <label class="inline-flex items-center"><input type="checkbox"
                            class="personal-col-toggle rounded text-blue-600 focus:ring-blue-500" data-col="first_name"
                            checked>
                        <span class="ml-2">First Name</span></label>
                    <label class="inline-flex items-center"><input type="checkbox"
                            class="personal-col-toggle rounded text-blue-600 focus:ring-blue-500" data-col="middle_name"
                            checked>
                        <span class="ml-2">Middle Name</span></label>
                    <label class="inline-flex items-center"><input type="checkbox"
                            class="personal-col-toggle rounded text-blue-600 focus:ring-blue-500" data-col="suffix"
                            checked>
                        <span class="ml-2">Suffix</span></label>
                    <label class="inline-flex items-center"><input type="checkbox"
                            class="personal-col-toggle rounded text-blue-600 focus:ring-blue-500" data-col="street"
                            checked>
                        <span class="ml-2">Street</span></label>
                    <label class="inline-flex items-center"><input type="checkbox"
                            class="personal-col-toggle rounded text-blue-600 focus:ring-blue-500" data-col="village"
                            checked>
                        <span class="ml-2">Village</span></label>
                    <label class="inline-flex items-center"><input type="checkbox"
                            class="personal-col-toggle rounded text-blue-600 focus:ring-blue-500" data-col="town" checked>
                        <span class="ml-2">Town</span></label>
                    <label class="inline-flex items-center"><input type="checkbox"
                            class="personal-col-toggle rounded text-blue-600 focus:ring-blue-500" data-col="province"
                            checked>
                        <span class="ml-2">Province</span></label>
                    <label class="inline-flex items-center"><input type="checkbox"
                            class="personal-col-toggle rounded text-blue-600 focus:ring-blue-500" data-col="zipcode"
                            checked>
                        <span class="ml-2">Zipcode</span></label>
                    <label class="inline-flex items-center"><input type="checkbox"
                            class="personal-col-toggle rounded text-blue-600 focus:ring-blue-500" data-col="district"
                            checked>
                        <span class="ml-2">District</span></label>
                    <label class="inline-flex items-center"><input type="checkbox"
                            class="personal-col-toggle rounded text-blue-600 focus:ring-blue-500" data-col="region"
                            checked>
                        <span class="ml-2">Region</span></label>
                    <label class="inline-flex items-center"><input type="checkbox"
                            class="personal-col-toggle rounded text-blue-600 focus:ring-blue-500" data-col="email"
                            checked>
                        <span class="ml-2">Email</span></label>
                    <label class="inline-flex items-center"><input type="checkbox"
                            class="personal-col-toggle rounded text-blue-600 focus:ring-blue-500" data-col="bday" checked>
                        <span class="ml-2">Birthday</span></label>
                    <label class="inline-flex items-center"><input type="checkbox"
                            class="personal-col-toggle rounded text-blue-600 focus:ring-blue-500" data-col="contact_no"
                            checked>
                        <span class="ml-2">Contact No.</span></label>
                    <label class="inline-flex items-center"><input type="checkbox"
                            class="personal-col-toggle rounded text-blue-600 focus:ring-blue-500" data-col="gender"
                            checked>
                        <span class="ml-2">Gender</span></label>
                </div>
            </div>
        </div>
        </form>
    </div>

    <!-- Monitoring Table Card -->
    <div id="monitoringTableWrapper" class="bg-white rounded-xl shadow-lg overflow-auto">
        <div class="p-4">
            <div class="overflow-x-auto">
                <div class="print-header">
                    <h2 class="text-lg font-bold">DETAILED STATUS REPORT OF SCHOLARSHIP PROGRAM</h2>
                    <div class="text-sm mt-1">University: University of Science and Technology of Southern Philippines
                        (USTP)</div>
                    <div style="height: 12px;"></div>
                </div>

                <table id="scholarsTable" class="min-w-full text-sm">
                    <thead>
                        <tr class="bg-blue-50">
                            <th data-col="no" class="px-3 py-2 text-center font-semibold border">No.</th>
                            <th data-col="last_name" class="px-3 py-2 text-left font-semibold border">Last Name</th>
                            <th data-col="first_name" class="px-3 py-2 text-left font-semibold border">First Name</th>
                            <th data-col="middle_name" class="px-3 py-2 text-left font-semibold border">Middle Name
                            </th>
                            <th data-col="level" class="px-3 py-2 text-center font-semibold border">Level (MS/PhD)
                            </th>
                            <th data-col="course" class="px-3 py-2 text-left font-semibold border">Course</th>
                            <th data-col="school" class="px-3 py-2 text-left font-semibold border">School</th>
                            <th data-col="new_lateral" class="px-3 py-2 text-center font-semibold border">New /
                                Lateral</th>
                            <th data-col="pt_ft" class="px-3 py-2 text-center font-semibold border">Part-Time /
                                Full-Time</th>
                            <th data-col="duration" class="px-3 py-2 text-center font-semibold border">Scholarship
                                Duration</th>
                            <th data-col="date_started" class="px-3 py-2 text-center font-semibold border">Date
                                Started (Month & Year)</th>
                            <th data-col="expected_completion" class="px-3 py-2 text-center font-semibold border">
                                Expected Completion (Month & Year)</th>
                            <th data-col="status" class="px-3 py-2 text-center font-semibold border">Status</th>
                            <th data-col="remarks" class="px-3 py-2 text-left font-semibold border">Remarks</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($scholars as $index => $scholar)
                            @php
                                $monitoring = $scholar->monitorings->first();
                            @endphp
                            <tr class="odd:bg-white even:bg-slate-50">
                                <td data-col="no" class="px-2 py-2 text-center border">{{ $index + 1 }}</td>
                                <td data-col="last_name" class="px-2 py-2 border">
                                    {{ $scholar->applicationForm->last_name }}</td>
                                <td data-col="first_name" class="px-2 py-2 border">
                                    {{ $scholar->applicationForm->first_name }}</td>
                                <td data-col="middle_name" class="px-2 py-2 border">
                                    {{ $scholar->applicationForm->middle_name }}</td>
                                <td data-col="level" class="px-2 py-2 text-center border">
                                    {{ implode(', ', $scholar->applicationForm->scholarship_type ?? []) }}
                                </td>

                                <!-- Editable fields -->
                                <td data-col="course" class="px-2 py-2 border">
                                    <span class="display-text"
                                        data-field="course">{{ $monitoring?->course ?? '' }}</span>
                                    <input type="text" class="edit-input hidden border px-1 py-1 w-full"
                                        value="{{ $monitoring?->course ?? '' }}" data-field="course"
                                        data-monitoring-id="{{ $monitoring?->id }}"
                                        data-scholar-id="{{ $scholar->id }}">
                                </td>

                                <td data-col="school" class="px-2 py-2 border">
                                    <span class="display-text"
                                        data-field="school">{{ $monitoring?->school ?? '' }}</span>
                                    <input type="text" class="edit-input hidden border px-1 py-1 w-full"
                                        value="{{ $monitoring?->school ?? '' }}" data-field="school"
                                        data-monitoring-id="{{ $monitoring?->id }}"
                                        data-scholar-id="{{ $scholar->id }}">
                                </td>

                                <td data-col="new_lateral" class="px-2 py-2 text-center border">
                                    {{ $scholar->applicationForm->applicant_status }}
                                </td>

                                <td data-col="pt_ft" class="px-2 py-2 text-center border">
                                    <span class="display-text"
                                        data-field="enrollment_type">{{ $monitoring?->enrollment_type ?? '' }}</span>
                                    <input type="text" class="edit-input hidden border px-1 py-1 w-full text-center"
                                        value="{{ $monitoring?->enrollment_type ?? '' }}" data-field="enrollment_type"
                                        data-monitoring-id="{{ $monitoring?->id }}"
                                        data-scholar-id="{{ $scholar->id }}">
                                </td>

                                <td data-col="duration" class="px-2 py-2 text-center border">
                                    <span class="display-text"
                                        data-field="scholarship_duration">{{ $monitoring?->scholarship_duration ?? '' }}</span>
                                    <input type="text" class="edit-input hidden border px-1 py-1 w-full text-center"
                                        value="{{ $monitoring?->scholarship_duration ?? '' }}"
                                        data-field="scholarship_duration" data-monitoring-id="{{ $monitoring?->id }}"
                                        data-scholar-id="{{ $scholar->id }}">
                                </td>

                                <td data-col="date_started" class="px-2 py-2 text-center border">
                                    <span class="display-text"
                                        data-field="date_started">{{ $monitoring?->date_started ?? '' }}</span>
                                    <input type="text" class="edit-input hidden border px-1 py-1 w-full text-center"
                                        value="{{ $monitoring?->date_started ?? '' }}" data-field="date_started"
                                        data-monitoring-id="{{ $monitoring?->id }}"
                                        data-scholar-id="{{ $scholar->id }}">
                                </td>

                                <td data-col="expected_completion" class="px-2 py-2 text-center border">
                                    <span class="display-text"
                                        data-field="expected_completion">{{ $monitoring?->expected_completion ?? '' }}</span>
                                    <input type="text" class="edit-input hidden border px-1 py-1 w-full text-center"
                                        value="{{ $monitoring?->expected_completion ?? '' }}"
                                        data-field="expected_completion" data-monitoring-id="{{ $monitoring?->id }}"
                                        data-scholar-id="{{ $scholar->id }}">
                                </td>

                                <td data-col="status" class="px-2 py-2 text-center border">
                                    {{ $scholar->applicationForm->status }}
                                </td>

                                <td data-col="remarks" class="px-2 py-2 border">
                                    <span class="display-text"
                                        data-field="remarks">{{ $monitoring?->remarks ?? '' }}</span>
                                    <input type="text" class="edit-input hidden border px-1 py-1 w-full"
                                        value="{{ $monitoring?->remarks ?? '' }}" data-field="remarks"
                                        data-monitoring-id="{{ $monitoring?->id }}"
                                        data-scholar-id="{{ $scholar->id }}">
                                </td>

                                <td class="px-2 py-2 border">
                                    <button type="button"
                                        class="edit-btn bg-blue-500 text-white px-2 py-1 rounded text-xs">Edit</button>
                                    <button type="button"
                                        class="save-btn hidden bg-green-500 text-white px-2 py-1 rounded text-xs">Save</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="15" class="text-center p-6 text-slate-500">No approved scholars found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Personal Information Table (Separate - Hidden by default) -->
    <div id="personalTable" class="bg-white rounded-xl shadow-lg overflow-auto hidden">
        <div class="p-4">
            <div class="overflow-x-auto">
                <div class="print-header">
                    <h2 class="text-lg font-bold">SCHOLARS PERSONAL INFORMATION</h2>
                    <div class="text-sm mt-1">University: University of Science and Technology of Southern Philippines
                        (USTP)</div>
                    <div style="height: 12px;"></div>
                </div>

                <table id="personalInfoTable" class="min-w-full text-sm">
                    <thead>
                        <tr class="bg-green-50">
                            <th data-col="no" class="px-3 py-2 text-center font-semibold border">No.</th>
                            <th data-col="last_name" class="px-3 py-2 text-left font-semibold border">Last Name</th>
                            <th data-col="first_name" class="px-3 py-2 text-left font-semibold border">First Name</th>
                            <th data-col="middle_name" class="px-3 py-2 text-left font-semibold border">Middle Name</th>
                            <th data-col="suffix" class="px-3 py-2 text-center font-semibold border">Suffix</th>
                            <th data-col="street" class="px-3 py-2 text-left font-semibold border">Street</th>
                            <th data-col="village" class="px-3 py-2 text-left font-semibold border">Village</th>
                            <th data-col="town" class="px-3 py-2 text-left font-semibold border">Town</th>
                            <th data-col="province" class="px-3 py-2 text-left font-semibold border">Province</th>
                            <th data-col="zipcode" class="px-3 py-2 text-center font-semibold border">Zipcode</th>
                            <th data-col="district" class="px-3 py-2 text-left font-semibold border">District</th>
                            <th data-col="region" class="px-3 py-2 text-left font-semibold border">Region</th>
                            <th data-col="email" class="px-3 py-2 text-left font-semibold border">Email</th>
                            <th data-col="birthday" class="px-3 py-2 text-center font-semibold border">Birthday</th>
                            <th data-col="contact_no" class="px-3 py-2 text-center font-semibold border">Contact No.</th>
                            <th data-col="gender" class="px-3 py-2 text-center font-semibold border">Gender</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($scholars as $index => $scholar)
                            <tr class="odd:bg-white even:bg-slate-50">
                                <td data-col="no" class="px-2 py-2 text-center border">{{ $index + 1 }}</td>
                                <td data-col="last_name" class="px-2 py-2 border">
                                    {{ $scholar->applicationForm->last_name }}</td>
                                <td data-col="first_name" class="px-2 py-2 border">
                                    {{ $scholar->applicationForm->first_name }}</td>
                                <td data-col="middle_name" class="px-2 py-2 border">
                                    {{ $scholar->applicationForm->middle_name }}</td>
                                <td data-col="suffix" class="px-2 py-2 text-center border">
                                    {{ $scholar->applicationForm->suffix ?? '' }}</td>
                                <td data-col="street" class="px-2 py-2 border">
                                    {{ $scholar->applicationForm->address_street ?? '' }}</td>
                                <td data-col="village" class="px-2 py-2 border">
                                    {{ getLocationName($scholar->applicationForm->barangay ?? '', 'barangay') }}
                                </td>
                                <td data-col="town" class="px-2 py-2 border">
                                    {{ getLocationName($scholar->applicationForm->city ?? '', 'city') }}
                                </td>
                                <td data-col="province" class="px-2 py-2 border">
                                    {{ getLocationName($scholar->applicationForm->province ?? '', 'province') }}
                                </td>
                                <td data-col="zipcode" class="px-2 py-2 text-center border">
                                    {{ $scholar->applicationForm->zip_code ?? '' }}</td>
                                <td data-col="district" class="px-2 py-2 border">
                                    {{ $scholar->applicationForm->district ?? '' }}</td>
                                <td data-col="region" class="px-2 py-2 border">
                                    {{ $scholar->applicationForm->region ?? '' }}</td>
                                <td data-col="email" class="px-2 py-2 border">
                                    {{ $scholar->applicationForm->email_address ?? '' }}</td>
                                <td data-col="birthday" class="px-2 py-2 text-center border">
                                    {{ $scholar->applicationForm->date_of_birth ?? '' }}</td>
                                <td data-col="contact_no" class="px-2 py-2 text-center border">
                                    {{ $scholar->applicationForm->telephone_nos ?? '' }}</td>
                                <td data-col="gender" class="px-2 py-2 text-center border">
                                    {{ $scholar->applicationForm->sex ?? '' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="16" class="text-center p-6 text-slate-500">No approved scholars found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- JS: column toggles, persistence, print behaviour -->
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {

                (function() {
                    const colToggles = document.querySelectorAll('.col-toggle');
                    const table = document.getElementById('scholarsTable');
                    const printBtn = document.getElementById('printBtn');
                    const resetBtn = document.getElementById('resetCols');
                    const STORAGE_KEY = 'monitoring_scholars_cols_v1';
                    const monitoringViewBtn = document.getElementById('monitoringViewBtn');
                    const personalViewBtn = document.getElementById('personalViewBtn');
                    const monitoringFields = document.getElementById('monitoringFields');
                    const personalFields = document.getElementById('personalFields');
                    const scholarsTable = document.getElementById('monitoringTableWrapper');
                    const personalTable = document.getElementById('personalTable');

                    if (monitoringViewBtn && personalViewBtn) {
                        monitoringViewBtn.addEventListener('click', function() {
                            monitoringViewBtn.classList.add('active', 'bg-blue-600', 'text-white');
                            monitoringViewBtn.classList.remove('bg-gray-200', 'text-gray-700');
                            personalViewBtn.classList.remove('active', 'bg-blue-600', 'text-white');
                            personalViewBtn.classList.add('bg-gray-200', 'text-gray-700');

                            monitoringFields.classList.remove('hidden');
                            personalFields.classList.add('hidden');
                            scholarsTable.classList.remove('hidden');
                            personalTable.classList.add('hidden');
                        });

                        personalViewBtn.addEventListener('click', function() {
                            personalViewBtn.classList.add('active', 'bg-blue-600', 'text-white');
                            personalViewBtn.classList.remove('bg-gray-200', 'text-gray-700');
                            monitoringViewBtn.classList.remove('active', 'bg-blue-600', 'text-white');
                            monitoringViewBtn.classList.add('bg-gray-200', 'text-gray-700');

                            personalFields.classList.remove('hidden');
                            monitoringFields.classList.add('hidden');
                            personalTable.classList.remove('hidden');
                            scholarsTable.classList.add('hidden');
                        });
                    }

                    // Personal Info Column Toggle Logic
                    const personalToggles = document.querySelectorAll('.personal-col-toggle');
                    const personalInfoTable = document.getElementById('personalInfoTable');

                    if (personalInfoTable) {
                        personalToggles.forEach(toggle => {
                            toggle.addEventListener('change', function() {
                                const col = this.dataset.col;
                                const isChecked = this.checked;

                                const headers = personalInfoTable.querySelectorAll(
                                    `th[data-col="${col}"]`);
                                headers.forEach(header => {
                                    header.classList.toggle('col-hidden', !isChecked);
                                });

                                const cells = personalInfoTable.querySelectorAll(
                                    `td[data-col="${col}"]`);
                                cells.forEach(cell => {
                                    cell.classList.toggle('col-hidden', !isChecked);
                                });
                            });
                        });
                    }

                    if (!table) return;

                    const defaultCols = [
                        'no', 'last_name', 'first_name', 'middle_name', 'level', 'course', 'school',
                        'new_lateral', 'pt_ft', 'duration', 'date_started', 'expected_completion', 'status',
                        'remarks'
                    ];

                    let saved = localStorage.getItem(STORAGE_KEY);
                    let visibleCols = saved ? JSON.parse(saved) : defaultCols.slice();

                    function initCheckboxes() {
                        colToggles.forEach(cb => {
                            const col = cb.getAttribute('data-col');
                            cb.checked = visibleCols.includes(col);
                        });
                    }

                    function applyColumnVisibility() {
                        table.querySelectorAll('th[data-col], td[data-col]').forEach(el => {
                            const col = el.getAttribute('data-col');
                            el.classList.toggle('col-hidden', !visibleCols.includes(col));
                        });
                    }

                    function savePrefs() {
                        localStorage.setItem(STORAGE_KEY, JSON.stringify(visibleCols));
                    }

                    colToggles.forEach(cb => {
                        cb.addEventListener('change', function() {
                            const col = this.getAttribute('data-col');
                            if (this.checked) {
                                if (!visibleCols.includes(col)) visibleCols.push(col);
                            } else {
                                visibleCols = visibleCols.filter(c => c !== col);
                            }
                            applyColumnVisibility();
                            savePrefs();
                        });
                    });

                    if (resetBtn) {
                        resetBtn.addEventListener('click', function() {
                            visibleCols = defaultCols.slice();
                            initCheckboxes();
                            applyColumnVisibility();
                            savePrefs();
                        });
                    }

                    if (printBtn) {
                        printBtn.addEventListener('click', function() {
                            // Check which view is active
                            const isPersonalView = !personalTable.classList.contains('hidden');

                            if (isPersonalView) {
                                // Print Personal Information
                                const personalToggles = document.querySelectorAll('.personal-col-toggle');
                                const selectedCols = [];
                                personalToggles.forEach(cb => {
                                    if (cb.checked) {
                                        selectedCols.push(cb.getAttribute('data-col'));
                                    }
                                });

                                const schoolTerm = document.querySelector('select[name="school_term"]')
                                    ?.value || '';
                                const academicYear = document.querySelector('select[name="academic_year"]')
                                    ?.value || '';
                                const program = document.querySelector('select[name="program"]')?.value ||
                                    '';
                                const columnsParam = encodeURIComponent(JSON.stringify(selectedCols));
                                const printUrl = `{{ route('admin.reports.monitoring-print-info') }}` +
                                    `?school_term=${encodeURIComponent(schoolTerm)}` +
                                    `&academic_year=${encodeURIComponent(academicYear)}` +
                                    `&program=${encodeURIComponent(program)}` +
                                    `&columns=${columnsParam}`;

                                window.open(printUrl, '_blank');
                            } else {
                                // Print Monitoring Data (your existing code)
                                const selectedCols = [];
                                colToggles.forEach(cb => {
                                    if (cb.checked) {
                                        selectedCols.push(cb.getAttribute('data-col'));
                                    }
                                });

                                const schoolTerm = document.querySelector('select[name="school_term"]')
                                    ?.value || '';
                                const academicYear = document.querySelector('select[name="academic_year"]')
                                    ?.value || '';
                                const program = document.querySelector('select[name="program"]')?.value ||
                                    '';
                                const columnsParam = encodeURIComponent(JSON.stringify(selectedCols));
                                const printUrl = `{{ route('admin.reports.monitoring.print') }}` +
                                    `?school_term=${encodeURIComponent(schoolTerm)}` +
                                    `&academic_year=${encodeURIComponent(academicYear)}` +
                                    `&program=${encodeURIComponent(program)}` +
                                    `&columns=${columnsParam}`;

                                window.open(printUrl, '_blank');
                            }
                        });
                    }

                    initCheckboxes();
                    applyColumnVisibility();
                })();

                document.querySelectorAll('tr').forEach(row => {
                    const editBtn = row.querySelector('.edit-btn');
                    const saveBtn = row.querySelector('.save-btn');

                    if (!editBtn || !saveBtn) return;

                    // Enable editing
                    editBtn.addEventListener('click', () => {
                        row.querySelectorAll('.display-text').forEach(span => span.classList.add(
                            'hidden'));
                        row.querySelectorAll('.edit-input').forEach(input => input.classList.remove(
                            'hidden'));
                        editBtn.classList.add('hidden');
                        saveBtn.classList.remove('hidden');
                    });

                    // Save changes
                    saveBtn.addEventListener('click', () => {
                        const course = row.querySelector('.edit-input[data-field="course"]').value
                            .trim();
                        const school = row.querySelector('.edit-input[data-field="school"]').value
                            .trim();
                        const enrollment_type = row.querySelector(
                            '.edit-input[data-field="enrollment_type"]').value.trim().toUpperCase();
                        const scholarship_duration = row.querySelector(
                            '.edit-input[data-field="scholarship_duration"]').value.trim();
                        const date_started = row.querySelector('.edit-input[data-field="date_started"]')
                            .value.trim();
                        const expected_completion = row.querySelector(
                            '.edit-input[data-field="expected_completion"]').value.trim();
                        const remarks = row.querySelector('.edit-input[data-field="remarks"]').value
                            .trim();

                        const id = row.querySelector('.edit-input[data-field="course"]').dataset
                            .monitoringId || null;
                        const scholar_id = row.querySelector('.edit-input[data-field="course"]').dataset
                            .scholarId;

                        // Send POST request
                        fetch("{{ route('admin.reports.monitoring.update-field') }}", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify([{
                                        id,
                                        scholar_id,
                                        field: 'course',
                                        value: course
                                    },
                                    {
                                        id,
                                        scholar_id,
                                        field: 'school',
                                        value: school
                                    },
                                    {
                                        id,
                                        scholar_id,
                                        field: 'enrollment_type',
                                        value: enrollment_type
                                    },
                                    {
                                        id,
                                        scholar_id,
                                        field: 'scholarship_duration',
                                        value: scholarship_duration
                                    },
                                    {
                                        id,
                                        scholar_id,
                                        field: 'date_started',
                                        value: date_started
                                    },
                                    {
                                        id,
                                        scholar_id,
                                        field: 'expected_completion',
                                        value: expected_completion
                                    },
                                    {
                                        id,
                                        scholar_id,
                                        field: 'remarks',
                                        value: remarks
                                    },
                                ])
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    row.querySelector('.display-text[data-field="course"]')
                                        .textContent = course;
                                    row.querySelector('.display-text[data-field="school"]')
                                        .textContent = school;
                                    row.querySelector('.display-text[data-field="enrollment_type"]')
                                        .textContent = enrollment_type;
                                    row.querySelector(
                                            '.display-text[data-field="scholarship_duration"]')
                                        .textContent = scholarship_duration;
                                    row.querySelector('.display-text[data-field="date_started"]')
                                        .textContent = date_started;
                                    row.querySelector(
                                            '.display-text[data-field="expected_completion"]')
                                        .textContent = expected_completion;
                                    row.querySelector('.display-text[data-field="remarks"]')
                                        .textContent = remarks;
                                    row.querySelectorAll('.display-text').forEach(span => span
                                        .classList.remove('hidden'));
                                    row.querySelectorAll('.edit-input').forEach(input => input
                                        .classList.add('hidden'));
                                    editBtn.classList.remove('hidden');
                                    saveBtn.classList.add('hidden');

                                    if (!row.querySelector('.edit-input[data-field="course"]')
                                        .dataset.monitoringId) {
                                        const newId = data.monitoring_ids ? data.monitoring_ids[0] :
                                            null;
                                        row.querySelectorAll('.edit-input').forEach(input => input
                                            .dataset.monitoringId = newId);
                                    }
                                } else {
                                    alert('Failed to save.');
                                }
                            })
                            .catch(err => {
                                console.error(err);
                                alert('Error saving data');
                            });
                    });
                });
            });
        </script>
    @endpush
@endsection
