@extends('layouts.admin-layout')

@section('content')
<form action="{{ route('admin.reports.applicants.save') }}" method="POST">
    @csrf

    <!-- Scrollable + Compact Table Container -->
            <div class="border border-white/20 rounded-2xl bg-white/20 backdrop-blur-md shadow-lg p-4 w-full overflow-auto max-h-[500px]">
    <!-- Scroll Container -->
    <div class="min-w-[700px] max-w-[1100px] w-full mx-auto">
            <table class="table-auto w-full text-[11px] text-left border border-gray-300 bg-white" id="applicants-table">
                <thead class="bg-gray-100 sticky top-0 z-10">
                    <tr class="text-[10px] text-gray-700 uppercase tracking-wider">
                        <!-- Column Headers here -->
                        <th class="p-2 border">#</th>
                        <th class="p-2 border">Last Name</th>
                        <th class="p-2 border">First Name</th>
                        <th class="p-2 border">Middle Name</th>
                        <th class="p-2 border">Suffix</th>
                        <th class="p-2 border">Street</th>
                        <th class="p-2 border">Village</th>
                        <th class="p-2 border">Town</th>
                        <th class="p-2 border">Province</th>
                        <th class="p-2 border">Zipcode</th>
                        <th class="p-2 border">District</th>
                        <th class="p-2 border">Region</th>
                        <th class="p-2 border">Email</th>
                        <th class="p-2 border">Birthday</th>
                        <th class="p-2 border">Contact No.</th>
                        <th class="p-2 border">Gender</th>
                        <th class="p-2 border">Course</th>
                        <th class="p-2 border">University</th>
                        <th class="p-2 border">Entry</th>
                        <th class="p-2 border">Level</th>
                        <th class="p-2 border">Degree</th>
                        <th class="p-2 border">Intended Univ</th>
                        <th class="p-2 border">Thesis</th>
                        <th class="p-2 border">Units Req</th>
                        <th class="p-2 border">Units Earned</th>
                        <th class="p-2 border">% Load</th>
                        <th class="p-2 border">Duration</th>
                        <th class="p-2 border">Remarks</th>
                        <th class="p-2 border">🗑</th>
                    </tr>
                </thead>
                    <tbody>
                        @foreach ($applicants as $i => $a)
                            <tr>
                                <td class="p-1 border border-gray-300 text-center">{{ $i + 1 }}</td>
                                <td class="p-1 border border-gray-300"><input type="text" name="applicants[{{ $i }}][last_name]" value="{{ $a->last_name }}"></td>
                                <td class="p-1 border border-gray-300"><input type="text" name="applicants[{{ $i }}][first_name]" value="{{ $a->first_name }}"></td>
                                <td class="p-1 border border-gray-300"><input type="text" name="applicants[{{ $i }}][middle_name]" value="{{ $a->middle_name }}"></td>
                                <td class="p-1 border border-gray-300"><input type="text" name="applicants[{{ $i }}][suffix]" value="{{ $a->suffix }}"></td>
                                <td class="p-1 border border-gray-300"><input type="text" name="applicants[{{ $i }}][street]" value="{{ $a->street }}"></td>
                                <td class="p-1 border border-gray-300"><input type="text" name="applicants[{{ $i }}][village]" value="{{ $a->village }}"></td>
                                <td class="p-1 border border-gray-300"><input type="text" name="applicants[{{ $i }}][town]" value="{{ $a->town }}"></td>
                                <td class="p-1 border border-gray-300"><input type="text" name="applicants[{{ $i }}][province]" value="{{ $a->province }}"></td>
                                <td class="p-1 border border-gray-300"><input type="number" name="applicants[{{ $i }}][zipcode]" value="{{ $a->zipcode }}"></td>
                                <td class="p-1 border border-gray-300"><input type="text" name="applicants[{{ $i }}][district]" value="{{ $a->district }}"></td>
                                <td class="p-1 border border-gray-300"><input type="text" name="applicants[{{ $i }}][region]" value="{{ $a->region }}"></td>
                                <td class="p-1 border border-gray-300"><input type="email" name="applicants[{{ $i }}][email]" value="{{ $a->email }}"></td>
                                <td class="p-1 border border-gray-300"><input type="date" name="applicants[{{ $i }}][birthday]" value="{{ $a->birthday }}"></td>
                                <td class="p-1 border border-gray-300"><input type="text" name="applicants[{{ $i }}][contact_number]" value="{{ $a->contact_number }}"></td>
                                <td class="p-1 border border-gray-300"><input type="text" name="applicants[{{ $i }}][gender]" value="{{ $a->gender }}"></td>
                                <td class="p-1 border border-gray-300"><input type="text" name="applicants[{{ $i }}][course_completed]" value="{{ $a->course_completed }}"></td>
                                <td class="p-1 border border-gray-300"><input type="text" name="applicants[{{ $i }}][university_graduated]" value="{{ $a->university_graduated }}"></td>
                                <td class="p-1 border border-gray-300">
                                    <select name="applicants[{{ $i }}][entry_type]">
                                        <option value="new" {{ $a->entry_type == 'new' ? 'selected' : '' }}>New</option>
                                        <option value="lateral" {{ $a->entry_type == 'lateral' ? 'selected' : '' }}>Lateral</option>
                                    </select>
                                </td>
                                <td class="p-1 border border-gray-300">
                                    <select name="applicants[{{ $i }}][level]">
                                        <option value="MS" {{ $a->level == 'MS' ? 'selected' : '' }}>MS</option>
                                        <option value="PHD" {{ $a->level == 'PHD' ? 'selected' : '' }}>PhD</option>
                                    </select>
                                </td>
                                <td class="p-1 border border-gray-300"><input type="text" name="applicants[{{ $i }}][intended_degree]" value="{{ $a->intended_degree }}"></td>
                                <td class="p-1 border border-gray-300"><input type="text" name="applicants[{{ $i }}][intended_university]" value="{{ $a->intended_university }}"></td>
                                <td class="p-1 border border-gray-300"><textarea name="applicants[{{ $i }}][thesis_title]">{{ $a->thesis_title }}</textarea></td>
                                <td class="p-1 border border-gray-300"><input type="number" name="applicants[{{ $i }}][units_required]" value="{{ $a->units_required }}"></td>
                                <td class="p-1 border border-gray-300"><input type="number" name="applicants[{{ $i }}][units_earned_prior]" value="{{ $a->units_earned_prior }}"></td>
                                <td class="p-1 border border-gray-300"><input type="text" name="applicants[{{ $i }}][percent_load_prior]" value="{{ $a->percent_load_prior }}"></td>
                                <td class="p-1 border border-gray-300"><input type="text" name="applicants[{{ $i }}][scholarship_duration]" value="{{ $a->scholarship_duration }}"></td>
                                <td class="p-1 border border-gray-300"><textarea name="applicants[{{ $i }}][remarks]">{{ $a->remarks }}</textarea></td>
                                <td class="p-1 border border-gray-300 text-center">
                                    <button type="button" onclick="removeRow(this)" class="text-red-600 hover:underline">🗑️</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Buttons -->
    <div class="mt-4 flex gap-4">
        <button type="button" onclick="addRow()" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 text-sm">➕ Add Row</button>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">💾 Save All Changes</button>
    </div>
</form>

<!-- JavaScript -->
<script>
    let index = {{ count($applicants) }};

    function addRow() {
        const table = document.querySelector("#applicants-table tbody");
        const row = document.createElement("tr");
        row.innerHTML = `
            <td class="p-1 border border-gray-300 text-center">${index + 1}</td>
            <td class="p-1 border border-gray-300"><input type="text" name="applicants[${index}][last_name]"></td>
            <td class="p-1 border border-gray-300"><input type="text" name="applicants[${index}][first_name]"></td>
            <td class="p-1 border border-gray-300"><input type="text" name="applicants[${index}][middle_name]"></td>
            <td class="p-1 border border-gray-300"><input type="text" name="applicants[${index}][suffix]"></td>
            <td class="p-1 border border-gray-300"><input type="text" name="applicants[${index}][street]"></td>
            <td class="p-1 border border-gray-300"><input type="text" name="applicants[${index}][village]"></td>
            <td class="p-1 border border-gray-300"><input type="text" name="applicants[${index}][town]"></td>
            <td class="p-1 border border-gray-300"><input type="text" name="applicants[${index}][province]"></td>
            <td class="p-1 border border-gray-300"><input type="number" name="applicants[${index}][zipcode]"></td>
            <td class="p-1 border border-gray-300"><input type="text" name="applicants[${index}][district]"></td>
            <td class="p-1 border border-gray-300"><input type="text" name="applicants[${index}][region]"></td>
            <td class="p-1 border border-gray-300"><input type="email" name="applicants[${index}][email]"></td>
            <td class="p-1 border border-gray-300"><input type="date" name="applicants[${index}][birthday]"></td>
            <td class="p-1 border border-gray-300"><input type="text" name="applicants[${index}][contact_number]"></td>
            <td class="p-1 border border-gray-300"><input type="text" name="applicants[${index}][gender]"></td>
            <td class="p-1 border border-gray-300"><input type="text" name="applicants[${index}][course_completed]"></td>
            <td class="p-1 border border-gray-300"><input type="text" name="applicants[${index}][university_graduated]"></td>
            <td class="p-1 border border-gray-300">
                <select name="applicants[${index}][entry_type]">
                    <option value="new">New</option>
                    <option value="lateral">Lateral</option>
                </select>
            </td>
            <td class="p-1 border border-gray-300">
                <select name="applicants[${index}][level]">
                    <option value="MS">MS</option>
                    <option value="PHD">PhD</option>
                </select>
            </td>
            <td class="p-1 border border-gray-300"><input type="text" name="applicants[${index}][intended_degree]"></td>
            <td class="p-1 border border-gray-300"><input type="text" name="applicants[${index}][intended_university]"></td>
            <td class="p-1 border border-gray-300"><textarea name="applicants[${index}][thesis_title]"></textarea></td>
            <td class="p-1 border border-gray-300"><input type="number" name="applicants[${index}][units_required]"></td>
            <td class="p-1 border border-gray-300"><input type="number" name="applicants[${index}][units_earned_prior]"></td>
            <td class="p-1 border border-gray-300"><input type="text" name="applicants[${index}][percent_load_prior]"></td>
            <td class="p-1 border border-gray-300"><input type="text" name="applicants[${index}][scholarship_duration]"></td>
            <td class="p-1 border border-gray-300"><textarea name="applicants[${index}][remarks]"></textarea></td>
            <td class="p-1 border border-gray-300 text-center"><button type="button" onclick="removeRow(this)" class="text-red-600 hover:underline">🗑️</button></td>
        `;
        table.appendChild(row);
        index++;
    }

    function removeRow(button) {
        button.closest("tr").remove();
    }
</script>
@endsection
