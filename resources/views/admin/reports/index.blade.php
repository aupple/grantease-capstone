    @extends('layouts.admin-layout')

    @section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold mb-6">Reports & Monitoring</h1>

       <!-- Report Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    @php
        $reportCards = [
            [
                'title' => 'List of All Applicants',
                'desc' => 'Shows all applicants and their current status.',
                'route' => 'admin.reports.applicants'
            ],
            [
                'title' => 'Monitoring of All Scholars',
                'desc' => 'Track scholars, their statuses, and compliance.',
                'route' => 'admin.reports.monitoring'
            ]
        ];
    @endphp

    @foreach ($reportCards as $card)
        <div class="bg-white/20 backdrop-blur-md border border-white/20 shadow-lg rounded-2xl p-6 transition hover:shadow-xl">
            <h2 class="text-lg font-semibold mb-2 text-gray-800">{{ $card['title'] }}</h2>
            <p class="text-sm text-gray-700 mb-3">{{ $card['desc'] }}</p>
            <a href="{{ route($card['route']) }}"
               class="inline-block bg-blue-900 text-white text-sm px-4 py-2 rounded-md shadow-md hover:bg-blue-700/90 transition font-semibold">
                Open
            </a>
        </div>
    @endforeach
</div>


    <!-- FILTER DROPDOWN FORM -->
    <form action="{{ route('admin.reports.index') }}" method="GET" class="mb-4">
        <label class="block mb-1 text-sm font-medium text-gray-700">Filter by Type:</label>
       <select name="type" class="w-full max-w-xl bg-white/30 backdrop-blur-sm border border-white/20 shadow p-2 rounded" onchange="this.form.submit()">
            <option value="applicant" {{ (request('type', $type ?? '') === 'applicant') ? 'selected' : '' }}>Applicants</option>
            <option value="scholar" {{ (request('type', $type ?? '') === 'scholar') ? 'selected' : '' }}>Scholars</option>
        </select>
    </form>

    <!-- EXPORT SELECTED RECORDS FORM: Wrap all table and inputs INSIDE this form -->
    <form action="{{ route('admin.reports.export-selected') }}" method="POST">
        @csrf
        <input type="hidden" name="type" value="{{ $type }}">

        <!-- SCROLLABLE TABLE -->
    <div class="border border-white/20 rounded-2xl bg-white/20 backdrop-blur-md shadow-lg p-4 w-full overflow-hidden">
     <div class="overflow-x-auto bg-white/30 backdrop-blur-sm border border-white/10 shadow rounded-2xl max-w-full">


                <table class="table-auto w-full text-sm text-left" id="export-table">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-2 border">
                                <label class="flex items-center space-x-2">
                                    <input type="checkbox" onclick="toggleAll(this)">
                                    <span>Select</span>
                                </label>
                            </th>

                            @php
                                $columns = [
                                    'last_name' => 'Last Name',
                                    'email' => 'Email',
                                    'status' => 'Status',
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
                                    'birthday' => 'Birthday',
                                    'contact_number' => 'Contact No.',
                                    'gender' => 'Gender'
                                ];
                            @endphp

                            @foreach ($columns as $key => $label)
                                <th class="p-2 border">
                                    <label class="flex items-center space-x-1">
                                        <input type="checkbox" class="column-toggle" data-column="{{ $key }}"
                                            {{ in_array($key, ['last_name', 'email', 'status','first_name','middle_name']) ? 'checked' : '' }}>
                                        <span>{{ $label }}</span>
                                    </label>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($records as $record)
                            <tr class="hover:bg-gray-50">
                                <td class="p-2 border">
                                    <input type="checkbox" name="selected[]" value="{{ $record->id }}">
                                </td>

                                @foreach ($columns as $key => $label)
                                    <td class="p-2 border column-{{ $key }}"
                                        style="{{ in_array($key, ['last_name', 'email', 'status','first_name','middle_name']) ? '' : 'display:none;' }}">
                                        @switch($key)
                                            @case('last_name')
                                                {{ $record->user->last_name ?? '—' }}
                                                @break
                                            @case('first_name')
                                                {{ $record->user->first_name ?? '—' }}
                                                @break
                                            @case('middle_name')
                                                {{ $record->user->middle_name ?? '—' }}
                                                @break
                                            @case('suffix')
                                                {{ $record->user->suffix ?? '—' }}
                                                @break
                                            @case('street')
                                                {{ $record->user->street ?? '—' }}
                                                @break
                                            @case('village')
                                                {{ $record->user->village ?? '—' }}
                                                @break
                                            @case('town')
                                                {{ $record->user->town ?? '—' }}
                                                @break
                                            @case('province')
                                                {{ $record->user->province ?? '—' }}
                                                @break
                                            @case('zipcode')
                                                {{ $record->user->zipcode ?? '—' }}
                                                @break
                                            @case('district')
                                                {{ $record->user->district ?? '—' }}
                                                @break
                                            @case('region')
                                                {{ $record->user->region ?? '—' }}
                                                @break
                                            @case('email')
                                                {{ $record->user->email ?? '—' }}
                                                @break
                                            @case('birthday')
                                                {{ $record->user->birthday ?? '—' }}
                                                @break
                                            @case('contact_number')
                                                {{ $record->user->contact_number ?? '—' }}
                                                @break
                                            @case('gender')
                                                {{ $record->user->gender ?? '—' }}
                                                @break
     @case('status')
@php
    $recordStatus = $type === 'scholar' ? ($record->status ?? '') : ($record->status ?? '');
    $statusClass = match($recordStatus) {
    '', null => 'bg-gray-200 text-gray-600 italic', // 👈 catches missing statuses
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
<span class="px-2 py-1 rounded text-xs font-semibold capitalize {{ $statusClass }} backdrop-blur-sm bg-white/30 border border-white/20">
    {{ str_replace('_', ' ', $recordStatus) }}
</span>
@break

                                        @endswitch
                                    </td>
                                @endforeach
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ count($columns) + 1 }}" class="text-center p-4 text-gray-500">
                                    No {{ $type }} records available.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

         <!-- Submit Button -->
    <div class="mt-4">
        <button type="submit"
    class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 text-sm transition shadow-md font-semibold flex items-center gap-2">
    📄 <span>Download Selected Records</span>
</button>

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
    </div>
    @endsection
