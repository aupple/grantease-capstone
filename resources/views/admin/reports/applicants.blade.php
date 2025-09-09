@extends('layouts.admin-layout')

@section('content')
<form action="{{ route('admin.reports.applicants.save') }}" method="POST">
    @csrf

    <h2 class="text-3xl font-bold text-black-700 mb-4">List of all applicants</h2>

    <div class="border border-white/20 rounded-2xl bg-white/20 backdrop-blur-md shadow-lg p-4 w-full overflow-auto max-h-[500px]">
        <div class="min-w-[700px] max-w-[1100px] w-full mx-auto">
            <table class="table-auto w-full text-xs text-left border border-gray-300 bg-white" id="applicants-table">
                <thead class="bg-gray-100 sticky top-0 z-10">
                    <tr class="text-xs text-gray-700 uppercase tracking-wider">
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
                        <tr class="{{ $i % 2 == 0 ? 'bg-gray-50' : 'bg-white' }}">
                            <td class="p-1 border border-gray-300 text-center">{{ $i + 1 }}</td>
                            <td class="p-1 border border-gray-300"><input type="text" aria-label="Last Name" name="applicants[{{ $i }}][last_name]" placeholder="Last Name" value="{{ $a->last_name }}"></td>
                            <td class="p-1 border border-gray-300"><input type="text" aria-label="First Name" name="applicants[{{ $i }}][first_name]" placeholder="First Name" value="{{ $a->first_name }}"></td>
                            <td class="p-1 border border-gray-300"><input type="text" aria-label="Middle Name" name="applicants[{{ $i }}][middle_name]" placeholder="Middle Name" value="{{ $a->middle_name }}"></td>
                            <td class="p-1 border border-gray-300"><input type="text" aria-label="Suffix" name="applicants[{{ $i }}][suffix]" placeholder="Suffix" value="{{ $a->suffix }}"></td>
                            <td class="p-1 border border-gray-300"><input type="text" aria-label="Street" name="applicants[{{ $i }}][street]" placeholder="Street" value="{{ $a->street }}"></td>
                            <td class="p-1 border border-gray-300"><input type="text" aria-label="Village" name="applicants[{{ $i }}][village]" placeholder="Village" value="{{ $a->village }}"></td>
                            <td class="p-1 border border-gray-300"><input type="text" aria-label="Town" name="applicants[{{ $i }}][town]" placeholder="Town" value="{{ $a->town }}"></td>
                            <td class="p-1 border border-gray-300"><input type="text" aria-label="Province" name="applicants[{{ $i }}][province]" placeholder="Province" value="{{ $a->province }}"></td>
                            <td class="p-1 border border-gray-300"><input type="number" aria-label="Zipcode" name="applicants[{{ $i }}][zipcode]" placeholder="Zipcode" value="{{ $a->zipcode }}"></td>
                            <td class="p-1 border border-gray-300"><input type="text" aria-label="District" name="applicants[{{ $i }}][district]" placeholder="District" value="{{ $a->district }}"></td>
                            <td class="p-1 border border-gray-300"><input type="text" aria-label="Region" name="applicants[{{ $i }}][region]" placeholder="Region" value="{{ $a->region }}"></td>
                            <td class="p-1 border border-gray-300"><input type="email" aria-label="Email" name="applicants[{{ $i }}][email]" placeholder="Email" value="{{ $a->email }}"></td>
                            <td class="p-1 border border-gray-300"><input type="date" aria-label="Birthday" name="applicants[{{ $i }}][birthday]" value="{{ $a->birthday }}"></td>
                            <td class="p-1 border border-gray-300"><input type="tel" aria-label="Contact No." name="applicants[{{ $i }}][contact_number]" placeholder="Contact No." value="{{ $a->contact_number }}"></td>
                            <td class="p-1 border border-gray-300">
                                <select name="applicants[{{ $i }}][gender]" aria-label="Gender">
                                    <option value="" disabled>Select Gender</option>
                                    <option value="Male" {{ $a->gender == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ $a->gender == 'Female' ? 'selected' : '' }}>Female</option>
                                    <option value="Other" {{ $a->gender == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </td>
                            <td class="p-1 border border-gray-300"><input type="text" aria-label="Course Completed" name="applicants[{{ $i }}][course_completed]" placeholder="Course" value="{{ $a->course_completed }}"></td>
                            <td class="p-1 border border-gray-300"><input type="text" aria-label="University Graduated" name="applicants[{{ $i }}][university_graduated]" placeholder="University" value="{{ $a->university_graduated }}"></td>
                            <td class="p-1 border border-gray-300">
                                <select name="applicants[{{ $i }}][entry_type]" aria-label="Entry Type">
                                    <option value="new" {{ $a->entry_type == 'new' ? 'selected' : '' }}>New</option>
                                    <option value="lateral" {{ $a->entry_type == 'lateral' ? 'selected' : '' }}>Lateral</option>
                                </select>
                            </td>
                            <td class="p-1 border border-gray-300">
                                <select name="applicants[{{ $i }}][level]" aria-label="Level">
                                    <option value="MS" {{ $a->level == 'MS' ? 'selected' : '' }}>MS</option>
                                    <option value="PHD" {{ $a->level == 'PHD' ? 'selected' : '' }}>PhD</option>
                                </select>
                            </td>
                            <td class="p-1 border border-gray-300"><input type="text" aria-label="Intended Degree" name="applicants[{{ $i }}][intended_degree]" placeholder="Degree" value="{{ $a->intended_degree }}"></td>
                            <td class="p-1 border border-gray-300"><input type="text" aria-label="Intended University" name="applicants[{{ $i }}][intended_university]" placeholder="Intended University" value="{{ $a->intended_university }}"></td>
                            <td class="p-1 border border-gray-300"><textarea aria-label="Thesis Title" name="applicants[{{ $i }}][thesis_title]" placeholder="Thesis Title">{{ $a->thesis_title }}</textarea></td>
                            <td class="p-1 border border-gray-300"><input type="number" aria-label="Units Required" name="applicants[{{ $i }}][units_required]" placeholder="Units Required" value="{{ $a->units_required }}"></td>
                            <td class="p-1 border border-gray-300"><input type="number" aria-label="Units Earned Prior" name="applicants[{{ $i }}][units_earned_prior]" placeholder="Units Earned" value="{{ $a->units_earned_prior }}"></td>
                            <td class="p-1 border border-gray-300"><input type="text" aria-label="Percent Load Prior" name="applicants[{{ $i }}][percent_load_prior]" placeholder="% Load" value="{{ $a->percent_load_prior }}"></td>
                            <td class="p-1 border border-gray-300"><input type="text" aria-label="Scholarship Duration" name="applicants[{{ $i }}][scholarship_duration]" placeholder="Duration" value="{{ $a->scholarship_duration }}"></td>
                            <td class="p-1 border border-gray-300"><textarea aria-label="Remarks" name="applicants[{{ $i }}][remarks]" placeholder="Remarks">{{ $a->remarks }}</textarea></td>
                            <td class="p-1 border border-gray-300 text-center">
                                <button type="button" onclick="removeRow(this)" class="text-red-600 hover:underline" title="Delete Row">🗑️</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4 flex gap-4">
        <button type="button" onclick="addRow()" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 text-sm" title="Add a new applicant row">
            <span aria-hidden="true">➕</span> Add Row
        </button>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm" title="Save all changes">
            <span aria-hidden="true">💾</span> Save All Changes
        </button>
    </div>
</form>

<script>
    let index = {{ count($applicants) }};

    function addRow() {
        const table = document.querySelector("#applicants-table tbody");
        const row = document.createElement("tr");
        row.className = index % 2 === 0 ? "bg-gray-50" : "bg-white";
        row.innerHTML = `
            <td class="p-1 border border-gray-300 text-center">${index + 1}</td>
            <td class="p-1 border border-gray-300"><input type="text" aria-label="Last Name" name="applicants[${index}][last_name]" placeholder="Last Name"></td>
            <td class="p-1 border border-gray-300"><input type="text" aria-label="First Name" name="applicants[${index}][first_name]" placeholder="First Name"></td>
            <td class="p-1 border border-gray-300"><input type="text" aria-label="Middle Name" name="applicants[${index}][middle_name]" placeholder="Middle Name"></td>
            <td class="p-1 border border-gray-300"><input type="text" aria-label="Suffix" name="applicants[${index}][suffix]" placeholder="Suffix"></td>
            <td class="p-1 border border-gray-300"><input type="text" aria-label="Street" name="applicants[${index}][street]" placeholder="Street"></td>
            <td class="p-1 border border-gray-300"><input type="text" aria-label="Village" name="applicants[${index}][village]" placeholder="Village"></td>
            <td class="p-1 border border-gray-300"><input type="text" aria-label="Town" name="applicants[${index}][town]" placeholder="Town"></td>
            <td class="p-1 border border-gray-300"><input type="text" aria-label="Province" name="applicants[${index}][province]" placeholder="Province"></td>
            <td class="p-1 border border-gray-300"><input type="number" aria-label="Zipcode" name="applicants[${index}][zipcode]" placeholder="Zipcode"></td>
            <td class="p-1 border border-gray-300"><input type="text" aria-label="District" name="applicants[${index}][district]" placeholder="District"></td>
            <td class="p-1 border border-gray-300"><input type="text" aria-label="Region" name="applicants[${index}][region]" placeholder="Region"></td>
            <td class="p-1 border border-gray-300"><input type="email" aria-label="Email" name="applicants[${index}][email]" placeholder="Email"></td>
            <td class="p-1 border border-gray-300"><input type="date" aria-label="Birthday" name="applicants[${index}][birthday]"></td>
            <td class="p-1 border border-gray-300"><input type="tel" aria-label="Contact No." name="applicants[${index}][contact_number]" placeholder="Contact No."></td>
            <td class="p-1 border border-gray-300">
                <select name="applicants[${index}][gender]" aria-label="Gender">
                    <option value="" disabled selected>Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
            </td>
            <td class="p-1 border border-gray-300"><input type="text" aria-label="Course Completed" name="applicants[${index}][course_completed]" placeholder="Course"></td>
            <td class="p-1 border border-gray-300"><input type="text" aria-label="University Graduated" name="applicants[${index}][university_graduated]" placeholder="University"></td>
            <td class="p-1 border border-gray-300">
                <select name="applicants[${index}][entry_type]" aria-label="Entry Type">
                    <option value="new">New</option>
                    <option value="lateral">Lateral</option>
                </select>
            </td>
            <td class="p-1 border border-gray-300">
                <select name="applicants[${index}][level]" aria-label="Level">
                    <option value="MS">MS</option>
                    <option value="PHD">PhD</option>
                </select>
            </td>
            <td class="p-1 border border-gray-300"><input type="text" aria-label="Intended Degree" name="applicants[${index}][intended_degree]" placeholder="Degree"></td>
            <td class="p-1 border border-gray-300"><input type="text" aria-label="Intended University" name="applicants[${index}][intended_university]" placeholder="Intended University"></td>
            <td class="p-1 border border-gray-300"><textarea aria-label="Thesis Title" name="applicants[${index}][thesis_title]" placeholder="Thesis Title"></textarea></td>
            <td class="p-1 border border-gray-300"><input type="number" aria-label="Units Required" name="applicants[${index}][units_required]" placeholder="Units Required"></td>
            <td class="p-1 border border-gray-300"><input type="number" aria-label="Units Earned Prior" name="applicants[${index}][units_earned_prior]" placeholder="Units Earned"></td>
            <td class="p-1 border border-gray-300"><input type="text" aria-label="Percent Load Prior" name="applicants[${index}][percent_load_prior]" placeholder="% Load"></td>
            <td class="p-1 border border-gray-300"><input type="text" aria-label="Scholarship Duration" name="applicants[${index}][scholarship_duration]" placeholder="Duration"></td>
            <td class="p-1 border border-gray-300"><textarea aria-label="Remarks" name="applicants[${index}][remarks]" placeholder="Remarks"></textarea></td>
            <td class="p-1 border border-gray-300 text-center">
                <button type="button" onclick="removeRow(this)" class="text-red-600 hover:underline" title="Delete Row">🗑️</button>
            </td>
        `;
        table.appendChild(row);
        index++;
    }

    function removeRow(button) {
        if (confirm('Are you sure you want to delete this row?')) {
            button.closest("tr").remove();
        }
    }
</script>
@endsection