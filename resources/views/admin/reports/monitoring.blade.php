@extends('layouts.admin-layout')

@section('content')
<style>
    /* Print layout: A4 landscape and print-only header */
    @media print {
        @page { size: A4 landscape; margin: 1cm; }
        body { font-size: 12px; }

        /* Hide UI controls while printing */
        .no-print { display: none !important; }

        /* Show print header (title + university) */
        .print-header { display: block !important; text-align: center; margin-bottom: 8px; }

        /* Table styling for print */
        table { border-collapse: collapse !important; width: 100% !important; }
        th, td { border: 1px solid #000 !important; padding: 6px !important; vertical-align: middle; }
    }

    /* On screen: hide print header */
    .print-header { display: none; }

    /* Hidden column helper (for screen and print) */
    .col-hidden { display: none !important; }
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
        <form id="filtersForm" method="GET" action="{{ route('admin.reports.monitoring') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            
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
   target="_blank" 
   rel="noopener"
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
                <label class="inline-flex items-center"><input type="checkbox" class="col-toggle" data-col="no" checked> <span class="ml-2">No.</span></label>
                <label class="inline-flex items-center"><input type="checkbox" class="col-toggle" data-col="last_name" checked> <span class="ml-2">Last Name</span></label>
                <label class="inline-flex items-center"><input type="checkbox" class="col-toggle" data-col="first_name" checked> <span class="ml-2">First name</span></label>
                <label class="inline-flex items-center"><input type="checkbox" class="col-toggle" data-col="middle_name" checked> <span class="ml-2">Middle Name</span></label>
                <label class="inline-flex items-center"><input type="checkbox" class="col-toggle" data-col="level" checked> <span class="ml-2">Level (MS/PhD)</span></label>
                <label class="inline-flex items-center"><input type="checkbox" class="col-toggle" data-col="course" checked> <span class="ml-2">Course</span></label>
                <label class="inline-flex items-center"><input type="checkbox" class="col-toggle" data-col="school" checked> <span class="ml-2">School</span></label>
                <label class="inline-flex items-center"><input type="checkbox" class="col-toggle" data-col="new_lateral" checked> <span class="ml-2">New/Lateral</span></label>
                <label class="inline-flex items-center"><input type="checkbox" class="col-toggle" data-col="pt_ft" checked> <span class="ml-2">Part-Time/Full-Time</span></label>
                <label class="inline-flex items-center"><input type="checkbox" class="col-toggle" data-col="duration" checked> <span class="ml-2">Scholarship Duration</span></label>
                <label class="inline-flex items-center"><input type="checkbox" class="col-toggle" data-col="date_started" checked> <span class="ml-2">Date Starded</span></label>
                <label class="inline-flex items-center"><input type="checkbox" class="col-toggle" data-col="expected_completion" checked> <span class="ml-2">Expected Completion</span></label>
                <label class="inline-flex items-center"><input type="checkbox" class="col-toggle" data-col="status" checked> <span class="ml-2">Status</span></label>
                <label class="inline-flex items-center"><input type="checkbox" class="col-toggle" data-col="remarks" checked> <span class="ml-2">Remarks</span></label>
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
                    <div class="text-sm mt-1">University: University of Science and Technology of Southern Philippines (USTP)</div>
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
                            <th data-col="new_lateral" class="px-3 py-2 text-center font-semibold border">New / Lateral</th>
                            <th data-col="pt_ft" class="px-3 py-2 text-center font-semibold border">Part-Time / Full-Time</th>
                            <th data-col="duration" class="px-3 py-2 text-center font-semibold border">Scholarship Duration</th>
                            <th data-col="date_started" class="px-3 py-2 text-center font-semibold border">Date Started (Month & Year)</th>
                            <th data-col="expected_completion" class="px-3 py-2 text-center font-semibold border">Expected Completion (Month & Year)</th>
                            <th data-col="status" class="px-3 py-2 text-center font-semibold border">Status</th>
                            <th data-col="remarks" class="px-3 py-2 text-left font-semibold border">Remarks</th>
                        </tr>
                    </thead>

                    <tbody>
   <tbody>
@forelse($scholars as $index => $scholar)
    <tr class="odd:bg-white even:bg-slate-50">
        <td data-col="no" class="px-2 py-2 text-center border">{{ $index + 1 }}</td>
        <td data-col="last_name" class="px-2 py-2 border">{{ $scholar->applicationForm->last_name }}</td>
        <td data-col="first_name" class="px-2 py-2 border">{{ $scholar->applicationForm->first_name }}</td>
        <td data-col="middle_name" class="px-2 py-2 border">{{ $scholar->applicationForm->middle_name }}</td>
        <td data-col="level" class="px-2 py-2 text-center border">
            {{ implode(', ', $scholar->applicationForm->scholarship_type ?? []) }}
        </td>
        <td data-col="course" class="px-2 py-2 border">{{ $scholar->applicationForm->new_applicant_course }}</td>
        <td data-col="school" class="px-2 py-2 border">{{ $scholar->applicationForm->new_applicant_university }}</td>
        <td data-col="new_lateral" class="px-2 py-2 text-center border"> {{ $scholar->applicationForm->applicant_status }} </td>
        <td data-col="pt_ft" class="px-2 py-2 text-center border"> {{ $scholar->applicationForm->applicant_type }} </td>
        <td data-col="duration" class="px-2 py-2 text-center border">
            {{ implode(', ', $scholar->applicationForm->scholarship_duration ?? []) }}
        </td>
        <td data-col="date_started" class="px-2 py-2 text-center border">{{ $scholar->applicationForm->last_enrollment_date }}</td>
        <td data-col="expected_completion" class="px-2 py-2 text-center border">{{ $scholar->applicationForm->declaration_date }}</td>
        <td data-col="status" class="px-2 py-2 text-center border">{{ $scholar->applicationForm->status }}</td>
        <td data-col="remarks" class="px-2 py-2 border">{{ $scholar->applicationForm->remarks }}</td>
    </tr>
@empty
    <tr>
        <td colspan="14" class="text-center p-6 text-slate-500">No approved scholars found.</td>
    </tr>
@endforelse
</tbody>



                </table>

            </div>
        </div>
    </div>
</div>

<!-- JS: column toggles, persistence, print behaviour -->
<script>
    (function() {
        const colToggles = document.querySelectorAll('.col-toggle');
        const table = document.getElementById('scholarsTable');
        const printBtn = document.getElementById('printBtn');
        const resetBtn = document.getElementById('resetCols');

        const STORAGE_KEY = 'monitoring_scholars_cols_v1';

        // default columns (matching the checkboxes order)
        const defaultCols = [
            'no','last_name','first_name','middle_name','level','course','school',
            'new_lateral','pt_ft','duration','date_started','expected_completion','status','remarks'
        ];

        // Load saved column preferences or default
        let saved = localStorage.getItem(STORAGE_KEY);
        let visibleCols = saved ? JSON.parse(saved) : defaultCols.slice();

        // initialize checkboxes based on saved state
        function initCheckboxes() {
            colToggles.forEach(cb => {
                const col = cb.getAttribute('data-col');
                cb.checked = visibleCols.includes(col);
            });
        }

        // apply column visibility to table
        function applyColumnVisibility() {
            // header cells
            table.querySelectorAll('th[data-col]').forEach(th => {
                const col = th.getAttribute('data-col');
                if (!visibleCols.includes(col)) th.classList.add('col-hidden');
                else th.classList.remove('col-hidden');
            });
            // body cells
            table.querySelectorAll('td[data-col]').forEach(td => {
                const col = td.getAttribute('data-col');
                if (!visibleCols.includes(col)) td.classList.add('col-hidden');
                else td.classList.remove('col-hidden');
            });
        }

        // save to localStorage
        function savePrefs() {
            localStorage.setItem(STORAGE_KEY, JSON.stringify(visibleCols));
        }

        // toggle checkbox handlers
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

        // reset button handler
        resetBtn.addEventListener('click', function() {
            visibleCols = defaultCols.slice();
            initCheckboxes();
            applyColumnVisibility();
            savePrefs();
        });

        // print button handler
        printBtn.addEventListener('click', function() {
            // ensure current visibleCols saved so print matches
            savePrefs();

            // call print
            window.print();
        });

        // initialize on load
        initCheckboxes();
        applyColumnVisibility();

        // Allow server-side filter changes (if page reloads, keep column prefs)
        // (already handled by localStorage)
    })();
</script>
@endsection
