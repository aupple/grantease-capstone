<x-app-layout :headerTitle="'Application Status'">
    @php
        $user = auth()->user();
    @endphp

    <style>
        body {
            background-color: #f9fafb;
        }

        .print-area {
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            padding: 20mm;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.04);
        }

        @media print {
            body * {
                visibility: hidden !important;
            }

            nav,
            header,
            footer,
            aside,
            .print:hidden {
                display: none !important;
            }

            @page {
                size: A4;
                margin: 10mm;
            }
        }

        /* ===== FORM STYLING ===== */
        .editable-field {
            min-height: 20px;
            /* Changed from 24px */
            padding: 2px 4px;
            /* Even smaller padding */
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            background: #fff;
            font-size: 13px;
            white-space: normal;
            overflow-wrap: break-word;
            word-break: break-word;
            overflow: hidden;
        }

        .editable-field[contenteditable="false"] {
            background: #f3f4f6;
        }

        .section-title {
            color: #1e40af;
            font-weight: 700;
            font-size: 15px;
            margin-bottom: 6px;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 6px;
        }

        .section-box {
            border: 1px solid #cbd5e1;
            padding: 10px;
            border-radius: 6px;
            background: #fff;
        }

        .photo-box {
            border: 1px solid #d1d5db;
            padding: 6px;
            font-size: 12px;
            text-align: center;
            width: 120px;
            height: 160px;
            background: #fff;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: center;
        }

        .photo-preview {
            width: 100px;
            height: 120px;
            border: 1px solid #e6e9ee;
            background: #f8fafc;
            margin-top: 6px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #9ca3af;
            font-size: 11px;
        }

        .header-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            gap: 10px;
        }

        .logo {
            width: 90px;
            height: 90px;
            object-fit: contain;
            border: 1px solid #d1d5db;
            padding: 4px;
            background: #fff;
        }

        @keyframes slide-in {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slide-out {
            from {
                transform: translateX(0);
                opacity: 1;
            }

            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }

        .animate-slide-in {
            animation: slide-in 0.3s ease-out;
        }

        .animate-slide-out {
            animation: slide-out 0.3s ease-out;
        }
    </style>
    @php
        function getLocationName($locations, $code)
        {
            return collect($locations)->firstWhere('code', $code)['name'] ?? ($code ?? '—');
        }
    @endphp
    <div class="py-6 px-4 sm:px-6 lg:px-8 bg-gray-50 min-h-screen">
        <!-- Success Toast Notification -->
        @if (session('success'))
            <div id="successToast"
                class="fixed top-4 right-4 z-50 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg flex items-center gap-3 animate-slide-in">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span class="font-semibold">{{ session('success') }}</span>
                <button onclick="closeToast()" class="ml-4 text-white hover:text-gray-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
        @endif
        <div class="print-area">
            @if ($applications->isEmpty())
                <p class="text-gray-600">You haven't submitted any applications yet.</p>
            @else
                @foreach ($applications as $application)
                    <!-- ===== APPLICATION STATUS (TOP) ===== -->
                    <h2 class="section-title mt-4">
                        <span>Application Status</span>
                    </h2>

                    <div class="p-1.5 mb-6 flex items-center gap-4">
                        <div>
                            <strong>Status:</strong>
                            <span
                                class="inline-block px-2 py-1 rounded text-xs
            {{ $application->status == 'approved'
                ? 'bg-green-100 text-green-800'
                : ($application->status == 'rejected'
                    ? 'bg-red-100 text-red-800'
                    : 'bg-yellow-100 text-yellow-800') }}">
                                {{ ucfirst(str_replace('_', ' ', $application->status ?? 'pending')) }}
                            </span>
                        </div>
                        <div>
                            <strong>Submitted At:</strong>
                            <span
                                class="text-sm">{{ $application->submitted_at ?? ($application->created_at ?? '—') }}</span>
                        </div>

                        <!-- Edit Button (only if pending) -->
                        @if (($application->status ?? 'pending') === 'pending' && !empty($application->id))
                            <div class="ml-auto">
                                <a href="{{ route('applicant.application.edit', ['id' => $application->id]) }}"
                                    class="inline-block bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded text-sm">
                                    Edit Application
                                </a>
                            </div>
                        @endif
                    </div>


                    <!-- HEADER -->
                    <div class="header-row">
                        <div class="logo-box">
                            <img src="{{ asset('images/DOST.png') }}" alt="DOST Logo" class="logo">
                        </div>

                        <div class="title text-center">
                            <p class="text-sm font-semibold">DEPARTMENT OF SCIENCE AND TECHNOLOGY</p>
                            <p class="text-sm font-semibold">SCIENCE EDUCATION INSTITUTE</p>
                            <p class="text-xs">Lapasan, Cagayan De Oro City</p>
                            <h1 class="text-base font-bold underline mt-1">APPLICATION FORM</h1>
                            <p class="text-xs mt-1">for the</p>
                            <h2 class="text-sm font-bold mt-1">
                                SCIENCE AND TECHNOLOGY REGIONAL ALLIANCE OF UNIVERSITIES<br>
                                FOR NATIONAL DEVELOPMENT (STRAND)
                            </h2>
                        </div>

                        <div class="photo-box relative">
                            <p class="font-semibold mb-1 text-[12px]">Attached Photo</p>
                            @if ($application->passport_picture)
                                <img src="{{ asset('storage/' . $application->passport_picture) }}"
                                    class="photo-preview object-cover">
                            @else
                                <div class="photo-preview">No Photo</div>
                            @endif
                        </div>
                    </div>

                    <!-- APPLICATION DETAILS -->
                    <div class="flex gap-4 text-sm mb-4">
                        <div>
                            <label class="block text-[12px] font-semibold">Application No.</label>
                            <div class="editable-field bg-gray-100 text-center inline-block px-6 py-2">
                                {{ $application->application_no }}
                            </div>
                        </div>
                        <div>
                            <label class="block text-[12px] font-semibold">Academic Year</label>
                            <div class="editable-field bg-gray-100 text-center inline-block px-6 py-2">
                                {{ $application->academic_year }}</div>
                        </div>
                        <div>
                            <label class="block text-[12px] font-semibold">School Term</label>
                            <div class="editable-field bg-gray-100 text-center inline-block px-6 py-2">
                                {{ $application->school_term }}</div>
                        </div>
                    </div>

                    <!-- I. PERSONAL INFORMATION -->
                    <h2 class="section-title">I. PERSONAL INFORMATION</h2>
                    <div class="section-box text-[13px] text-gray-800 leading-snug">

                        <!-- Name -->
                        <div class="grid grid-cols-4 gap-2 p-1.5">
                            <div>
                                <label class="block text-[12px] font-semibold">Last Name</label>
                                <div class="editable-field text-[13px]">{{ $application->last_name ?? '—' }}</div>
                            </div>
                            <div>
                                <label class="block text-[12px] font-semibold">First Name</label>
                                <div class="editable-field text-[13px]">{{ $application->first_name ?? '—' }}</div>
                            </div>
                            <div>
                                <label class="block text-[12px] font-semibold">Middle Name</label>
                                <div class="editable-field text-[13px]">{{ $application->middle_name ?? '—' }}</div>
                            </div>
                            <div>
                                <label class="block text-[12px] font-semibold">Suffix</label>
                                <div class="editable-field text-[13px]">{{ $application->suffix ?? 'N/A' }}</div>
                            </div>
                        </div>

                        <!-- Permanent Address -->
                        <div class="grid grid-cols-6 gap-2 p-1.5 w-full">
                            <div class="col-span-2 min-w-0">
                                <label class="block text-[12px] font-semibold">Permanent Address (No.)</label>
                                <div class="editable-field text-[13px]">{{ $application->address_no ?? '—' }}</div>
                            </div>
                            <div class="min-w-0">
                                <label class="block text-[12px] font-semibold">Street</label>
                                <div class="editable-field text-[13px]">{{ $application->address_street ?? '—' }}</div>
                            </div>
                            <div class="min-w-0">
                                <label class="block text-[12px] font-semibold">Barangay</label>
                                <div class="editable-field text-[13px]">
                                    {{ getLocationName($barangays, $application->barangay) }}</div>
                            </div>
                            <div class="min-w-0">
                                <label class="block text-[10px] font-semibold">City / Municipality</label>
                                <div
                                    class="editable-field whitespace-nowrap overflow-hidden
        @if (strlen(getLocationName($cities, $application->city)) > 20) text-[9px]
        @elseif(strlen(getLocationName($cities, $application->city)) > 15)
            text-[10px]
        @else
            text-[13px] @endif">
                                    {{ getLocationName($cities, $application->city) }}
                                </div>
                            </div>
                            <div class="min-w-0">
                                <label class="block text-[12px] font-semibold">Province</label>
                                <div class="editable-field text-[13px]">
                                    {{ getLocationName($provinces, $application->province) }}</div>
                            </div>
                        </div>

                        <!-- ZIP / Region / District / Passport / Email -->
                        <div class="grid grid-cols-6 gap-2 p-1.5">
                            <div>
                                <label class="block text-[12px] font-semibold">ZIP Code</label>
                                <div class="editable-field text-[13px]">{{ $application->zip_code ?? '—' }}</div>
                            </div>
                            <div>
                                <label class="block text-[12px] font-semibold">Region</label>
                                <div class="editable-field text-[13px]">{{ $application->region ?? '—' }}</div>
                            </div>
                            <div>
                                <label class="block text-[12px] font-semibold">District</label>
                                <div class="editable-field text-[13px]">{{ $application->district ?? '—' }}</div>
                            </div>
                            <div>
                                <label class="block text-[12px] font-semibold">Passport No.</label>
                                <div class="editable-field text-[13px]">{{ $application->passport_no ?? '—' }}</div>
                            </div>
                            <div class="col-span-2">
                                <label class="block text-[12px] font-semibold">E-mail Address</label>
                                <div class="editable-field text-[13px]">{{ $application->email_address ?? '—' }}</div>
                            </div>
                        </div>

                        <!-- Current Mailing Address -->
                        <div class="p-1.5">
                            <label class="block text-[12px] font-semibold">Current Mailing Address</label>
                            <div class="editable-field text-[13px]">{{ $application->current_mailing_address ?? '—' }}
                            </div>
                        </div>

                        <!-- Telephone / Alternate Contact -->
                        <div class="grid grid-cols-2 gap-2 p-1.5">
                            <div>
                                <label class="block text-[12px] font-semibold">Telephone Nos. (Landline/Mobile)</label>
                                <div class="editable-field text-[13px]">{{ $application->telephone_nos ?? '—' }}</div>
                            </div>
                            <div>
                                <label class="block text-[12px] font-semibold">Alternate Contact No.</label>
                                <div class="editable-field text-[13px]">{{ $application->alternate_contact ?? '—' }}
                                </div>
                            </div>
                        </div>

                        <!-- Civil Status / DOB / Age / Sex -->
                        <div class="grid grid-cols-4 gap-2 p-1.5">
                            <div>
                                <label class="block text-[12px] font-semibold">Civil Status</label>
                                <div class="editable-field text-[13px]">{{ $application->civil_status ?? '—' }}</div>
                            </div>
                            <div>
                                <label class="block text-[12px] font-semibold">Date of Birth</label>
                                <div class="editable-field text-[13px]">{{ $application->date_of_birth ?? '—' }}</div>
                            </div>
                            <div>
                                <label class="block text-[12px] font-semibold">Age</label>
                                <div class="editable-field text-[13px]">{{ $application->age ?? '—' }}</div>
                            </div>
                            <div>
                                <label class="block text-[12px] font-semibold">Sex</label>
                                <div class="editable-field text-[13px]">{{ $application->sex ?? '—' }}</div>
                            </div>
                        </div>

                        <!-- Parents -->
                        <div class="grid grid-cols-2 gap-2 p-1.5">
                            <div>
                                <label class="block text-[12px] font-semibold">Father's Name</label>
                                <div class="editable-field text-[13px]">{{ $application->father_name ?? '—' }}</div>
                            </div>
                            <div>
                                <label class="block text-[12px] font-semibold">Mother's Name</label>
                                <div class="editable-field text-[13px]">{{ $application->mother_name ?? '—' }}</div>
                            </div>
                        </div>
                    </div>


                    <!-- II. EDUCATIONAL BACKGROUND -->
                    <h2 class="section-title mt-4 text-sm">II. EDUCATIONAL BACKGROUND</h2>

                    <table class="w-full border border-gray-400 text-[12px] text-gray-800 table-fixed mb-2">
                        <thead class="bg-gray-100 text-xs">
                            <tr class="text-center">
                                <th class="border border-gray-400 w-[6%] py-1">Level</th>
                                <th class="border border-gray-400 w-[14%] py-1">Period</th>
                                <th class="border border-gray-400 w-[16%] py-1">Field</th>
                                <th class="border border-gray-400 w-[20%] py-1">University / School</th>
                                <th class="border border-gray-400 w-[30%] py-1">Scholarship</th>
                                <th class="border border-gray-400 w-[14%] py-1">Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (['BS' => 'bs', 'MS' => 'ms', 'PHD' => 'phd'] as $label => $levelKey)
                                <tr class="align-top text-xs">
                                    <td class="border border-gray-400 font-semibold text-center py-1">
                                        {{ $label }}</td>
                                    <td class="border border-gray-400 px-1">
                                        <div class="editable-field min-h-[20px] text-xs" contenteditable="false">
                                            {{ $application->{$levelKey . '_period'} ?? '' }}</div>
                                    </td>
                                    <td class="border border-gray-400 px-1">
                                        <div class="editable-field min-h-[20px] text-xs" contenteditable="false">
                                            {{ $application->{$levelKey . '_field'} ?? '' }}</div>
                                    </td>
                                    <td class="border border-gray-400 px-1">
                                        <div class="editable-field min-h-[20px] text-xs" contenteditable="false">
                                            {{ $application->{$levelKey . '_university'} ?? '' }}</div>
                                    </td>
                                    <td class="border border-gray-400 px-1 py-1">
                                        <div class="grid grid-cols-2 text-[11px] gap-x-1">
                                            @php $selectedScholarships = $application->{$levelKey.'_scholarship_type'} ?? []; @endphp
                                            @if ($label === 'BS')
                                                @foreach (['PSHS', 'RA 7687', 'MERIT', 'RA 10612'] as $s)
                                                    <label><input type="checkbox" disabled
                                                            {{ in_array($s, $selectedScholarships) ? 'checked' : '' }}
                                                            class="mr-1">{{ $s }}</label>
                                                @endforeach
                                            @elseif($label === 'MS')
                                                @foreach (['NSDB/NSTA', 'ASTHRDP', 'ERDT', 'COUNCIL/SEI'] as $s)
                                                    <label><input type="checkbox" disabled
                                                            {{ in_array($s, $selectedScholarships) ? 'checked' : '' }}
                                                            class="mr-1">{{ $s }}</label>
                                                @endforeach
                                            @elseif($label === 'PHD')
                                                @foreach (['NSDB/NSTA', 'ASTHRDP', 'ERDT', 'COUNCIL/SEI'] as $s)
                                                    <label><input type="checkbox" disabled
                                                            {{ in_array($s, $selectedScholarships) ? 'checked' : '' }}
                                                            class="mr-1">{{ $s }}</label>
                                                @endforeach
                                            @endif
                                        </div>
                                        <div class="text-[11px] mt-1 flex items-center gap-1">
                                            <span>OTHERS:</span>
                                            <div class="editable-field border-0 border-b border-gray-500 bg-transparent min-h-[16px] px-1 text-xs"
                                                contenteditable="false">
                                                {{ $application->{$levelKey . '_scholarship_others'} ?? '' }}</div>
                                        </div>
                                    </td>
                                    <td class="border border-gray-400 px-1">
                                        <div class="editable-field min-h-[20px] text-xs" contenteditable="false">
                                            {{ $application->{$levelKey . '_remarks'} ?? '' }}</div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>


                    <h2 class="section-title mt-4 text-sm">III. GRADUATE SCHOLARSHIP INTENTIONS DATA</h2>

                    <div class="border border-gray-400 rounded text-[12px] text-gray-800 section-box p-1">

                        <!-- Strand / Type / Scholarship table -->
                        @php
                            // Decode JSON strings to arrays
                            $strand_categories = $application->strand_category ?? [];
                            if (is_string($strand_categories)) {
                                $strand_categories = json_decode($strand_categories, true) ?? [];
                            }

                            $applicant_types = $application->applicant_type ?? [];
                            if (is_string($applicant_types)) {
                                $applicant_types = json_decode($applicant_types, true) ?? [];
                            }

                            $scholarship_types = $application->scholarship_type ?? [];
                            if (is_string($scholarship_types)) {
                                $scholarship_types = json_decode($scholarship_types, true) ?? [];
                            }

                            $research_approved = $application->research_topic_approved ? 'YES' : 'NO';
                        @endphp

                        <table class="w-full text-[12px] border-t border-b border-gray-400 text-center mb-2">
                            <thead class="bg-gray-100 font-semibold text-xs">
                                <tr>
                                    <th class="border-r border-gray-400 py-1">STRAND CATEGORY</th>
                                    <th class="border-r border-gray-400 py-1">TYPE OF APPLICANT</th>
                                    <th class="py-1">TYPE OF SCHOLARSHIP APPLIED FOR</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="border-r border-gray-400 align-top px-1 py-1 text-left">
                                        @foreach (['STRAND 1', 'STRAND 2'] as $s)
                                            <label><input type="checkbox" disabled
                                                    {{ in_array($s, $strand_categories) ? 'checked' : '' }}
                                                    class="mr-1 text-xs">{{ $s }}</label><br>
                                        @endforeach
                                    </td>
                                    <td class="border-r border-gray-400 align-top px-1 py-1 text-left">
                                        @foreach (['Student', 'Faculty'] as $a)
                                            <label><input type="checkbox" disabled
                                                    {{ in_array($a, $applicant_types) ? 'checked' : '' }}
                                                    class="mr-1 text-xs">{{ $a }}</label><br>
                                        @endforeach
                                    </td>
                                    <td class="align-top px-1 py-1 text-left">
                                        @foreach (['MS', 'PhD'] as $sch)
                                            <label><input type="checkbox" disabled
                                                    {{ in_array($sch, $scholarship_types) ? 'checked' : '' }}
                                                    class="mr-1 text-xs">{{ $sch }}</label><br>
                                        @endforeach
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <!-- New Applicant -->
                        <div class="px-1 py-1 border-t border-gray-400">
                            <p class="font-semibold text-xs">New Applicant</p>
                            <div class="mt-1 space-y-1 text-[12px]">
                                <div class="flex items-center gap-1">
                                    <span><strong>a.</strong> University:</span>
                                    <div class="editable-field flex-1 border-0 border-b border-gray-400 bg-transparent min-h-[18px] text-xs"
                                        contenteditable="false">{{ $application->new_applicant_university ?? '' }}
                                    </div>
                                </div>
                                <div class="flex items-center gap-1">
                                    <span><strong>b.</strong> Course/Degree:</span>
                                    <div class="editable-field flex-1 border-0 border-b border-gray-400 bg-transparent min-h-[18px] text-xs"
                                        contenteditable="false">{{ $application->new_applicant_course ?? '' }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Lateral Applicant -->
                        <div class="px-1 py-1 border-t border-gray-400">
                            <p class="font-semibold text-xs">Lateral Applicant</p>
                            <div class="mt-1 space-y-1 text-[12px]">
                                <div class="flex items-center gap-1">
                                    <span><strong>a.</strong> University enrolled in:</span>
                                    <div class="editable-field flex-1 border-0 border-b border-gray-400 bg-transparent min-h-[18px]"
                                        contenteditable="false">{{ $application->lateral_university_enrolled ?? '' }}
                                    </div>
                                </div>
                                <div class="flex items-center gap-1">
                                    <span><strong>b.</strong> Course/Degree:</span>
                                    <div class="editable-field flex-1 border-0 border-b border-gray-400 bg-transparent min-h-[18px]"
                                        contenteditable="false">{{ $application->lateral_course_degree ?? '' }}</div>
                                </div>
                                <div class="grid grid-cols-2 gap-1">
                                    <div class="flex items-center gap-1">
                                        <span><strong>c.</strong> Units earned:</span>
                                        <div class="editable-field flex-1 border-0 border-b border-gray-400 bg-transparent min-h-[18px]"
                                            contenteditable="false">{{ $application->lateral_units_earned ?? '' }}
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <span><strong>d.</strong> Remaining units:</span>
                                        <div class="editable-field flex-1 border-0 border-b border-gray-400 bg-transparent min-h-[18px]"
                                            contenteditable="false">{{ $application->lateral_remaining_units ?? '' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Research -->
                        <div class="px-1 py-1 border-t border-gray-400 text-[12px]">
                            <div class="flex items-center gap-1">
                                <span><strong>e.</strong> Research topic approved?</span>
                                <label><input type="checkbox" disabled
                                        {{ $research_approved == 'YES' ? 'checked' : '' }}
                                        class="ml-1 mr-1 text-xs">YES</label>
                                <label><input type="checkbox" disabled
                                        {{ $research_approved == 'NO' ? 'checked' : '' }}
                                        class="ml-1 mr-1 text-xs">NO</label>
                            </div>
                            <div class="mt-1">
                                <span>Title:</span>
                                <div class="editable-field border-0 border-b border-gray-400 bg-transparent w-[90%] min-h-[18px]"
                                    contenteditable="false">{{ $application->research_title ?? '' }}</div>
                            </div>
                            <div class="mt-1">
                                <span>Date of last enrollment:</span>
                                <div class="editable-field border-0 border-b border-gray-400 bg-transparent w-[60%] min-h-[18px]"
                                    contenteditable="false">{{ $application->research_date ?? '' }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- IV. CAREER/EMPLOYMENT INFORMATION -->
                    <h2 class="section-title">IV. CAREER/EMPLOYMENT INFORMATION</h2>

                    <div class="border border-gray-400 text-[13px] text-gray-800 leading-tight section-box">

                        <!-- a. Employment Status -->
                        <div class="px-3 py-2">
                            <p>a. Present Employment Status</p>
                            <div class="p-1 border rounded mt-1 text-[13px]">
                                {{ isset($application->employment_status) && is_array($application->employment_status) ? implode(', ', $application->employment_status) : '—' }}
                            </div>
                        </div>

                        <!-- a.1 Presently employed -->
                        <div class="px-3 py-2">
                            <p class="font-semibold">a.1 For those who are presently employed*</p>
                            <div class="grid grid-cols-12 gap-x-2 gap-y-1 mt-1">
                                <div class="col-span-2">Position</div>
                                <div class="col-span-4 border p-1 rounded">
                                    {{ $application->employed_position ?? '—' }}</div>

                                <div class="col-span-3 text-right pr-1">Length of Service</div>
                                <div class="col-span-3 border p-1 rounded">
                                    {{ $application->employed_length_of_service ?? '—' }}</div>

                                <div class="col-span-3 mt-1">Name of Company/Office</div>
                                <div class="col-span-9 mt-1 border p-1 rounded">
                                    {{ $application->employed_company_name ?? '—' }}</div>
                                <div class="col-span-3 mt-1">Address of Company/Office</div>
                                <div class="col-span-9 mt-1 border p-1 rounded">
                                    {{ $application->employed_company_address ?? '—' }}</div>

                                <div class="col-span-1 mt-1">Email</div>
                                <div class="col-span-5 mt-1 border p-1 rounded">
                                    {{ $application->employed_email ?? '—' }}
                                </div>

                                <div class="col-span-1 mt-1">Website</div>
                                <div class="col-span-5 mt-1 border p-1 rounded">
                                    {{ $application->employed_website ?? '—' }}
                                </div>

                                <div class="col-span-2 mt-1">Telephone No.</div>
                                <div class="col-span-4 mt-1 border p-1 rounded">
                                    {{ $application->employed_telephone ?? '—' }}
                                </div>

                                <div class="col-span-1 mt-1">Fax No.</div>
                                <div class="col-span-5 mt-1 border p-1 rounded">
                                    {{ $application->employed_fax ?? '—' }}</div>
                            </div>
                        </div>

                        <!-- a.2 Self-employed -->
                        <div class="px-3 py-2">
                            <p class="font-semibold">a.2 For those who are self-employed</p>
                            <div class="grid grid-cols-12 gap-x-2 gap-y-1 mt-1">
                                <div class="col-span-2">Business Name</div>
                                <div class="col-span-10 border p-1 rounded">
                                    {{ $application->self_employed_business_name ?? '—' }}</div>

                                <div class="col-span-2 mt-1">Address</div>
                                <div class="col-span-10 mt-1 border p-1 rounded">
                                    {{ $application->self_employed_address ?? '—' }}</div>

                                <div class="col-span-2 mt-1">Email/Website</div>
                                <div class="col-span-3 mt-1 border p-1 rounded">
                                    {{ $application->self_employed_email_website ?? '—' }}</div>

                                <div class="col-span-2 mt-1">Telephone No.</div>
                                <div class="col-span-3 mt-1 border p-1 rounded">
                                    {{ $application->self_employed_telephone ?? '—' }}</div>

                                <div class="col-span-1 mt-1">Fax No.</div>
                                <div class="col-span-1 mt-1 border p-1 rounded">
                                    {{ $application->self_employed_fax ?? '—' }}
                                </div>

                                <div class="col-span-2 mt-1">Type of Business</div>
                                <div class="col-span-4 mt-1 border p-1 rounded">
                                    {{ $application->self_employed_type_of_business ?? '—' }}</div>

                                <div class="col-span-2 mt-1">Years of Operation</div>
                                <div class="col-span-4 mt-1 border p-1 rounded">
                                    {{ $application->self_employed_years_of_operation ?? '—' }}</div>
                            </div>
                        </div>

                        <!-- Research Plans -->
                        <div class="px-3 py-2 border-t border-gray-400">
                            <p class="font-semibold">b. RESEARCH PLANS (Minimum of 300 words)</p>
                            <div class="border p-1 rounded mt-1">{{ $application->research_plans ?? '—' }}</div>
                        </div>

                        <!-- Career Plans -->
                        <div class="px-3 py-2 border-t border-gray-400">
                            <p class="font-semibold">c. CAREER PLANS (Minimum of 300 words)</p>
                            <div class="border p-1 rounded mt-1">{{ $application->career_plans ?? '—' }}</div>
                        </div>

                    </div>


                    <!-- V. RESEARCH / PUBLICATIONS / AWARDS -->
                    <h2 class="section-title mt-6">V. RESEARCH / PUBLICATIONS / AWARDS</h2>

                    <div class="section-box text-[13px] text-gray-800 leading-snug">

                        <!-- a. R&D Involvement -->
                        <div class="p-1.5 border-b border-gray-300">
                            <label class="block text-[12px] font-semibold mb-1">
                                a. Research & Development (R&D) Involvement (Last 5 Years)
                            </label>

                            <table class="w-full border border-gray-400 text-[13px] text-gray-800 mb-2">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="table-xs text-left font-semibold">Field and Title of Research</th>
                                        <th class="table-xs text-left font-semibold">Location/Duration</th>
                                        <th class="table-xs text-left font-semibold">Fund Source</th>
                                        <th class="table-xs text-left font-semibold">Nature of Involvement</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($application->research_involvements ?? [] as $research)
                                        <tr>
                                            <td class="table-xs border p-1 rounded">
                                                {{ $research['field_title'] ?? '—' }}</td>
                                            <td class="table-xs border p-1 rounded">
                                                {{ $research['location_duration'] ?? '—' }}</td>
                                            <td class="table-xs border p-1 rounded">
                                                {{ $research['fund_source'] ?? '—' }}</td>
                                            <td class="table-xs border p-1 rounded">
                                                {{ $research['nature_of_involvement'] ?? '—' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="table-xs border p-1 rounded" colspan="4">—</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- b. Publications -->
                        <div class="p-1.5 border-b border-gray-300">
                            <label class="block text-[12px] font-semibold mb-1">
                                b. Publications (Last 5 Years)
                            </label>

                            <table class="w-full border border-gray-400 text-[13px] text-gray-800 mb-2">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="table-xs text-left font-semibold">Title of Article</th>
                                        <th class="table-xs text-left font-semibold">Name/Year of Publication</th>
                                        <th class="table-xs text-left font-semibold">Nature of Involvement</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($application->publications ?? [] as $pub)
                                        <tr>
                                            <td class="table-xs border p-1 rounded">{{ $pub['title'] ?? '—' }}</td>
                                            <td class="table-xs border p-1 rounded">{{ $pub['name_year'] ?? '—' }}
                                            </td>
                                            <td class="table-xs border p-1 rounded">
                                                {{ $pub['nature_of_involvement'] ?? '—' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="table-xs border p-1 rounded" colspan="3">—</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- c. Awards -->
                        <div class="p-1.5">
                            <label class="block text-[12px] font-semibold mb-1">
                                c. Awards / Recognitions Received
                            </label>

                            <table class="w-full border border-gray-400 text-[13px] text-gray-800 mb-2">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="table-xs text-left font-semibold">Title of Award</th>
                                        <th class="table-xs text-left font-semibold">Award Giving Body</th>
                                        <th class="table-xs text-left font-semibold">Year</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($application->awards ?? [] as $award)
                                        <tr>
                                            <td class="table-xs border p-1 rounded">{{ $award['title'] ?? '—' }}</td>
                                            <td class="table-xs border p-1 rounded">{{ $award['giving_body'] ?? '—' }}
                                            </td>
                                            <td class="table-xs border p-1 rounded">{{ $award['year'] ?? '—' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="table-xs border p-1 rounded" colspan="3">—</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                    </div>

                    <!-- VI. ATTACHED DOCUMENTS -->
                    <h2 class="section-title mt-6">VI. ATTACHED DOCUMENTS</h2>

                    <div class="section-box mb-4 text-[13px] leading-snug text-gray-800">
                        <p class="mb-2 font-semibold">Uploaded Documents:</p>

                        @php
                            // Helper function to check if document exists
                            function hasDocument($doc)
                            {
                                if (empty($doc)) {
                                    return false;
                                }

                                if (is_string($doc) && (str_starts_with($doc, '[') || str_starts_with($doc, '{'))) {
                                    $decoded = json_decode($doc, true);
                                    if (is_array($decoded)) {
                                        return !empty($decoded);
                                    }
                                }

                                return !empty($doc);
                            }

                            // Helper to get file URL
                            function getFileUrl($doc)
                            {
                                if (empty($doc)) {
                                    return null;
                                }

                                if (is_string($doc) && (str_starts_with($doc, '[') || str_starts_with($doc, '{'))) {
                                    $decoded = json_decode($doc, true);
                                    if (is_array($decoded) && !empty($decoded)) {
                                        $doc = $decoded[0] ?? null;
                                    }
                                }

                                return $doc ? asset('storage/' . $doc) : null;
                            }

                            // Get remarks from application
                            $allRemarks = DB::table('remarks')
                                ->where('application_form_id', $application->application_form_id) // ✅ Fixed!
                                ->get()
                                ->keyBy('document_name');
                        @endphp

                        <!-- General Requirements -->
                        <div class="space-y-3 pl-4">
                            <!-- Birth Certificate -->
                            @php
                                $documentKey = 'Birth Certificate';
                                $hasRemark = $allRemarks->has($documentKey);
                                $remarkData = $hasRemark ? $allRemarks->get($documentKey) : null;
                            @endphp
                            <div
                                class="border rounded-lg p-3 {{ $hasRemark ? 'bg-red-50 border-red-300' : (hasDocument($application->birth_certificate_pdf) ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200') }}">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <p class="font-medium text-sm">
                                            • Birth Certificate (Photocopy)
                                        </p>
                                        <div class="flex items-center gap-2 mt-1 flex-wrap">
                                            @if (hasDocument($application->birth_certificate_pdf))
                                                <span class="text-green-700 font-semibold text-xs">✓ Uploaded</span>
                                                <a href="{{ getFileUrl($application->birth_certificate_pdf) }}"
                                                    target="_blank"
                                                    class="text-blue-600 hover:text-blue-800 text-xs font-semibold underline flex items-center gap-1">
                                                    View File
                                                </a>
                                                <button
                                                    onclick="openDocumentModal('{{ getFileUrl($application->birth_certificate_pdf) }}', 'Birth Certificate')"
                                                    class="text-purple-600 hover:text-purple-800 text-xs font-semibold underline">
                                                    Quick View
                                                </button>
                                            @else
                                                <span class="text-gray-500 text-xs">Not uploaded</span>
                                            @endif
                                        </div>

                                        <!-- Remarks Section -->
                                        @if ($hasRemark)
                                            <div class="mt-2 p-3 bg-yellow-50 border-l-4 border-yellow-400 rounded">
                                                <div class="flex items-start justify-between">
                                                    <div class="flex-1">
                                                        <p
                                                            class="font-semibold text-yellow-800 text-xs flex items-center gap-1">
                                                            ⚠️ Admin Remarks:
                                                        </p>
                                                        <p class="text-yellow-700 mt-1 text-xs">
                                                            {{ $remarkData->remark_text }}</p>
                                                        <p class="text-yellow-600 text-xs mt-1 italic">
                                                            Please update this document and resubmit.
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    @if ($application->status === 'pending' || $hasRemark)
                                        <button onclick="openEditModal('birth_certificate', 'Birth Certificate')"
                                            class="ml-2 text-xs bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded flex items-center gap-1 whitespace-nowrap">
                                            {{ hasDocument($application->birth_certificate_pdf) ? 'Edit File' : 'Upload File' }}
                                        </button>
                                    @endif
                                </div>
                            </div>

                            <!-- Transcript of Record -->
                            @php
                                $documentKey = 'Transcript of Record';
                                $hasRemark = $allRemarks->has($documentKey);
                                $remarkData = $hasRemark ? $allRemarks->get($documentKey) : null;
                                $needsRevision = $hasRemark;
                            @endphp
                            <div
                                class="border rounded-lg p-3 {{ hasDocument($application->transcript_of_record_pdf) ? ($needsRevision ? 'bg-red-50 border-red-300' : 'bg-green-50 border-green-200') : 'bg-gray-50 border-gray-200' }}">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <p class="font-medium text-sm flex items-center gap-2">
                                            • Certified True Copy of the Official Transcript of Record
                                        </p>
                                        <div class="flex items-center gap-2 mt-1 flex-wrap">
                                            @if (hasDocument($application->transcript_of_record_pdf))
                                                <span class="text-green-700 font-semibold text-xs">✓ Uploaded</span>
                                                <a href="{{ getFileUrl($application->transcript_of_record_pdf) }}"
                                                    target="_blank"
                                                    class="text-blue-600 hover:text-blue-800 text-xs font-semibold underline">
                                                    View File
                                                </a>
                                                <button
                                                    onclick="openDocumentModal('{{ getFileUrl($application->transcript_of_record_pdf) }}', 'Transcript of Record')"
                                                    class="text-purple-600 hover:text-purple-800 text-xs font-semibold underline">
                                                    Quick View
                                                </button>
                                            @else
                                                <span class="text-gray-500 text-xs">Not uploaded</span>
                                            @endif
                                        </div>

                                        <!-- Remarks Section -->
                                        @if ($hasRemark)
                                            <div class="mt-2 p-3 bg-yellow-50 border-l-4 border-yellow-400 rounded">
                                                <div class="flex items-start justify-between">
                                                    <div class="flex-1">
                                                        <p
                                                            class="font-semibold text-yellow-800 text-xs flex items-center gap-1">
                                                            ⚠️ Admin Remarks:
                                                        </p>
                                                        <p class="text-yellow-700 mt-1 text-xs">
                                                            {{ $remarkData->remark_text }}</p>
                                                        <p class="text-yellow-600 text-xs mt-1 italic">
                                                            Please update this document and resubmit.
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    @if (($application->status === 'pending' || $needsRevision) && hasDocument($application->transcript_of_record_pdf))
                                        <button onclick="openEditModal('transcript_of_record', 'Transcript of Record')"
                                            class="ml-2 text-xs bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded flex items-center gap-1 whitespace-nowrap">
                                            Edit File
                                        </button>
                                    @endif
                                </div>
                            </div>

                            <!-- Endorsement 1 -->
                            @php
                                $documentKey = 'Endorsement Letter 1';
                                $hasRemark = $allRemarks->has($documentKey);
                                $remarkData = $hasRemark ? $allRemarks->get($documentKey) : null;
                                $needsRevision = $hasRemark;
                            @endphp
                            <div
                                class="border rounded-lg p-3 {{ hasDocument($application->endorsement_1_pdf) ? ($needsRevision ? 'bg-red-50 border-red-300' : 'bg-green-50 border-green-200') : 'bg-gray-50 border-gray-200' }}">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <p class="font-medium text-sm flex items-center gap-2">
                                            • Endorsement 1 – Former professor in college for MS / former professor in
                                            MS program for PhD
                                        </p>
                                        <div class="flex items-center gap-2 mt-1 flex-wrap">
                                            @if (hasDocument($application->endorsement_1_pdf))
                                                <span class="text-green-700 font-semibold text-xs">✓ Uploaded</span>
                                                <a href="{{ getFileUrl($application->endorsement_1_pdf) }}"
                                                    target="_blank"
                                                    class="text-blue-600 hover:text-blue-800 text-xs font-semibold underline">
                                                    View File
                                                </a>
                                                <button
                                                    onclick="openDocumentModal('{{ getFileUrl($application->endorsement_1_pdf) }}', 'Endorsement 1')"
                                                    class="text-purple-600 hover:text-purple-800 text-xs font-semibold underline">
                                                    Quick View
                                                </button>
                                            @else
                                                <span class="text-gray-500 text-xs">Not uploaded</span>
                                            @endif
                                        </div>

                                        @if ($hasRemark)
                                            <div class="mt-2 p-3 bg-yellow-50 border-l-4 border-yellow-400 rounded">
                                                <p
                                                    class="font-semibold text-yellow-800 text-xs flex items-center gap-1">
                                                    ⚠️ Admin Remarks:
                                                </p>
                                                <p class="text-yellow-700 mt-1 text-xs">
                                                    {{ $remarkData->remark_text }}</p>
                                                <p class="text-yellow-600 text-xs mt-1 italic">
                                                    Please update this document and resubmit.
                                                </p>
                                            </div>
                                        @endif
                                    </div>

                                    @if (($application->status === 'pending' || $needsRevision) && hasDocument($application->endorsement_1_pdf))
                                        <button onclick="openEditModal('endorsement_1', 'Endorsement 1')"
                                            class="ml-2 text-xs bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded flex items-center gap-1 whitespace-nowrap">
                                            Edit File
                                        </button>
                                    @endif
                                </div>
                            </div>

                            <!-- Endorsement 2 -->
                            @php
                                $documentKey = 'Endorsement Letter 2';
                                $hasRemark = $allRemarks->has($documentKey);
                                $remarkData = $hasRemark ? $allRemarks->get($documentKey) : null;
                                $needsRevision = $hasRemark;
                            @endphp
                            <div
                                class="border rounded-lg p-3 {{ hasDocument($application->endorsement_2_pdf) ? ($needsRevision ? 'bg-red-50 border-red-300' : 'bg-green-50 border-green-200') : 'bg-gray-50 border-gray-200' }}">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <p class="font-medium text-sm flex items-center gap-2">
                                            • Endorsement 2 – Former professor in college for MS / former professor in
                                            MS program for PhD

                                        </p>
                                        <div class="flex items-center gap-2 mt-1 flex-wrap">
                                            @if (hasDocument($application->endorsement_2_pdf))
                                                <span class="text-green-700 font-semibold text-xs">✓ Uploaded</span>
                                                <a href="{{ getFileUrl($application->endorsement_2_pdf) }}"
                                                    target="_blank"
                                                    class="text-blue-600 hover:text-blue-800 text-xs font-semibold underline">
                                                    View File
                                                </a>
                                                <button
                                                    onclick="openDocumentModal('{{ getFileUrl($application->endorsement_2_pdf) }}', 'Endorsement 2')"
                                                    class="text-purple-600 hover:text-purple-800 text-xs font-semibold underline">
                                                    Quick View
                                                </button>
                                            @else
                                                <span class="text-gray-500 text-xs">Not uploaded</span>
                                            @endif
                                        </div>

                                        @if ($hasRemark)
                                            <div class="mt-2 p-3 bg-yellow-50 border-l-4 border-yellow-400 rounded">
                                                <p
                                                    class="font-semibold text-yellow-800 text-xs flex items-center gap-1">
                                                    ⚠️ Admin Remarks:
                                                </p>
                                                <p class="text-yellow-700 mt-1 text-xs">
                                                    {{ $remarkData->remark_text }}</p>
                                                <p class="text-yellow-600 text-xs mt-1 italic">
                                                    Please update this document and resubmit.
                                                </p>
                                            </div>
                                        @endif
                                    </div>

                                    @if (($application->status === 'pending' || $needsRevision) && hasDocument($application->endorsement_2_pdf))
                                        <button onclick="openEditModal('endorsement_2', 'Endorsement 2')"
                                            class="ml-2 text-xs bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded flex items-center gap-1 whitespace-nowrap">
                                            Edit File
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- If Employed -->
                        <div class="mt-4 pl-4">
                            <p class="font-semibold mb-2">If Employed:</p>
                            <div class="space-y-3 pl-4">
                                <!-- Recommendation from Head of Agency -->
                                @php
                                    $documentKey = 'Recommendation from Head of Agency';
                                    $hasRemark = $allRemarks->has($documentKey);
                                    $remarkData = $hasRemark ? $allRemarks->get($documentKey) : null;
                                    $needsRevision = $hasRemark;
                                @endphp
                                <div
                                    class="border rounded-lg p-3 {{ hasDocument($application->recommendation_head_agency_pdf) ? ($needsRevision ? 'bg-red-50 border-red-300' : 'bg-green-50 border-green-200') : 'bg-gray-50 border-gray-200' }}">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <p class="font-medium text-sm flex items-center gap-2">
                                                • Recommendation from Head of Agency

                                            </p>
                                            <div class="flex items-center gap-2 mt-1 flex-wrap">
                                                @if (hasDocument($application->recommendation_head_agency_pdf))
                                                    <span class="text-green-700 font-semibold text-xs">✓
                                                        Uploaded</span>
                                                    <a href="{{ getFileUrl($application->recommendation_head_agency_pdf) }}"
                                                        target="_blank"
                                                        class="text-blue-600 hover:text-blue-800 text-xs font-semibold underline">
                                                        View File
                                                    </a>
                                                    <button
                                                        onclick="openDocumentModal('{{ getFileUrl($application->recommendation_head_agency_pdf) }}', 'Recommendation from Head of Agency')"
                                                        class="text-purple-600 hover:text-purple-800 text-xs font-semibold underline">
                                                        Quick View
                                                    </button>
                                                @else
                                                    <span class="text-gray-500 text-xs">Not uploaded</span>
                                                @endif
                                            </div>

                                            @if ($hasRemark)
                                                <div
                                                    class="mt-2 p-3 bg-yellow-50 border-l-4 border-yellow-400 rounded">
                                                    <p
                                                        class="font-semibold text-yellow-800 text-xs flex items-center gap-1">
                                                        ⚠️ Admin Remarks:
                                                    </p>
                                                    <p class="text-yellow-700 mt-1 text-xs">
                                                        {{ $remarkData->remark_text }}</p>
                                                    <p class="text-yellow-600 text-xs mt-1 italic">
                                                        Please update this document and resubmit.
                                                    </p>
                                                </div>
                                            @endif
                                        </div>

                                        @if (($application->status === 'pending' || $needsRevision) && hasDocument($application->recommendation_head_agency_pdf))
                                            <button
                                                onclick="openEditModal('recommendation_head_agency', 'Recommendation from Head of Agency')"
                                                class="ml-2 text-xs bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded flex items-center gap-1 whitespace-nowrap">
                                                Edit File
                                            </button>
                                        @endif
                                    </div>
                                </div>

                                <!-- Form 2B -->
                                @php
                                    $documentKey = 'Form 2B - Optional Employment Cert.'; // ✅ FIXED: Correct key
                                    $hasRemark = $allRemarks->has($documentKey);
                                    $remarkData = $hasRemark ? $allRemarks->get($documentKey) : null;
                                @endphp
                                <div
                                    class="border rounded-lg p-3 {{ $hasRemark ? 'bg-red-50 border-red-300' : (hasDocument($application->form_2b_pdf) ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200') }}">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <p class="font-medium text-sm">
                                                • Form 2B – Certificate of DepEd Employment and Permit to Study (for
                                                DepEd employees only)
                                            </p>
                                            <div class="flex items-center gap-2 mt-1 flex-wrap">
                                                @if (hasDocument($application->form_2b_pdf))
                                                    <span class="text-green-700 font-semibold text-xs">✓
                                                        Uploaded</span>
                                                    <a href="{{ getFileUrl($application->form_2b_pdf) }}"
                                                        target="_blank"
                                                        class="text-blue-600 hover:text-blue-800 text-xs font-semibold underline">
                                                        View File
                                                    </a>
                                                    <button
                                                        onclick="openDocumentModal('{{ getFileUrl($application->form_2b_pdf) }}', 'Form 2B')"
                                                        class="text-purple-600 hover:text-purple-800 text-xs font-semibold underline">
                                                        Quick View
                                                    </button>
                                                @else
                                                    <span class="text-gray-500 text-xs">Not uploaded</span>
                                                @endif
                                            </div>

                                            @if ($hasRemark)
                                                <div
                                                    class="mt-2 p-3 bg-yellow-50 border-l-4 border-yellow-400 rounded">
                                                    <p
                                                        class="font-semibold text-yellow-800 text-xs flex items-center gap-1">
                                                        ⚠️ Admin Remarks:
                                                    </p>
                                                    <p class="text-yellow-700 mt-1 text-xs">
                                                        {{ $remarkData->remark_text }}</p>
                                                    <p class="text-yellow-600 text-xs mt-1 italic">
                                                        Please update this document and resubmit.
                                                    </p>
                                                </div>
                                            @endif
                                        </div>

                                        @if ($application->status === 'pending' || $hasRemark)
                                            <button onclick="openEditModal('form_2b', 'Form 2B')"
                                                class="ml-2 text-xs bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded flex items-center gap-1 whitespace-nowrap">
                                                {{ hasDocument($application->form_2b_pdf) ? 'Edit File' : 'Upload File' }}
                                            </button>
                                        @endif
                                    </div>
                                </div>

                                <!-- Form 2B -->
                                @php
                                    $documentKey = 'Form B - Career Plans';
                                    $hasRemark = $allRemarks->has($documentKey);
                                    $remarkData = $hasRemark ? $allRemarks->get($documentKey) : null;
                                    $needsRevision = $hasRemark;
                                @endphp
                                <div
                                    class="border rounded-lg p-3 {{ hasDocument($application->form_2b_pdf) ? ($needsRevision ? 'bg-red-50 border-red-300' : 'bg-green-50 border-green-200') : 'bg-gray-50 border-gray-200' }}">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <p class="font-medium text-sm flex items-center gap-2">
                                                • Form 2B – Certificate of DepEd Employment and Permit to Study (for
                                                DepEd employees only)

                                            </p>
                                            <div class="flex items-center gap-2 mt-1 flex-wrap">
                                                @if (hasDocument($application->form_2b_pdf))
                                                    <span class="text-green-700 font-semibold text-xs">✓
                                                        Uploaded</span>
                                                    <a href="{{ getFileUrl($application->form_2b_pdf) }}"
                                                        target="_blank"
                                                        class="text-blue-600 hover:text-blue-800 text-xs font-semibold underline">
                                                        View File
                                                    </a>
                                                    <button
                                                        onclick="openDocumentModal('{{ getFileUrl($application->form_2b_pdf) }}', 'Form 2B')"
                                                        class="text-purple-600 hover:text-purple-800 text-xs font-semibold underline">
                                                        Quick View
                                                    </button>
                                                @else
                                                    <span class="text-gray-500 text-xs">Not uploaded</span>
                                                @endif
                                            </div>

                                            @if ($hasRemark)
                                                <div
                                                    class="mt-2 p-3 bg-yellow-50 border-l-4 border-yellow-400 rounded">
                                                    <p
                                                        class="font-semibold text-yellow-800 text-xs flex items-center gap-1">
                                                        ⚠️ Admin Remarks:
                                                    </p>
                                                    <p class="text-yellow-700 mt-1 text-xs">
                                                        {{ $remarkData->remark_text }}</p>
                                                    <p class="text-yellow-600 text-xs mt-1 italic">
                                                        Please update this document and resubmit.
                                                    </p>
                                                </div>
                                            @endif
                                        </div>

                                        @if (($application->status === 'pending' || $needsRevision) && hasDocument($application->form_2b_pdf))
                                            <button onclick="openEditModal('form_2b', 'Form 2B')"
                                                class="ml-2 text-xs bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded flex items-center gap-1 whitespace-nowrap">
                                                Edit File
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Other Requirements -->
                        <div class="mt-4 pl-4">
                            <p class="font-semibold mb-2">Other Requirements:</p>
                            <div class="space-y-3 pl-4">
                                <!-- Form C -->
                                @php
                                    $documentKey = 'Form C - Health Status';
                                    $hasRemark = $allRemarks->has($documentKey);
                                    $remarkData = $hasRemark ? $allRemarks->get($documentKey) : null;
                                @endphp
                                <div
                                    class="border rounded-lg p-3 {{ $hasRemark ? 'bg-red-50 border-red-300' : (hasDocument($application->form_c_health_status_pdf) ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200') }}">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <p class="font-medium text-sm">
                                                • Form C – Certification of Health Status
                                            </p>
                                            <div class="flex items-center gap-2 mt-1 flex-wrap">
                                                @if (hasDocument($application->form_c_health_status_pdf))
                                                    <span class="text-green-700 font-semibold text-xs">✓
                                                        Uploaded</span>
                                                    <a href="{{ getFileUrl($application->form_c_health_status_pdf) }}"
                                                        target="_blank"
                                                        class="text-blue-600 hover:text-blue-800 text-xs font-semibold underline">
                                                        View File
                                                    </a>
                                                    <button
                                                        onclick="openDocumentModal('{{ getFileUrl($application->form_c_health_status_pdf) }}', 'Form C - Health Status')"
                                                        class="text-purple-600 hover:text-purple-800 text-xs font-semibold underline">
                                                        Quick View
                                                    </button>
                                                @else
                                                    <span class="text-gray-500 text-xs">Not uploaded</span>
                                                @endif
                                            </div>

                                            @if ($hasRemark)
                                                <div
                                                    class="mt-2 p-3 bg-yellow-50 border-l-4 border-yellow-400 rounded">
                                                    <p
                                                        class="font-semibold text-yellow-800 text-xs flex items-center gap-1">
                                                        ⚠️ Admin Remarks:
                                                    </p>
                                                    <p class="text-yellow-700 mt-1 text-xs">
                                                        {{ $remarkData->remark_text }}</p>
                                                    <p class="text-yellow-600 text-xs mt-1 italic">
                                                        Please update this document and resubmit.
                                                    </p>
                                                </div>
                                            @endif
                                        </div>

                                        @if ($application->status === 'pending' || $hasRemark)
                                            <button
                                                onclick="openEditModal('form_c_health_status', 'Form C - Health Status')"
                                                class="ml-2 text-xs bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded flex items-center gap-1 whitespace-nowrap">
                                                {{ hasDocument($application->form_c_health_status_pdf) ? 'Edit File' : 'Upload File' }}
                                            </button>
                                        @endif
                                    </div>
                                </div>
                                <!-- NBI Clearance -->
                                @php
                                    $documentKey = 'NBI Clearance';
                                    $hasRemark = $allRemarks->has($documentKey);
                                    $remarkData = $hasRemark ? $allRemarks->get($documentKey) : null;
                                    $needsRevision = $hasRemark;
                                @endphp
                                <div
                                    class="border rounded-lg p-3 {{ hasDocument($application->nbi_clearance_pdf) ? ($needsRevision ? 'bg-red-50 border-red-300' : 'bg-green-50 border-green-200') : 'bg-gray-50 border-gray-200' }}">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <p class="font-medium text-sm flex items-center gap-2">
                                                • Valid NBI Clearance

                                            </p>
                                            <div class="flex items-center gap-2 mt-1 flex-wrap">
                                                @if (hasDocument($application->nbi_clearance_pdf))
                                                    <span class="text-green-700 font-semibold text-xs">✓
                                                        Uploaded</span>
                                                    <a href="{{ getFileUrl($application->nbi_clearance_pdf) }}"
                                                        target="_blank"
                                                        class="text-blue-600 hover:text-blue-800 text-xs font-semibold underline">
                                                        View File
                                                    </a>
                                                    <button
                                                        onclick="openDocumentModal('{{ getFileUrl($application->nbi_clearance_pdf) }}', 'NBI Clearance')"
                                                        class="text-purple-600 hover:text-purple-800 text-xs font-semibold underline">
                                                        Quick View
                                                    </button>
                                                @else
                                                    <span class="text-gray-500 text-xs">Not uploaded</span>
                                                @endif
                                            </div>

                                            @if ($hasRemark)
                                                <div
                                                    class="mt-2 p-3 bg-yellow-50 border-l-4 border-yellow-400 rounded">
                                                    <p
                                                        class="font-semibold text-yellow-800 text-xs flex items-center gap-1">
                                                        ⚠️ Admin Remarks:
                                                    </p>
                                                    <p class="text-yellow-700 mt-1 text-xs">
                                                        {{ $remarkData->remark_text }}</p>
                                                    <p class="text-yellow-600 text-xs mt-1 italic">
                                                        Please update this document and resubmit.
                                                    </p>
                                                </div>
                                            @endif
                                        </div>

                                        @if (($application->status === 'pending' || $needsRevision) && hasDocument($application->nbi_clearance_pdf))
                                            <button onclick="openEditModal('nbi_clearance', 'NBI Clearance')"
                                                class="ml-2 text-xs bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded flex items-center gap-1 whitespace-nowrap">
                                                Edit File
                                            </button>
                                        @endif
                                    </div>
                                </div>

                                <!-- Letter of Admission -->
                                @php
                                    $documentKey = 'Letter of Admission';
                                    $hasRemark = $allRemarks->has($documentKey);
                                    $remarkData = $hasRemark ? $allRemarks->get($documentKey) : null;
                                    $needsRevision = $hasRemark;
                                @endphp
                                <div
                                    class="border rounded-lg p-3 {{ hasDocument($application->letter_of_admission_pdf) ? ($needsRevision ? 'bg-red-50 border-red-300' : 'bg-green-50 border-green-200') : 'bg-gray-50 border-gray-200' }}">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <p class="font-medium text-sm flex items-center gap-2">
                                                • Letter of Admission with Regular Status (includes Evaluation Sheet)

                                            </p>
                                            <div class="flex items-center gap-2 mt-1 flex-wrap">
                                                @if (hasDocument($application->letter_of_admission_pdf))
                                                    <span class="text-green-700 font-semibold text-xs">✓
                                                        Uploaded</span>
                                                    <a href="{{ getFileUrl($application->letter_of_admission_pdf) }}"
                                                        target="_blank"
                                                        class="text-blue-600 hover:text-blue-800 text-xs font-semibold underline">
                                                        View File
                                                    </a>
                                                    <button
                                                        onclick="openDocumentModal('{{ getFileUrl($application->letter_of_admission_pdf) }}', 'Letter of Admission')"
                                                        class="text-purple-600 hover:text-purple-800 text-xs font-semibold underline">
                                                        Quick View
                                                    </button>
                                                @else
                                                    <span class="text-gray-500 text-xs">Not uploaded</span>
                                                @endif
                                            </div>

                                            @if ($hasRemark)
                                                <div
                                                    class="mt-2 p-3 bg-yellow-50 border-l-4 border-yellow-400 rounded">
                                                    <p
                                                        class="font-semibold text-yellow-800 text-xs flex items-center gap-1">
                                                        ⚠️ Admin Remarks:
                                                    </p>
                                                    <p class="text-yellow-700 mt-1 text-xs">
                                                        {{ $remarkData->remark_text }}</p>
                                                    <p class="text-yellow-600 text-xs mt-1 italic">
                                                        Please update this document and resubmit.
                                                    </p>
                                                </div>
                                            @endif
                                        </div>

                                        @if (($application->status === 'pending' || $needsRevision) && hasDocument($application->letter_of_admission_pdf))
                                            <button
                                                onclick="openEditModal('letter_of_admission', 'Letter of Admission')"
                                                class="ml-2 text-xs bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded flex items-center gap-1 whitespace-nowrap">
                                                Edit File
                                            </button>
                                        @endif
                                    </div>
                                </div>

                                <!-- Approved Program of Study -->
                                @php
                                    $documentKey = 'Approved Program of Study';
                                    $hasRemark = $allRemarks->has($documentKey);
                                    $remarkData = $hasRemark ? $allRemarks->get($documentKey) : null;
                                    $needsRevision = $hasRemark;
                                @endphp
                                <div
                                    class="border rounded-lg p-3 {{ hasDocument($application->approved_program_of_study_pdf) ? ($needsRevision ? 'bg-red-50 border-red-300' : 'bg-green-50 border-green-200') : 'bg-gray-50 border-gray-200' }}">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <p class="font-medium text-sm flex items-center gap-2">
                                                • Approved Program of Study

                                            </p>
                                            <div class="flex items-center gap-2 mt-1 flex-wrap">
                                                @if (hasDocument($application->approved_program_of_study_pdf))
                                                    <span class="text-green-700 font-semibold text-xs">✓
                                                        Uploaded</span>
                                                    <a href="{{ getFileUrl($application->approved_program_of_study_pdf) }}"
                                                        target="_blank"
                                                        class="text-blue-600 hover:text-blue-800 text-xs font-semibold underline">
                                                        View File
                                                    </a>
                                                    <button
                                                        onclick="openDocumentModal('{{ getFileUrl($application->approved_program_of_study_pdf) }}', 'Approved Program of Study')"
                                                        class="text-purple-600 hover:text-purple-800 text-xs font-semibold underline">
                                                        Quick View
                                                    </button>
                                                @else
                                                    <span class="text-gray-500 text-xs">Not uploaded</span>
                                                @endif
                                            </div>

                                            @if ($hasRemark)
                                                <div
                                                    class="mt-2 p-3 bg-yellow-50 border-l-4 border-yellow-400 rounded">
                                                    <p
                                                        class="font-semibold text-yellow-800 text-xs flex items-center gap-1">
                                                        ⚠️ Admin Remarks:
                                                    </p>
                                                    <p class="text-yellow-700 mt-1 text-xs">
                                                        {{ $remarkData->remark_text }}</p>
                                                    <p class="text-yellow-600 text-xs mt-1 italic">
                                                        Please update this document and resubmit.
                                                    </p>
                                                </div>
                                            @endif
                                        </div>

                                        @if (($application->status === 'pending' || $needsRevision) && hasDocument($application->approved_program_of_study_pdf))
                                            <button
                                                onclick="openEditModal('approved_program_of_study', 'Approved Program of Study')"
                                                class="ml-2 text-xs bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded flex items-center gap-1 whitespace-nowrap">
                                                Edit File
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Lateral Applicants -->
                        <div class="mt-4 pl-4">
                            <p class="font-semibold mb-2">Additional Requirements for Lateral Applicants:</p>
                            <div class="space-y-3 pl-4">
                                @php
                                    $documentKey = 'Lateral Certification';
                                    $hasRemark = $allRemarks->has($documentKey);
                                    $remarkData = $hasRemark ? $allRemarks->get($documentKey) : null;
                                    $needsRevision = $hasRemark;
                                @endphp
                                <div
                                    class="border rounded-lg p-3 {{ hasDocument($application->lateral_certification_pdf) ? ($needsRevision ? 'bg-red-50 border-red-300' : 'bg-green-50 border-green-200') : 'bg-gray-50 border-gray-200' }}">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <p class="font-medium text-sm flex items-center gap-2">
                                                • Certification from the university indicating:

                                            </p>
                                            <ul class="list-disc pl-6 text-xs mt-1 mb-2">
                                                <li>Number of graduate units required in the program</li>
                                                <li>Number of graduate units already earned with corresponding grades
                                                </li>
                                            </ul>
                                            <div class="flex items-center gap-2">
                                                @if (hasDocument($application->lateral_certification_pdf))
                                                    <span class="text-green-700 font-semibold text-xs">✓
                                                        Uploaded</span>
                                                    <a href="{{ getFileUrl($application->lateral_certification_pdf) }}"
                                                        target="_blank"
                                                        class="text-blue-600 hover:text-blue-800 text-xs font-semibold underline">
                                                        View File
                                                    </a>
                                                    <button
                                                        onclick="openDocumentModal('{{ getFileUrl($application->lateral_certification_pdf) }}', 'Lateral Certification')"
                                                        class="text-purple-600 hover:text-purple-800 text-xs font-semibold underline">
                                                        Quick View
                                                    </button>
                                                @else
                                                    <span class="text-gray-500 text-xs">Not uploaded</span>
                                                @endif
                                            </div>

                                            @if ($hasRemark)
                                                <div
                                                    class="mt-2 p-3 bg-yellow-50 border-l-4 border-yellow-400 rounded">
                                                    <p
                                                        class="font-semibold text-yellow-800 text-xs flex items-center gap-1">
                                                        ⚠️ Admin Remarks:
                                                    </p>
                                                    <p class="text-yellow-700 mt-1 text-xs">
                                                        {{ $remarkData->remark_text }}</p>
                                                    <p class="text-yellow-600 text-xs mt-1 italic">
                                                        Please update this document and resubmit.
                                                    </p>
                                                </div>
                                            @endif
                                        </div>

                                        @if (($application->status === 'pending' || $needsRevision) && hasDocument($application->lateral_certification_pdf))
                                            <button
                                                onclick="openEditModal('lateral_certification', 'Lateral Certification')"
                                                class="ml-2 text-xs bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded flex items-center gap-1 whitespace-nowrap">
                                                Replace
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END OF ATTACHED DOCUMENTS SECTION -->

                    <!-- Document Quick View Modal -->
                    <div id="documentModal"
                        class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
                        <div class="bg-white rounded-lg max-w-4xl w-full max-h-[90vh] flex flex-col">
                            <div class="flex items-center justify-between p-4 border-b">
                                <h3 id="modalTitle" class="text-lg font-semibold">Document Viewer</h3>
                                <button onclick="closeDocumentModal()" class="text-gray-500 hover:text-gray-700">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                            <div class="flex-1 overflow-auto p-4">
                                <iframe id="documentFrame" class="w-full h-full min-h-[500px]"
                                    frameborder="0"></iframe>
                            </div>
                            <div class="p-4 border-t flex justify-end gap-2">
                                <a id="downloadLink" href="#" target="_blank"
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded text-sm">
                                    Download
                                </a>
                                <button onclick="closeDocumentModal()"
                                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded text-sm">
                                    Close
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Edit Document Modal -->
                    <div id="editModal"
                        class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
                        <div class="bg-white rounded-lg max-w-md w-full">
                            <div class="flex items-center justify-between p-4 border-b">
                                <h3 id="editModalTitle" class="text-lg font-semibold">Replace Document</h3>
                                <button onclick="closeEditModal()" class="text-gray-500 hover:text-gray-700">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                            <form id="editDocumentForm" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="p-4">
                                    <input type="hidden" id="documentType" name="document_type">
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Upload New Document (PDF only)
                                        </label>
                                        <input type="file" name="document" id="documentFile" accept=".pdf"
                                            required class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                                        <p class="text-xs text-gray-500 mt-1">Maximum file size: 5MB</p>
                                    </div>
                                </div>
                                <div class="p-4 border-t flex justify-end gap-2">
                                    <button type="button" onclick="closeEditModal()"
                                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded text-sm">
                                        Cancel
                                    </button>
                                    <button type="submit"
                                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded text-sm">
                                        Upload
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- VIII. DECLARATION -->
                    <h2 class="section-title mt-6">VIII. TRUTHFULNESS OF DATA AND DATA PRIVACY</h2>
                    <div class="section-box mb-4 text-[13px] leading-snug">
                        <p class="mb-2 text-justify">
                            I hereby certify that all information given above are true and correct to the best of my
                            knowledge. Any misinformation or withholding of information will automatically disqualify me
                            from the program, Project Science and Technology Regional Alliance of Universities for
                            National Development (STRAND). I am willing to refund all the financial benefits received
                            plus appropriate interest if such misinformation is discovered.
                        </p>

                        <p class="mb-3 text-justify">
                            Moreover, I hereby authorize the Science Education Institute of the Department of Science
                            and Technology (SEI-DOST) to collect, record, organize, update or modify, retrieve,
                            consult, use, consolidate, block, erase or destruct my personal data that I have provided
                            in relation to my application to this scholarship. I hereby affirm my right to be informed,
                            object to processing, access and rectify, suspend or withdraw my personal data, and be
                            indemnified in case of damages pursuant to the provisions of the Republic Act No. 10173 of
                            the Philippines, Data Privacy Act of 2012 and its corresponding Implementing Rules and
                            Regulations.
                        </p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                            <!-- Applicant Name -->
                            <div>
                                <label class="block text-[12px] font-semibold mb-1">Applicant Name</label>
                                <div class="p-2 border rounded bg-gray-50">
                                    {{ trim(($user->first_name ?? '') . ' ' . ($user->middle_name ?? '') . ' ' . ($user->last_name ?? '')) }}
                                </div>
                            </div>
                            <!-- Terms Agreement -->
                            <div class="mt-4 flex items-start gap-2">
                                <input type="checkbox" class="h-4 w-4 border-gray-400 mt-0.5" disabled
                                    {{ $application->terms_and_conditions_agreed ? 'checked' : '' }}>
                                <span class="text-[12px]">I agree to the Terms, Conditions, and Data Privacy
                                    Policy.</span>
                            </div>
                        </div>
                @endforeach
            @endif
        </div>
    </div>
    <script>
        function openDocumentModal(fileUrl, title) {
            document.getElementById('modalTitle').textContent = title;
            document.getElementById('documentFrame').src = fileUrl;
            document.getElementById('downloadLink').href = fileUrl;
            document.getElementById('documentModal').classList.remove('hidden');
            // Prevent body scroll when modal is open
            document.body.style.overflow = 'hidden';
        }

        function closeDocumentModal() {
            document.getElementById('documentModal').classList.add('hidden');
            document.getElementById('documentFrame').src = '';
            // Restore body scroll
            document.body.style.overflow = '';
        }

        function openEditModal(documentType, title) {
            document.getElementById('editModalTitle').textContent = 'Replace ' + title;
            document.getElementById('documentType').value = documentType;
            document.getElementById('editDocumentForm').action = `/applicant/application/update-document/${documentType}`;
            document.getElementById('editModal').classList.remove('hidden');
            // Prevent body scroll when modal is open
            document.body.style.overflow = 'hidden';
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
            document.getElementById('editDocumentForm').reset();
            // Restore body scroll
            document.body.style.overflow = '';
        }

        // Close toast notification
        function closeToast() {
            const toast = document.getElementById('successToast');
            if (toast) {
                toast.classList.remove('animate-slide-in');
                toast.classList.add('animate-slide-out');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }
        }

        // Auto-hide toast after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const toast = document.getElementById('successToast');
            if (toast) {
                setTimeout(() => {
                    closeToast();
                }, 3000);
            }
        });

        // Close modals when clicking outside
        document.getElementById('documentModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeDocumentModal();
            }
        });

        document.getElementById('editModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditModal();
            }
        });

        // Close modals with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDocumentModal();
                closeEditModal();
            }
        });

        // File input validation
        document.getElementById('documentFile')?.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Check file type
                if (file.type !== 'application/pdf') {
                    alert('Please upload a PDF file only.');
                    this.value = '';
                    return;
                }

                // Check file size (5MB = 5 * 1024 * 1024 bytes)
                const maxSize = 5 * 1024 * 1024;
                if (file.size > maxSize) {
                    alert('File size must be less than 5MB. Your file is ' + (file.size / (1024 * 1024)).toFixed(
                        2) + 'MB');
                    this.value = '';
                    return;
                }
            }
        });

        // Form submission handling
        document.getElementById('editDocumentForm')?.addEventListener('submit', function(e) {
            const fileInput = document.getElementById('documentFile');
            if (!fileInput.files || fileInput.files.length === 0) {
                e.preventDefault();
                alert('Please select a file to upload.');
                return false;
            }

            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML =
                '<svg class="animate-spin h-5 w-5 mr-2 inline" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Uploading...';
        });
    </script>
</x-app-layout>
