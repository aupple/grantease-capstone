@extends('layouts.admin-layout')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
        <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
            List of All Applicants
        </h1>
    </div>

    <!-- Filter Card -->
    <div class="bg-white/30 backdrop-blur-lg shadow-md border border-white/20 rounded-2xl p-6">
        <form method="GET" action="{{ route('admin.reports.applicants') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <!-- Academic Year -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Academic Year</label>
                <select name="academic_year"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All</option>
                    @foreach (['2024-2025', '2025-2026'] as $year)
                        <option value="{{ $year }}" {{ $academicYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
            </div>

            <!-- School Term -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">School Term</label>
                <select name="school_term"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All</option>
                    @foreach (['1st Semester', '2nd Semester'] as $term)
                        <option value="{{ $term }}" {{ $schoolTerm == $term ? 'selected' : '' }}>{{ $term }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Buttons -->
            <div class="flex items-end gap-2">
                <button type="submit"
                        class=" bg-blue-600 font-medium text-white text-sm px-4 py-2 rounded-lg hover:bg-blue-700 transition shadow-md">
                    Filter
                </button>
                <button id="printBtn" type="button"
                        class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg shadow-md hover:bg-green-700 transition">
                    Print
                </button>
                <button id="resetCols" type="button"
                        class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg shadow-md hover:bg-gray-600 transition">
                    Undo Columns
                </button>
            </div>
        </form>
    </div>


    <!-- Column Controls Card -->
    <div class="bg-white/30 backdrop-blur-lg shadow-md border border-white/20 rounded-2xl p-5">
        <h2 class="text-sm font-semibold text-gray-700 mb-3">Select Fields</h2>
        <div class="flex flex-wrap gap-4">
            @foreach ([
                'no' => 'No.', 'last_name' => 'Last Name', 'first_name' => 'First Name', 'middle_name' => 'Middle Name',
                'suffix' => 'Suffix', 'street' => 'Street', 'village' => 'Village', 'town' => 'Town', 'province' => 'Province',
                'zipcode' => 'Zipcode', 'district' => 'District', 'region' => 'Region', 'email' => 'Email', 'bday' => 'Birthday',
                'contact_no' => 'Contact No.', 'gender' => 'Gender', 'course_completed' => 'Course Completed',
                'university_graduated' => 'University Graduated', 'entry' => 'Entry', 'level' => 'Level',
                'intended_degree' => 'Intended Degree', 'university' => 'University', 'thesis_title' => 'Thesis/Dissertation Title',
                'units_required' => 'Units Required', 'units_earned' => 'Units Earned', 'percent_completed' => '% Completed',
                'duration' => 'Duration', 'remarks' => 'Remarks'
            ] as $col => $label)
                <label class="flex items-center text-sm text-gray-800">
                    <input type="checkbox" class="col-toggle mr-1 accent-blue-600" data-col="{{ $col }}" checked>
                    {{ $label }}
                </label>
            @endforeach
        </div>
    </div>

    <!-- Table Section (Unchanged) -->
    <div class="border border-white/20 rounded-2xl bg-white/20 backdrop-blur-md shadow-lg p-4 w-full overflow-auto max-h-[500px]">
        <div class="min-w-[700px] max-w-[1200px] w-full mx-auto">
            <table class="table-auto w-full text-xs text-left border border-gray-300 bg-white" id="applicants-table">
                <thead class="bg-gray-100 sticky top-0 z-10">
                    <tr>
                        @foreach ([
                            'no' => 'No.', 'last_name' => 'Last Name', 'first_name' => 'First Name', 'middle_name' => 'Middle Name',
                            'suffix' => 'Suffix', 'street' => 'Street', 'village' => 'Village', 'town' => 'Town', 'province' => 'Province',
                            'zipcode' => 'Zipcode', 'district' => 'District', 'region' => 'Region', 'email' => 'Email', 'bday' => 'Birthday',
                            'contact_no' => 'Contact No.', 'gender' => 'Gender', 'course_completed' => 'Course Completed',
                            'university_graduated' => 'University Graduated', 'entry' => 'Entry', 'level' => 'Level',
                            'intended_degree' => 'Intended Degree', 'university' => 'University', 'thesis_title' => 'Thesis/Dissertation Title',
                            'units_required' => 'Units Required', 'units_earned' => 'Units Earned', 'percent_completed' => '% Completed',
                            'duration' => 'Duration', 'remarks' => 'Remarks'
                        ] as $col => $label)
                            <th data-col="{{ $col }}" class="border px-1 py-1 text-left whitespace-nowrap">{{ $label }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($applicants as $index => $a)
                        <tr class="border-t hover:bg-gray-50">
                            <td data-col="no" class="border px-2 py-1 text-center">{{ $index + 1 }}</td>
                            <td data-col="last_name" class="border px-2 py-1">{{ $a->last_name }}</td>
                            <td data-col="first_name" class="border px-2 py-1">{{ $a->first_name }}</td>
                            <td data-col="middle_name" class="border px-2 py-1">{{ $a->middle_name }}</td>
                            <td data-col="suffix" class="border px-2 py-1">{{ $a->suffix }}</td>
                            <td data-col="street" class="border px-2 py-1">{{ $a->street }}</td>
                            <td data-col="village" class="border px-2 py-1">{{ $a->village }}</td>
                            <td data-col="town" class="border px-2 py-1">{{ $a->town }}</td>
                            <td data-col="province" class="border px-2 py-1">{{ $a->province }}</td>
                            <td data-col="zipcode" class="border px-2 py-1">{{ $a->zipcode }}</td>
                            <td data-col="district" class="border px-2 py-1">{{ $a->district }}</td>
                            <td data-col="region" class="border px-2 py-1">{{ $a->region }}</td>
                            <td data-col="email" class="border px-2 py-1">{{ $a->email }}</td>
                            <td data-col="bday" class="border px-2 py-1">{{ $a->bday }}</td>
                            <td data-col="contact_no" class="border px-2 py-1">{{ $a->contact_no }}</td>
                            <td data-col="gender" class="border px-2 py-1">{{ strtoupper($a->gender) }}</td>
                            <td data-col="course_completed" class="border px-2 py-1">{{ $a->course_completed }}</td>
                            <td data-col="university_graduated" class="border px-2 py-1">{{ $a->university_graduated }}</td>
                            <td data-col="entry" class="border px-2 py-1">{{ ucfirst($a->entry) }}</td>
                            <td data-col="level" class="border px-2 py-1">{{ strtoupper($a->level) }}</td>
                            <td data-col="intended_degree" class="border px-2 py-1">{{ $a->intended_degree }}</td>
                            <td data-col="university" class="border px-2 py-1">{{ $a->university }}</td>
                            <td data-col="thesis_title" class="border px-2 py-1">{{ $a->thesis_title }}</td>
                            <td data-col="units_required" class="border px-2 py-1 text-center">{{ $a->units_required }}</td>
                            <td data-col="units_earned" class="border px-2 py-1 text-center">{{ $a->units_earned }}</td>
                            <td data-col="percent_completed" class="border px-2 py-1 text-center">{{ $a->percent_completed }}</td>
                            <td data-col="duration" class="border px-2 py-1">{{ $a->duration }}</td>
                            <td data-col="remarks" class="border px-2 py-1">{{ $a->remarks }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Column toggle + print logic -->
<script>
    (function() {
        const colToggles = document.querySelectorAll('.col-toggle');
        const table = document.getElementById('applicants-table');
        const printBtn = document.getElementById('printBtn');
        const resetBtn = document.getElementById('resetCols');
        const STORAGE_KEY = 'applicants_cols_v1';

        const defaultCols = Array.from(colToggles).map(cb => cb.dataset.col);
        let saved = localStorage.getItem(STORAGE_KEY);
        let visibleCols = saved ? JSON.parse(saved) : defaultCols.slice();

        function initCheckboxes() {
            colToggles.forEach(cb => {
                const col = cb.dataset.col;
                cb.checked = visibleCols.includes(col);
            });
        }

        function applyColumnVisibility() {
            table.querySelectorAll('[data-col]').forEach(el => {
                const col = el.dataset.col;
                el.classList.toggle('hidden', !visibleCols.includes(col));
            });
        }

        function savePrefs() {
            localStorage.setItem(STORAGE_KEY, JSON.stringify(visibleCols));
        }

        colToggles.forEach(cb => {
            cb.addEventListener('change', function() {
                const col = this.dataset.col;
                if (this.checked) visibleCols.push(col);
                else visibleCols = visibleCols.filter(c => c !== col);
                applyColumnVisibility();
                savePrefs();
            });
        });

        resetBtn.addEventListener('click', function() {
            visibleCols = defaultCols.slice();
            initCheckboxes();
            applyColumnVisibility();
            savePrefs();
        });

        printBtn.addEventListener('click', function() {
            savePrefs();
            window.open("{{ route('admin.reports.applicants.print') }}?academic_year={{ $academicYear }}&school_term={{ $schoolTerm }}", '_blank');
        });

        initCheckboxes();
        applyColumnVisibility();
    })();
</script>
@endsection
