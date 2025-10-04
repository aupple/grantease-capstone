@extends('layouts.admin-layout')

@section('content')
@php
    $type = request('type', $type ?? 'applicant');
@endphp

<h2 class="text-3xl font-bold text-black-700 mb-4">List of all applicants</h2>

<!-- FILTER DROPDOWN FORM -->
<form action="{{ route('admin.reports.applicants') }}" method="GET" class="mb-4">
    <label class="block mb-1 text-sm font-medium text-gray-700">Filter by Type:</label>
    <select name="type" class="w-full max-w-xl bg-white/30 backdrop-blur-sm border border-white/20 shadow p-2 rounded" onchange="this.form.submit()">
        <option value="applicant" {{ ($type === 'applicant') ? 'selected' : '' }}>Applicants</option>
        <option value="scholar" {{ ($type === 'scholar') ? 'selected' : '' }}>Scholars</option>
    </select>
</form>

<!-- EXPORT SELECTED RECORDS FORM: Wrap table and checkboxes -->
<form action="{{ route('admin.reports.export-selected') }}" method="POST" class="mb-6">
    @csrf
    <input type="hidden" name="type" value="{{ $type }}">

     <div class="border border-white/20 rounded-2xl bg-white/20 backdrop-blur-md shadow-lg p-4 w-full overflow-auto max-h-[700px]">
    <div class="min-w-[800px] max-w-[1150px] w-full mx-auto">
                <table class="table-auto w-full text-sm text-left" id="export-table">
                @php
                    $columns = [
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
                        'email' => 'Email Address',
                        'birthday' => 'Birthday',
                        'contact_number' => 'Contact No.',
                        'gender' => 'Gender',
                        'course_completed' => 'Course Completed',
                        'university_graduated' => 'University Graduated',
                        'entry' => 'Entry',
                        'level' => 'Level',
                        'intended_degree' => 'Intended Masters/Doctoral Degree',
                        'university' => 'University',
                        'thesis_title' => 'Thesis/Dissertation Title',
                        'units_already' => 'Number of Units Already Earned Prior to Scholarship Award',
                        'percent_load_completed' => '% of Required Load Completed Prior to Scholarship Award',
                        'duration' => 'Duration of Scholarship',
                        'remarks' => 'Remarks'
                    ];
                @endphp

                <table id="export-table" class="table-auto text-sm text-left whitespace-nowrap min-w-[1400px] border-collapse">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-2 border">
                                <label class="flex items-center space-x-2">
                                    <input type="checkbox" onclick="toggleAll(this)">
                                    <span>Select</span>
                                </label>
                            </th>

                            @foreach ($columns as $key => $label)
                                <th class="p-1 border text-xs">
    <label class="flex items-center space-x-1">
        <input type="checkbox" class="column-toggle w-3 h-3" data-column="{{ $key }}">
        <span>{{ $label }}</span>
    </label>
</th>

                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($applicants as $applicant)
                            <tr class="hover:bg-gray-50">
                                <td class="p-2 border">
                                    <input type="checkbox" name="selected[]" value="{{ $applicant->id }}">
                                </td>

                                @foreach ($columns as $key => $label)
                                    <td class="p-2 border column-{{ $key }}"
                                        style="{{ in_array($key, ['last_name', 'email', 'status','first_name','middle_name']) ? '' : 'display:none;' }}">
                                        @switch($key)
                                            @case('no')
                                                {{ $loop->parent->iteration }}
                                                @break
                                            @case('last_name')
                                                {{ $applicant->user->last_name ?? $applicant->last_name ?? '—' }}
                                                @break
                                            @case('first_name')
                                                {{ $applicant->user->first_name ?? $applicant->first_name ?? '—' }}
                                                @break
                                            @case('middle_name')
                                                {{ $applicant->user->middle_name ?? $applicant->middle_name ?? '—' }}
                                                @break
                                            @case('suffix')
                                                {{ $applicant->user->suffix ?? $applicant->suffix ?? '—' }}
                                                @break
                                            @case('street')
                                                {{ $applicant->user->street ?? $applicant->street ?? '—' }}
                                                @break
                                            @case('village')
                                                {{ $applicant->user->village ?? $applicant->village ?? '—' }}
                                                @break
                                            @case('town')
                                                {{ $applicant->user->town ?? $applicant->town ?? '—' }}
                                                @break
                                            @case('province')
                                                {{ $applicant->user->province ?? $applicant->province ?? '—' }}
                                                @break
                                            @case('zipcode')
                                                {{ $applicant->user->zipcode ?? $applicant->zipcode ?? '—' }}
                                                @break
                                            @case('district')
                                                {{ $applicant->user->district ?? $applicant->district ?? '—' }}
                                                @break
                                            @case('region')
                                                {{ $applicant->user->region ?? $applicant->region ?? '—' }}
                                                @break
                                            @case('email')
                                                {{ $applicant->user->email ?? $applicant->email ?? '—' }}
                                                @break
                                            @case('birthday')
                                                {{ optional($applicant->user)->birthday ?? $applicant->birthday ?? '—' }}
                                                @break
                                            @case('contact_number')
                                                {{ $applicant->user->contact_number ?? $applicant->contact_number ?? '—' }}
                                                @break
                                            @case('gender')
                                                {{ $applicant->user->gender ?? $applicant->gender ?? '—' }}
                                                @break
                                            @case('course_completed')
                                                {{ $applicant->user->course_completed ?? $applicant->course_completed ?? $applicant->course ?? '—' }}
                                                @break
                                            @case('university_graduated')
                                                {{ $applicant->user->university ?? $applicant->university_graduated ?? $applicant->university ?? '—' }}
                                                @break
                                            @case('entry')
                                                {{ $applicant->entry ?? $applicant->application_entry ?? '—' }}
                                                @break
                                            @case('level')
                                                {{ $applicant->level ?? '—' }}
                                                @break
                                            @case('intended_degree')
                                                {{ $applicant->intended_degree ?? $applicant->intended_masters_or_doctoral ?? '—' }}
                                                @break
                                            @case('university')
                                                {{ $applicant->university ?? '—' }}
                                                @break
                                            @case('thesis_title')
                                                {{ $applicant->thesis_title ?? $applicant->dissertation_title ?? '—' }}
                                                @break
                                            @case('units_already')
                                                {{ $applicant->units_already ?? $applicant->number_of_units_already ?? '—' }}
                                                @break
                                            @case('percent_load_completed')
                                                {{ $applicant->percent_load_completed ?? $applicant->percent_required_load ?? '—' }}
                                                @break
                                            @case('duration')
                                                {{ $applicant->duration ?? '—' }}
                                                @break
                                            @case('remarks')
                                                {{ $applicant->remarks ?? '—' }}
                                                @break
                                            @case('status')
                                                @php
                                                    $recordStatus = $applicant->status ?? $applicant->application_status ?? '';
                                                    $statusClass = match($recordStatus) {
                                                        '', null => 'bg-gray-200 text-gray-600 italic',
                                                        'qualifiers', 'gs_on_track' => 'bg-green-200 text-green-900',
                                                        'not_availing' => 'bg-gray-300 text-gray-800',
                                                        'deferred', 'pending', 'leave_of_absence', 'on_ext_complete_fa' => 'bg-yellow-200 text-yellow-900',
                                                        'graduated_on_time', 'graduated_ext', 'on_ext_with_fa', 'on_ext_for_monitoring', 'for_interview' => 'bg-blue-200 text-blue-900',
                                                        'document_verification' => 'bg-purple-200 text-purple-900',
                                                        'non_compliance', 'terminated', 'withdrawn', 'rejected', 'no_report', 'suspended' => 'bg-red-200 text-red-900',
                                                        'approved', 'good_standing' => 'bg-green-200 text-green-900',
                                                        default => 'bg-gray-100 text-gray-800',
                                                    };
                                                @endphp
                                                <span class="px-2 py-1 rounded text-xs font-semibold capitalize {{ $statusClass }}">
                                                    {{ $recordStatus ? str_replace('_', ' ', $recordStatus) : '—' }}
                                                </span>
                                                @break
                                            @default
                                                {{ $applicant->{$key} ?? '—' }}
                                        @endswitch
                                    </td>
                                @endforeach
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ count($columns) + 1 }}" class="text-center p-4 text-gray-500">
                                    No applicants available.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="mt-4">
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 text-sm">
                📄 Download Selected Records
            </button>
        </div>
    </div>
</form>

<!-- Toggle All Checkboxes & Column Toggle Script -->
<script>
    function toggleAll(source) {
        const checkboxes = document.querySelectorAll('input[name="selected[]"]');
        checkboxes.forEach(box => box.checked = source.checked);
    }

    document.querySelectorAll('.column-toggle').forEach(checkbox => {
        checkbox.addEventListener('change', () => {
            const columnClass = 'column-' + checkbox.dataset.column;
            document.querySelectorAll('.' + columnClass).forEach(cell => {
                cell.style.display = checkbox.checked ? '' : 'none';
            });
        });
    });
</script>
@endsection