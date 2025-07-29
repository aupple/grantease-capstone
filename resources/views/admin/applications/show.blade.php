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

        <!-- Personal Information -->
        <div x-show="sectionIndex === 0" class="transition-all duration-300 bg-white/30 backdrop-blur-md border border-white/20 shadow-md rounded-2xl p-6">
            <div class="flex items-center gap-2 mb-6">
                <svg class="w-6 h-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A10.97 10.97 0 0112 15c2.45 0 4.712.755 6.559 2.028M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <h3 class="text-xl font-bold  text-[#1e33a3]">Personal Information</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 text-sm text-gray-800">
            
                <div>
                    <p class="font-semibold text-gray-600">Full Name</p>
                    <p class="font-semibold">{{ $application->user->full_name ?? $application->user->first_name . ' ' . $application->user->last_name }}</p>
                </div>
               <div>
                   <p class="font-semibold text-gray-600">Email</p>
                   <p class="font-semibold">{{ $application->user->email }}</p>
               </div>
               <div>
                   <p class="font-semibold text-gray-600">Phone</p>
                   <p class="font-semibold">{{ $application->phone_number ?? 'N/A' }}</p>
               </div>
               <div>
                   <p class="font-semibold text-gray-600">Sex</p>
                   <p class="font-semibold">{{ $application->sex ?? 'N/A' }}</p>
               </div>
               <div>
                   <p class="font-semibold text-gray-600">Birthdate</p>
                   <p class="font-semibold">{{ $application->birthdate ?? 'N/A' }}</p>
               </div>
               <div>
                   <p class="font-semibold text-gray-600">Age</p>
                   <p class="font-semibold">{{ $application->age ?? 'N/A' }}</p>
               </div>
               <div>
                   <p class="font-semibold text-gray-600">Civil Status</p>
                   <p class="font-semibold">{{ $application->civil_status ?? 'N/A' }}</p>
               </div>
               <div class="md:col-span-2">
                   <p class="font-semibold text-gray-600">Permanent Address</p>
                   <p class="font-semibold">{{ $application->permanent_address ?? 'N/A' }}</p>
               </div>
               <div class="md:col-span-2">
                   <p class="font-semibold text-gray-600">Current Address</p>
                   <p class="font-semibold">{{ $application->current_address ?? 'N/A' }}</p>
               </div>
               <div>
                   <p class="font-semibold text-gray-600">Region</p>
                   <p class="font-semibold">{{ $application->region ?? 'N/A' }}</p>
               </div>
               <div>
                   <p class="font-semibold text-gray-600">District</p>
                   <p class="font-semibold">{{ $application->district ?? 'N/A' }}</p>
               </div>
               <div>
                   <p class="font-semibold text-gray-600ium">Zip Code</p>
                   <p class="font-semibold">{{ $application->zip_code ?? 'N/A' }}</p>
               </div>
               <div>
                   <p class="font-semibold text-gray-600">Passport No.</p>
                   <p class="font-semibold">{{ $application->passport_no ?? 'N/A' }}</p>
               </div>
               <div>
                   <p class="font-semibold text-gray-600">Father's Name</p>
                   <p class="font-semibold">{{ $application->father_name ?? 'N/A' }}</p>
               </div>
               <div>
                   <p class="font-semibold text-gray-600">Mother's Name</p>
                   <p class="font-semibold">{{ $application->mother_name ?? 'N/A' }}</p>
               </div>

            </div>
        </div>
 

        <!-- Academic Background -->
        <div x-show="sectionIndex === 1" class="transition-all duration-300 bg-white/30 backdrop-blur-md border border-white/20 shadow-md rounded-2xl p-6">
            <div class="flex items-center gap-2 mb-6">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 14L21 9l-9-5-9 5 9 5zm0 0v6m0-6L3 9m18 0v6" />
                </svg>
                <h3 class="text-xl font-bold text-[#1e33a3]">Academic Background</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 text-sm text-gray-800">
            
                <div>
                    <p class="font-semibold text-gray-600">Program Applied</p>
                    <p class="font-semibold">{{ $application->program }}</p>
                </div>
                <div>
                    <p class="font-semibold text-gray-600">School</p>
                    <p class="font-semibold">{{ $application->school }}</p>
                </div>
                <div>
                    <p class="font-semibold text-gray-600">Year Level</p>
                    <p class="font-semibold">{{ $application->year_level }}</p>
                </div>
                <div class="md:col-span-2">
                    <p class="font-semibold text-gray-600">Reason for Applying</p>
                    <p class="font-semibold">{{ $application->reason ?? 'N/A' }}</p>
                </div>
            </div>

            <hr class="my-4 border-dashed">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 text-sm text-gray-800">
                @foreach ([
                    'BS Field' => 'bs_field',
                    'BS School' => 'bs_school',
                    'BS Scholarship' => 'bs_scholarship',
                    'BS Remarks' => 'bs_remarks',
                    'MS Field' => 'ms_field',
                    'MS School' => 'ms_school',
                    'MS Scholarship' => 'ms_scholarship',
                    'MS Remarks' => 'ms_remarks',
                    'PhD Field' => 'phd_field',
                    'PhD School' => 'phd_school',
                    'PhD Scholarship' => 'phd_scholarship',
                    'PhD Remarks' => 'phd_remarks',
                    'Strand Category' => 'strand_category',
                    'Strand Type' => 'strand_type',
                    'Scholarship Type' => 'scholarship_type',
                    'New University' => 'new_university',
                    'New Course' => 'new_course',
                    'Lateral University' => 'lateral_university',
                    'Lateral Course' => 'lateral_course',
                    'Units Earned' => 'units_earned',
                    'Units Remaining' => 'units_remaining',
                    'Research Title' => 'research_title',
                    'Research Approved' => 'research_approved',
                    'Last Thesis Date' => 'last_thesis_date'
                ] as $label => $field)
                    <div>
                        <p class="font-semibold text-gray-600">{{ $label }}</p>
                        <p class="font-semibold">{{ $application->$field ?? 'N/A' }}</p>
                    </div>
                @endforeach
            </div>
        </div>


        <!-- Employment -->
        <div x-show="sectionIndex === 2" class="transition-all duration-300 bg-white/30 backdrop-blur-md border border-white/20 shadow-md rounded-2xl p-6">
            <div class="flex items-center gap-2 mb-6">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 7V6a2 2 0 012-2h2a2 2 0 012 2v1h4V6a2 2 0 012-2h2a2 2 0 012 2v1m0 0v10a2 2 0 01-2 2H6a2 2 0 01-2-2V7h16z" />
                </svg>
                <h3 class="text-xl font-bold text-[#1e33a3]">Employment</h3>
            </div>      
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 text-sm text-gray-800">

                <div>
                    <p class="font-semibold text-gray-600">Employment Status</p>
                    <p class="font-semibold">{{ $application->employment_status ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="font-semibold text-gray-600">Position</p>
                    <p class="font-semibold">{{ $application->position ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="font-semibold text-gray-600">Length of Service</p>
                    <p class="font-semibold">{{ $application->length_of_service ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="font-semibold text-gray-600">Company Name</p>
                    <p class="font-semibold">{{ $application->company_name ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="font-semibold text-gray-600">Company Address</p>
                    <p class="font-semibold">{{ $application->company_address ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="font-semibold text-gray-600">Company Email</p>
                    <p class="font-semibold">{{ $application->company_email ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="font-semibold text-gray-600">Company Website</p>
                    <p class="font-semibold">{{ $application->company_website ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="font-semibold text-gray-600">Company Phone</p>
                    <p class="font-semibold">{{ $application->company_phone ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="font-semibold text-gray-600">Company Fax</p>
                    <p class="font-semibold">{{ $application->company_fax ?? 'N/A' }}</p>
                </div>

                <hr class="my-3 border-dashed">

                <div>
                    <p class="font-semibold text-gray-600">Business Name</p>
                    <p class="font-semibold">{{ $application->business_name ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="font-semibold text-gray-600">Business Address</p>
                    <p class="font-semibold">{{ $application->business_address ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="font-semibold text-gray-600">Business Email</p>
                    <p class="font-semibold">{{ $application->business_email ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="font-semibold text-gray-600">Business Type</p>
                    <p class="font-semibold">{{ $application->business_type ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="font-semibold text-gray-600">Years of Operation</p>
                    <p class="font-semibold">{{ $application->years_operation ?? 'N/A' }}</p>
                </div>
            </div> 
        </div>

        <!-- Future Plans -->
        <div x-show="sectionIndex === 3" class="transition-all duration-300 bg-white/30 backdrop-blur-md border border-white/20 shadow-md rounded-2xl p-6">
            <div class="flex items-center gap-2 mb-6">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 17l6-6 4 4 8-8" />
                </svg>
                <h3 class="text-xl font-bold text-[#1e33a3]">Future Plans</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 text-sm text-gray-800">

                <div>
                    <p class="font-semibold text-gray-600">Career Plans</p>
                    <p class="font-semibold">{{ $application->career_plans ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="font-semibold text-gray-600">Research Plans</p>
                    <p class="font-semibold">{{ $application->research_plans ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="font-semibold text-gray-600">R&D Involvement</p>
                    <p class="font-semibold">{{ $application->rnd_involvement ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="font-semibold text-gray-600">Publications</p>
                    <p class="font-semibold">{{ $application->publications ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="font-semibold text-gray-600">Awards</p>
                    <p class="font-semibold">{{ $application->awards ?? 'N/A' }}</p>
                </div>
            </div>
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
