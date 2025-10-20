@extends('layouts.admin-layout')

@section('content')
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

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                Monitoring Scholars
            </h1>
        </div>

        <!-- Filters & Actions Card -->
        <div class="bg-white/30 backdrop-blur-lg shadow-md border border-white/20 rounded-2xl p-6">
            <form id="filtersForm" method="GET" action="{{ route('admin.reports.monitoring') }}"
                class="grid grid-cols-1 sm:grid-cols-3 gap-6">

                <!-- Semester filter -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Semester</label>
                    <select name="semester"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="" {{ request('semester') == '' ? 'selected' : '' }}>All Semesters</option>
                        <option value="1st" {{ request('semester') == '1st' ? 'selected' : '' }}>First Semester</option>
                        <option value="2nd" {{ request('semester') == '2nd' ? 'selected' : '' }}>Second Semester</option>
                    </select>
                </div>

                <!-- Buttons -->
                <div class="flex items-end gap-2">
                    <button type="submit"
                        class="bg-blue-600 font-medium text-white text-sm px-4 py-2 rounded-lg shadow-md hover:bg-blue-700 transition">
                        Filter
                    </button>

                    <a href="{{ route('admin.reports.monitoring.print', ['semester' => request('semester', '')]) }}"
                        target="_blank" rel="noopener"
                        class="bg-green-600 font-medium text-white text-sm px-4 py-2 rounded-lg shadow-md hover:bg-green-700 transition">
                        Print
                    </a>


                    <button type="button" id="resetCols"
                        class="bg-red-600 font-medium text-white text-sm px-4 py-2 rounded-lg shadow-md hover:bg-red-700 transition">
                        Reset Columns
                    </button>
                </div>
            </form>
        </div>
        <!-- Field Selection Section -->
        <div class="bg-white/30 backdrop-blur-lg shadow-md border border-white/20 rounded-2xl p-5">
            <h2 class="text-sm font-semibold text-gray-700 mb-3">Select Fields to Display</h2>
            <div class="flex flex-wrap gap-4">
                <label class="inline-flex items-center"><input type="checkbox" class="col-toggle" data-col="no" checked>
                    <span class="ml-2">No.</span></label>
                <label class="inline-flex items-center"><input type="checkbox" class="col-toggle" data-col="last_name"
                        checked> <span class="ml-2">Last Name</span></label>
                <label class="inline-flex items-center"><input type="checkbox" class="col-toggle" data-col="first_name"
                        checked> <span class="ml-2">First name</span></label>
                <label class="inline-flex items-center"><input type="checkbox" class="col-toggle" data-col="middle_name"
                        checked> <span class="ml-2">Middle Name</span></label>
                <label class="inline-flex items-center"><input type="checkbox" class="col-toggle" data-col="level" checked>
                    <span class="ml-2">Level (MS/PhD)</span></label>
                <label class="inline-flex items-center"><input type="checkbox" class="col-toggle" data-col="course" checked>
                    <span class="ml-2">Course</span></label>
                <label class="inline-flex items-center"><input type="checkbox" class="col-toggle" data-col="school" checked>
                    <span class="ml-2">School</span></label>
                <label class="inline-flex items-center"><input type="checkbox" class="col-toggle" data-col="new_lateral"
                        checked> <span class="ml-2">New/Lateral</span></label>
                <label class="inline-flex items-center"><input type="checkbox" class="col-toggle" data-col="pt_ft" checked>
                    <span class="ml-2">Part-Time/Full-Time</span></label>
                <label class="inline-flex items-center"><input type="checkbox" class="col-toggle" data-col="duration"
                        checked> <span class="ml-2">Scholarship Duration</span></label>
                <label class="inline-flex items-center"><input type="checkbox" class="col-toggle" data-col="date_started"
                        checked> <span class="ml-2">Date Starded</span></label>
                <label class="inline-flex items-center"><input type="checkbox" class="col-toggle"
                        data-col="expected_completion" checked> <span class="ml-2">Expected Completion</span></label>
                <label class="inline-flex items-center"><input type="checkbox" class="col-toggle" data-col="status" checked>
                    <span class="ml-2">Status</span></label>
                <label class="inline-flex items-center"><input type="checkbox" class="col-toggle" data-col="remarks"
                        checked> <span class="ml-2">Remarks</span></label>
            </div>
        </div>
        </form>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-xl shadow-lg overflow-auto">
        <div class="p-4">
            <div class="overflow-x-auto">
                <!-- Print-only header inserted here, visible only in print -->
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
                            <th data-col="middle_name" class="px-3 py-2 text-left font-semibold border">Middle Name</th>
                            <th data-col="level" class="px-3 py-2 text-center font-semibold border">Level (MS/PhD)</th>
                            <th data-col="course" class="px-3 py-2 text-left font-semibold border">Course</th>
                            <th data-col="school" class="px-3 py-2 text-left font-semibold border">School</th>
                            <th data-col="new_lateral" class="px-3 py-2 text-center font-semibold border">New / Lateral
                            </th>
                            <th data-col="pt_ft" class="px-3 py-2 text-center font-semibold border">Part-Time / Full-Time
                            </th>
                            <th data-col="duration" class="px-3 py-2 text-center font-semibold border">Scholarship
                                Duration</th>
                            <th data-col="date_started" class="px-3 py-2 text-center font-semibold border">Date Started
                                (Month & Year)</th>
                            <th data-col="expected_completion" class="px-3 py-2 text-center font-semibold border">Expected
                                Completion (Month & Year)</th>
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
                                <td class="px-2 py-2 text-center border">{{ $index + 1 }}</td>
                                <td class="px-2 py-2 border">{{ $scholar->applicationForm->last_name }}</td>
                                <td class="px-2 py-2 border">{{ $scholar->applicationForm->first_name }}</td>
                                <td class="px-2 py-2 border">{{ $scholar->applicationForm->middle_name }}</td>
                                <td class="px-2 py-2 text-center border">
                                    {{ implode(', ', $scholar->applicationForm->scholarship_type ?? []) }}</td>

                                <!-- Editable fields -->
                                <td class="px-2 py-2 border">
                                    <span class="display-text"
                                        data-field="course">{{ $monitoring?->course ?? '' }}</span>
                                    <input type="text" class="edit-input hidden border px-1 py-1 w-full"
                                        value="{{ $monitoring?->course ?? '' }}" data-field="course"
                                        data-monitoring-id="{{ $monitoring?->id }}"
                                        data-scholar-id="{{ $scholar->id }}">
                                </td>

                                <td class="px-2 py-2 border">
                                    <span class="display-text"
                                        data-field="school">{{ $monitoring?->school ?? '' }}</span>
                                    <input type="text" class="edit-input hidden border px-1 py-1 w-full"
                                        value="{{ $monitoring?->school ?? '' }}" data-field="school"
                                        data-monitoring-id="{{ $monitoring?->id }}"
                                        data-scholar-id="{{ $scholar->id }}">
                                </td>

                                <td class="px-2 py-2 text-center border">{{ $scholar->applicationForm->applicant_status }}
                                </td>

                                <td class="px-2 py-2 text-center border">
                                    <span class="display-text"
                                        data-field="enrollment_type">{{ $monitoring?->enrollment_type ?? '' }}</span>
                                    <input type="text" class="edit-input hidden border px-1 py-1 w-full text-center"
                                        value="{{ $monitoring?->enrollment_type ?? '' }}" data-field="enrollment_type"
                                        data-monitoring-id="{{ $monitoring?->id }}"
                                        data-scholar-id="{{ $scholar->id }}">
                                </td>

                                <td class="px-2 py-2 text-center border">
                                    <span class="display-text"
                                        data-field="scholarship_duration">{{ $monitoring?->scholarship_duration ?? '' }}</span>
                                    <input type="text" class="edit-input hidden border px-1 py-1 w-full text-center"
                                        value="{{ $monitoring?->scholarship_duration ?? '' }}"
                                        data-field="scholarship_duration" data-monitoring-id="{{ $monitoring?->id }}"
                                        data-scholar-id="{{ $scholar->id }}">
                                </td>

                                <td class="px-2 py-2 text-center border">
                                    <span class="display-text"
                                        data-field="date_started">{{ $monitoring?->date_started ?? '' }}</span>
                                    <input type="text" class="edit-input hidden border px-1 py-1 w-full text-center"
                                        value="{{ $monitoring?->date_started ?? '' }}" data-field="date_started"
                                        data-monitoring-id="{{ $monitoring?->id }}"
                                        data-scholar-id="{{ $scholar->id }}">
                                </td>

                                <td class="px-2 py-2 text-center border">
                                    <span class="display-text"
                                        data-field="expected_completion">{{ $monitoring?->expected_completion ?? '' }}</span>
                                    <input type="text" class="edit-input hidden border px-1 py-1 w-full text-center"
                                        value="{{ $monitoring?->expected_completion ?? '' }}"
                                        data-field="expected_completion" data-monitoring-id="{{ $monitoring?->id }}"
                                        data-scholar-id="{{ $scholar->id }}">
                                </td>

                                <td class="px-2 py-2 text-center border">{{ $scholar->applicationForm->status }}</td>

                                <td class="px-2 py-2 border">
                                    <span class="display-text"
                                        data-field="remarks">{{ $monitoring?->remarks ?? '' }}</span>
                                    <input type="text" class="edit-input hidden border px-1 py-1 w-full"
                                        value="{{ $monitoring?->remarks ?? '' }}" data-field="remarks"
                                        data-monitoring-id="{{ $monitoring?->id }}"
                                        data-scholar-id="{{ $scholar->id }}">
                                </td>

                                <td>
                                    <button type="button"
                                        class="edit-btn bg-blue-500 text-white px-2 py-1 rounded text-xs">Edit</button>
                                    <button type="button"
                                        class="save-btn hidden bg-green-500 text-white px-2 py-1 rounded text-xs">Save</button>
                                </td>
                            @empty
                            <tr>
                                <td colspan="15" class="text-center p-6 text-slate-500">No approved scholars found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
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

                    if (!table) return; // guard against missing table

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
                            savePrefs();
                            window.print();
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
                                    // Update table visually
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

                                    // Hide inputs & show display
                                    row.querySelectorAll('.display-text').forEach(span => span
                                        .classList.remove('hidden'));
                                    row.querySelectorAll('.edit-input').forEach(input => input
                                        .classList.add('hidden'));
                                    editBtn.classList.remove('hidden');
                                    saveBtn.classList.add('hidden');

                                    // Update the monitoringId if it was null (new row)
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
