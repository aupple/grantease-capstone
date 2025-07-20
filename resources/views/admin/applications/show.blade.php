@extends('layouts.admin-layout')

@section('content')
<div class="mb-6 relative">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-semibold text-gray-800 leading-tight">
                Application #{{ $application->application_form_id }}
            </h2>
            <p class="text-sm text-gray-500">Submitted on {{ $application->created_at->format('F d, Y') }}</p>
        </div>

        <div id="actionButtons" class="hidden flex gap-2">
            <form action="{{ route('admin.applications.update-status', $application->application_form_id) }}" method="POST">
                @csrf
                <input type="hidden" name="status" value="approved">
                <button type="submit" class="bg-green-600 text-white text-sm px-4 py-1.5 rounded-md hover:bg-green-700 transition font-semibold">Approve</button>
            </form>
            <form action="{{ route('admin.applications.update-status', $application->application_form_id) }}" method="POST">
                @csrf
                <input type="hidden" name="status" value="rejected">
                <button type="submit" class="bg-red-600 text-white text-sm px-4 py-1.5 rounded-md hover:bg-red-700 transition font-semibold">Reject</button>
            </form>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- LEFT SIDE -->
    <div x-data="{ sectionIndex: 0 }" class="col-span-2 space-y-6">

        <!-- 👤 Personal Information -->
        <div x-show="sectionIndex === 0" class="transition-all duration-300 bg-white/30 backdrop-blur-md border border-white/20 shadow-md rounded-2xl p-6">
            <h3 class="text-lg font-bold mb-4">👤 Personal Information</h3>
            <p><strong>Full Name:</strong> {{ $application->user->full_name ?? $application->user->first_name . ' ' . $application->user->last_name }}</p>
            <p><strong>Email:</strong> {{ $application->user->email }}</p>
            <p><strong>Phone:</strong> {{ $application->phone_number ?? 'N/A' }}</p>
            <p><strong>Sex:</strong> {{ $application->sex ?? 'N/A' }}</p>
            <p><strong>Birthdate:</strong> {{ $application->birthdate ?? 'N/A' }}</p>
            <p><strong>Age:</strong> {{ $application->age ?? 'N/A' }}</p>
            <p><strong>Civil Status:</strong> {{ $application->civil_status ?? 'N/A' }}</p>
            <p><strong>Permanent Address:</strong> {{ $application->permanent_address ?? 'N/A' }}</p>
            <p><strong>Current Address:</strong> {{ $application->current_address ?? 'N/A' }}</p>
            <p><strong>Region:</strong> {{ $application->region ?? 'N/A' }}</p>
            <p><strong>District:</strong> {{ $application->district ?? 'N/A' }}</p>
            <p><strong>Zip Code:</strong> {{ $application->zip_code ?? 'N/A' }}</p>
            <p><strong>Passport No.:</strong> {{ $application->passport_no ?? 'N/A' }}</p>
            <p><strong>Father's Name:</strong> {{ $application->father_name ?? 'N/A' }}</p>
            <p><strong>Mother's Name:</strong> {{ $application->mother_name ?? 'N/A' }}</p>
        </div>

        <!-- 🎓 Academic Background -->
        <div x-show="sectionIndex === 1" class="transition-all duration-300 bg-white/30 backdrop-blur-md border border-white/20 shadow-md rounded-2xl p-6">
            <h3 class="text-lg font-bold mb-4">🎓 Academic Background</h3>
            <p><strong>Program Applied:</strong> {{ $application->program }}</p>
            <p><strong>School:</strong> {{ $application->school }}</p>
            <p><strong>Year Level:</strong> {{ $application->year_level }}</p>
            <p><strong>Reason for Applying:</strong> {{ $application->reason ?? 'N/A' }}</p>
            <hr class="my-3 border-dashed">
            <p><strong>BS Field:</strong> {{ $application->bs_field ?? 'N/A' }}</p>
            <p><strong>BS School:</strong> {{ $application->bs_school ?? 'N/A' }}</p>
            <p><strong>BS Scholarship:</strong> {{ $application->bs_scholarship ?? 'N/A' }}</p>
            <p><strong>BS Remarks:</strong> {{ $application->bs_remarks ?? 'N/A' }}</p>
            <p><strong>MS Field:</strong> {{ $application->ms_field ?? 'N/A' }}</p>
            <p><strong>MS School:</strong> {{ $application->ms_school ?? 'N/A' }}</p>
            <p><strong>MS Scholarship:</strong> {{ $application->ms_scholarship ?? 'N/A' }}</p>
            <p><strong>MS Remarks:</strong> {{ $application->ms_remarks ?? 'N/A' }}</p>
            <p><strong>PhD Field:</strong> {{ $application->phd_field ?? 'N/A' }}</p>
            <p><strong>PhD School:</strong> {{ $application->phd_school ?? 'N/A' }}</p>
            <p><strong>PhD Scholarship:</strong> {{ $application->phd_scholarship ?? 'N/A' }}</p>
            <p><strong>PhD Remarks:</strong> {{ $application->phd_remarks ?? 'N/A' }}</p>
            <p><strong>Strand Category:</strong> {{ $application->strand_category ?? 'N/A' }}</p>
            <p><strong>Strand Type:</strong> {{ $application->strand_type ?? 'N/A' }}</p>
            <p><strong>Scholarship Type:</strong> {{ $application->scholarship_type ?? 'N/A' }}</p>
            <p><strong>New University:</strong> {{ $application->new_university ?? 'N/A' }}</p>
            <p><strong>New Course:</strong> {{ $application->new_course ?? 'N/A' }}</p>
            <p><strong>Lateral University:</strong> {{ $application->lateral_university ?? 'N/A' }}</p>
            <p><strong>Lateral Course:</strong> {{ $application->lateral_course ?? 'N/A' }}</p>
            <p><strong>Units Earned:</strong> {{ $application->units_earned ?? 'N/A' }}</p>
            <p><strong>Units Remaining:</strong> {{ $application->units_remaining ?? 'N/A' }}</p>
            <p><strong>Research Title:</strong> {{ $application->research_title ?? 'N/A' }}</p>
            <p><strong>Research Approved:</strong> {{ $application->research_approved ?? 'N/A' }}</p>
            <p><strong>Last Thesis Date:</strong> {{ $application->last_thesis_date ?? 'N/A' }}</p>
        </div>

        <!-- 💼 Employment -->
        <div x-show="sectionIndex === 2" class="transition-all duration-300 bg-white/30 backdrop-blur-md border border-white/20 shadow-md rounded-2xl p-6">
            <h3 class="text-lg font-bold mb-4">💼 Employment / Business</h3>
            <p><strong>Employment Status:</strong> {{ $application->employment_status ?? 'N/A' }}</p>
            <p><strong>Position:</strong> {{ $application->position ?? 'N/A' }}</p>
            <p><strong>Length of Service:</strong> {{ $application->length_of_service ?? 'N/A' }}</p>
            <p><strong>Company Name:</strong> {{ $application->company_name ?? 'N/A' }}</p>
            <p><strong>Company Address:</strong> {{ $application->company_address ?? 'N/A' }}</p>
            <p><strong>Company Email:</strong> {{ $application->company_email ?? 'N/A' }}</p>
            <p><strong>Company Website:</strong> {{ $application->company_website ?? 'N/A' }}</p>
            <p><strong>Company Phone:</strong> {{ $application->company_phone ?? 'N/A' }}</p>
            <p><strong>Company Fax:</strong> {{ $application->company_fax ?? 'N/A' }}</p>
            <hr class="my-3 border-dashed">
            <p><strong>Business Name:</strong> {{ $application->business_name ?? 'N/A' }}</p>
            <p><strong>Business Address:</strong> {{ $application->business_address ?? 'N/A' }}</p>
            <p><strong>Business Email:</strong> {{ $application->business_email ?? 'N/A' }}</p>
            <p><strong>Business Type:</strong> {{ $application->business_type ?? 'N/A' }}</p>
            <p><strong>Years of Operation:</strong> {{ $application->years_operation ?? 'N/A' }}</p>
        </div>

        <!-- 📈 Future Plans -->
        <div x-show="sectionIndex === 3" class="transition-all duration-300 bg-white/30 backdrop-blur-md border border-white/20 shadow-md rounded-2xl p-6">
            <h3 class="text-lg font-bold mb-4">📈 Future Plans & Accomplishments</h3>
            <p><strong>Career Plans:</strong> {{ $application->career_plans ?? 'N/A' }}</p>
            <p><strong>Research Plans:</strong> {{ $application->research_plans ?? 'N/A' }}</p>
            <p><strong>R&D Involvement:</strong> {{ $application->rnd_involvement ?? 'N/A' }}</p>
            <p><strong>Publications:</strong> {{ $application->publications ?? 'N/A' }}</p>
            <p><strong>Awards:</strong> {{ $application->awards ?? 'N/A' }}</p>
        </div>

        <!-- Navigation -->
        <div class="flex justify-between items-center px-6">
            <button @click="sectionIndex = Math.max(sectionIndex - 1, 0)"
                class="px-4 py-2 bg-gray-400 border border-gray-300 text-sm font-semibold rounded hover:bg-gray-300 transition"
                :disabled="sectionIndex === 0">← Back</button>
            <div class="text-sm font-semibold text-gray-600">
                Step <span x-text="sectionIndex + 1"></span> of 4
            </div>
            <button @click="sectionIndex = Math.min(sectionIndex + 1, 3)"
                class="px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded hover:bg-blue-400 transition"
                :disabled="sectionIndex === 3">Next →</button>
        </div>

    </div>

    <!-- RIGHT SIDE -->
    <div class="col-span-1 space-y-6">

        <!-- 📑 Documents -->
        <div class="bg-white/30 backdrop-blur-md border border-white/20 shadow-md rounded-2xl p-6">
            <h3 class="text-lg font-bold mb-4">Documents</h3>
            @php
                $evaluationFile = $application->evaluation_file_path ?? null;
                $scoresheetFile = $application->scoresheet_file_path ?? null;
            @endphp

            @foreach (['Evaluation Sheet' => $evaluationFile, 'Scoresheet' => $scoresheetFile] as $label => $file)
                <div class="mb-4">
                    <div class="flex justify-between items-center">
                        <p class="text-sm font-medium">{{ $label }}</p>
                        <div class="flex items-center gap-3">
                            @if ($file)
                                <a href="{{ asset($file) }}" target="_blank" class="text-blue-600 hover:underline text-sm">View</a>
                                <label class="inline-flex items-center text-sm cursor-pointer">
                                    <input type="checkbox" class="peer hidden checkbox-tracker">
                                    <div class="w-2.5 h-2.5 rounded-full border border-gray-400 peer-checked:bg-green-500 peer-checked:border-green-500 transition"></div>
                                    <span class="ml-2 text-xs text-gray-600 peer-checked:text-green-600 font-semibold">Verified</span>
                                </label>
                            @else
                                <span class="text-sm text-gray-400">No file submitted</span>
                                <label class="inline-flex items-center text-sm opacity-50 cursor-not-allowed">
                                    <input type="checkbox" disabled class="hidden">
                                    <div class="w-2.5 h-2.5 rounded-full border border-gray-300 bg-gray-200"></div>
                                    <span class="ml-2 text-xs text-gray-400 font-semibold">No file</span>
                                </label>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Application Info -->
        <div class="bg-white/30 backdrop-blur-md border border-white/20 shadow-md rounded-2xl p-6">
            <h3 class="text-lg font-bold mb-3">Application Info</h3>
            <div class="mb-3 flex items-center gap-3">
                <strong class="text-sm">Status:</strong>
                <span class="px-3 py-1 rounded-full text-sm font-bold
                    @if ($application->status === 'approved') bg-green-100 text-green-800
                    @elseif ($application->status === 'rejected') bg-red-100 text-red-800
                    @elseif ($application->status === 'pending') bg-yellow-100 text-yellow-800
                    @elseif ($application->status === 'document_verification') bg-purple-100 text-purple-800
                    @elseif ($application->status === 'for_interview') bg-blue-100 text-blue-800
                    @else bg-gray-100 text-gray-800
                    @endif">
                    {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                </span>
            </div>

            <form action="{{ route('admin.applications.update-status', $application->application_form_id) }}" method="POST" class="flex items-center gap-2">
                @csrf
                <strong class="text-sm">Remarks:</strong>
                <input type="text" name="remarks" class="text-xs border px-3 py-1 rounded w-64" placeholder="Type your message here..." value="{{ $application->remarks }}">
                <button type="submit" class="text-xs text-white bg-blue-500 hover:bg-blue-600 px-3 py-1 rounded transition">Send</button>
            </form>
        </div>

        <!-- ✅ Quick Actions -->
        <div class="bg-white/30 backdrop-blur-md border border-white/20 shadow-md rounded-2xl p-6">
            <h3 class="text-lg font-bold mb-4">Quick Actions</h3>
            <div class="space-y-2">
                <a href="#" class="block w-full text-center bg-gray-50 border border-gray-200 text-sm text-gray-800 rounded-md px-4 py-2 hover:bg-gray-100 transition">📄 Print Application</a>
                <a href="#" class="block w-full text-center bg-gray-50 border border-gray-200 text-sm text-gray-800 rounded-md px-4 py-2 hover:bg-gray-100 transition">📥 Download Documents</a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkboxes = document.querySelectorAll('.checkbox-tracker');
        const actionButtons = document.getElementById('actionButtons');

        function toggleActionButtons() {
            let allChecked = true;
            checkboxes.forEach(cb => {
                if (!cb.checked) {
                    allChecked = false;
                }
            });
            actionButtons.classList.toggle('hidden', !allChecked);
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', toggleActionButtons);
        });
    });
</script>
@endpush
