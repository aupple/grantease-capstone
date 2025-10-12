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

<div class="container mx-auto px-4 py-6">
    <!-- Header & Controls card -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <div class="flex items-start justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Monitoring Scholars</h1>
                <p class="text-sm text-slate-500 mt-1">View and print scholar monitoring report</p>
            </div>

            <!-- Action buttons -->
            <div class="no-print flex items-center gap-2">
                <a href="{{ route('admin.reports.monitoring.print', [
    'semester' => request('semester'),
    'program' => request('program'),
    'academic_year' => request('academic_year')
]) }}"
   target="_blank"
   class="flex-1 bg-green-600 font-semibold text-white text-sm px-4 py-2 rounded-lg hover:bg-blue-700 transition shadow-md">
   Preview Print
</a>

            </div>
        </div>

           <!-- Filter + Columns selection card -->
<div class="mt-5 bg-slate-50 rounded-md p-4">
    <form id="filtersForm" method="GET" action="{{ route('admin.reports.monitoring') }}" class="space-y-4">
        <!-- Filters row -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Semester filter -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Semester</label>
                <select name="semester" class="w-full rounded border-gray-300">
                    <option value="" {{ request('semester') == '' ? 'selected' : '' }}>All Semesters</option>
                    <option value="1st" {{ request('semester') == '1st' ? 'selected' : '' }}>1st Semester</option>
                    <option value="2nd" {{ request('semester') == '2nd' ? 'selected' : '' }}>2nd Semester</option>
                </select>
            </div>
            <!-- Filter Button -->
            <div class="flex items-end">
                <button type="submit"
                    class="bg-blue-600 font-semibold text-white px-2 py-2 rounded-md hover:bg-blue-700 transition">
                    Filter
                </button>
            </div>
        </div>

        <!-- Field Selection Section -->
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">
                Select Fields to Display
            </label>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-2 text-sm">
                <label class="inline-flex items-center"><input type="checkbox" class="col-toggle" data-col="no" checked> <span class="ml-2">NO.</span></label>
                <label class="inline-flex items-center"><input type="checkbox" class="col-toggle" data-col="last_name" checked> <span class="ml-2">LAST NAME</span></label>
                <label class="inline-flex items-center"><input type="checkbox" class="col-toggle" data-col="first_name" checked> <span class="ml-2">FIRST NAME</span></label>
                <label class="inline-flex items-center"><input type="checkbox" class="col-toggle" data-col="middle_name" checked> <span class="ml-2">MIDDLE NAME</span></label>
                <label class="inline-flex items-center"><input type="checkbox" class="col-toggle" data-col="level" checked> <span class="ml-2">LEVEL (MS/PhD)</span></label>
                <label class="inline-flex items-center"><input type="checkbox" class="col-toggle" data-col="course" checked> <span class="ml-2">COURSE</span></label>
                <label class="inline-flex items-center"><input type="checkbox" class="col-toggle" data-col="school" checked> <span class="ml-2">SCHOOL</span></label>
                <label class="inline-flex items-center"><input type="checkbox" class="col-toggle" data-col="new_lateral" checked> <span class="ml-2">NEW / LATERAL</span></label>
                <label class="inline-flex items-center"><input type="checkbox" class="col-toggle" data-col="pt_ft" checked> <span class="ml-2">PART-TIME / FULL-TIME</span></label>
                <label class="inline-flex items-center"><input type="checkbox" class="col-toggle" data-col="duration" checked> <span class="ml-2">SCHOLARSHIP DURATION</span></label>
                <label class="inline-flex items-center"><input type="checkbox" class="col-toggle" data-col="date_started" checked> <span class="ml-2">DATE STARTED</span></label>
                <label class="inline-flex items-center"><input type="checkbox" class="col-toggle" data-col="expected_completion" checked> <span class="ml-2">EXPECTED COMPLETION</span></label>
                <label class="inline-flex items-center"><input type="checkbox" class="col-toggle" data-col="status" checked> <span class="ml-2">STATUS</span></label>
                <label class="inline-flex items-center"><input type="checkbox" class="col-toggle" data-col="remarks" checked> <span class="ml-2">REMARKS</span></label>
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
    @forelse($monitorings as $index => $monitor)
        <tr class="odd:bg-white even:bg-slate-50">
            <td data-col="no" class="px-2 py-2 text-center border">{{ $index + 1 }}</td>
            <td data-col="last_name" class="px-2 py-2 border">{{ $monitor->last_name }}</td>
            <td data-col="first_name" class="px-2 py-2 border">{{ $monitor->first_name }}</td>
            <td data-col="middle_name" class="px-2 py-2 border">{{ $monitor->middle_name }}</td>
            <td data-col="level" class="px-2 py-2 text-center border">{{ $monitor->degree_type }}</td>
            <td data-col="course" class="px-2 py-2 border">{{ $monitor->course }}</td>
            <td data-col="school" class="px-2 py-2 border">{{ $monitor->school }}</td>
            <td data-col="new_lateral" class="px-2 py-2 text-center border">{{ $monitor->new_or_lateral }}</td>
            <td data-col="pt_ft" class="px-2 py-2 text-center border">{{ $monitor->enrollment_type }}</td>
            <td data-col="duration" class="px-2 py-2 text-center border">{{ $monitor->scholarship_duration }}</td>
            <td data-col="date_started" class="px-2 py-2 text-center border">{{ $monitor->date_started }}</td>
            <td data-col="expected_completion" class="px-2 py-2 text-center border">{{ $monitor->expected_completion }}</td>
            <td data-col="status" class="px-2 py-2 text-center border">{{ $monitor->status_code }}</td>
            <td data-col="remarks" class="px-2 py-2 border">{{ $monitor->remarks }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="14" class="text-center p-6 text-slate-500">No monitoring data found.</td>
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
