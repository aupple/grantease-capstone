<x-app-layout :headerTitle="'Application Status'">
    @php
        $user = auth()->user();
    @endphp

    <style>
        /* ===== PAGE & PRINT ===== */
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

            .print-area,
            .print-area * {
                visibility: visible !important;
            }

            .print-area {
                position: absolute;
                left: 0;
                top: 0;
                width: 210mm;
                padding: 15mm;
                background: white;
                border: none;
                box-shadow: none;
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
            min-height: 24px;
            padding: 6px 8px;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            background: #fff;
            font-size: 13px;
            white-space: pre-wrap;
            overflow-wrap: break-word;
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
    </style>

    <div class="py-6 px-4 sm:px-6 lg:px-8 bg-gray-50 min-h-screen">
        <div class="print-area">
            @if ($applications->isEmpty())
                <p class="text-gray-600">You haven't submitted any applications yet.</p>
            @else
                <div class="space-y-6">
                    @foreach ($applications as $application)
                        <div class="bg-white rounded-lg shadow p-6 border border-gray-200">
                            <h2 class="text-xl font-semibold text-gray-800 mb-2">
                                {{ $application->program }} Scholarship
                            </h2>
                            <div class="text-sm text-gray-700 space-y-1">
                                <p><strong>School:</strong> {{ $application->school }}</p>
                                <p><strong>Year Level:</strong> {{ $application->year_level }}</p>
                                <p><strong>Reason:</strong> {{ $application->reason }}</p>
                                <p><strong>Status:</strong>
                                    <span class="inline-block bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs">
                                        {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                                    </span>
                                </p>
                                <p><strong>Remarks:</strong> {{ $application->remarks ?? 'None' }}</p>
                                <p><strong>Submitted At:</strong>
                                    {{ $application->submitted_at ?? $application->created_at }}</p>
                            </div>

                            @if ($application->status === 'pending')
                                <div class="mt-4">
                                    <a href="{{ route('applicant.application.edit', ['id' => $application->application_form_id]) }}"
                                        class="inline-block bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded text-sm">
                                        ✏️ Edit Application
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- APPLICATION DETAILS -->
                    <div class="grid grid-cols-4 gap-2 text-sm mb-4">
                        <div>
                            <label class="block text-[12px] font-semibold">Application No.</label>
                            <div class="editable-field bg-gray-100 text-center">{{ $application->application_no }}</div>
                        </div>
                        <div>
                            <label class="block text-[12px] font-semibold">Academic Year</label>
                            <div class="editable-field bg-gray-100 text-center">{{ $application->academic_year }}</div>
                        </div>
                        <div>
                            <label class="block text-[12px] font-semibold">School Term</label>
                            <div class="editable-field bg-gray-100 text-center">{{ $application->school_term }}</div>
                        </div>
                    </div>

                    <!-- I. PERSONAL INFORMATION -->
                    <h2 class="section-title">I. PERSONAL INFORMATION</h2>
                    <div class="section-box text-[13px] text-gray-800 leading-snug">

                        <!-- Name -->
                        <div class="grid grid-cols-4 gap-2 p-1.5">
                            <div>
                                <label class="block text-[12px] font-semibold">Last Name</label>
                                <div class="editable-field text-[13px]">{{ $user->last_name ?? '—' }}</div>
                            </div>
                            <div>
                                <label class="block text-[12px] font-semibold">First Name</label>
                                <div class="editable-field text-[13px]">{{ $user->first_name ?? '—' }}</div>
                            </div>
                            <div>
                                <label class="block text-[12px] font-semibold">Middle Name</label>
                                <div class="editable-field text-[13px]">{{ $user->middle_name ?? '—' }}</div>
                            </div>
                            <div>
                                <label class="block text-[12px] font-semibold">Suffix</label>
                                <div class="editable-field text-[13px]">{{ $user->suffix ?? 'N/A' }}</div>
                            </div>
                        </div>

                        <!-- Permanent Address -->
                        <div class="grid grid-cols-6 gap-2 p-1.5">
                            <div class="col-span-2">
                                <label class="block text-[12px] font-semibold">Permanent Address (No.)</label>
                                <div class="editable-field text-[13px]">{{ $user->address_no ?? '—' }}</div>
                            </div>
                            <div>
                                <label class="block text-[12px] font-semibold">Street</label>
                                <div class="editable-field text-[13px]">{{ $user->address_street ?? '—' }}</div>
                            </div>
                            <div>
                                <label class="block text-[12px] font-semibold">Barangay</label>
                                <div class="editable-field text-[13px]">{{ $user->barangay ?? '—' }}</div>
                            </div>
                            <div>
                                <label class="block text-[10px] font-semibold">City / Municipality</label>
                                <div class="editable-field text-[13px]">{{ $user->city ?? '—' }}</div>
                            </div>
                            <div>
                                <label class="block text-[12px] font-semibold">Province</label>
                                <div class="editable-field text-[13px]">{{ $user->province ?? '—' }}</div>
                            </div>
                        </div>

                        <!-- ZIP / Region / District / Passport / Email -->
                        <div class="grid grid-cols-6 gap-2 p-1.5">
                            <div>
                                <label class="block text-[12px] font-semibold">ZIP Code</label>
                                <div class="editable-field text-[13px]">{{ $user->zip_code ?? '—' }}</div>
                            </div>
                            <div>
                                <label class="block text-[12px] font-semibold">Region</label>
                                <div class="editable-field text-[13px]">{{ $user->region ?? '—' }}</div>
                            </div>
                            <div>
                                <label class="block text-[12px] font-semibold">District</label>
                                <div class="editable-field text-[13px]">{{ $user->district ?? '—' }}</div>
                            </div>
                            <div>
                                <label class="block text-[12px] font-semibold">Passport No.</label>
                                <div class="editable-field text-[13px]">{{ $user->passport_no ?? '—' }}</div>
                            </div>
                            <div class="col-span-2">
                                <label class="block text-[12px] font-semibold">E-mail Address</label>
                                <div class="editable-field text-[13px]">{{ $user->email ?? '—' }}</div>
                            </div>
                        </div>

                        <!-- Current Mailing Address -->
                        <div class="p-1.5">
                            <label class="block text-[12px] font-semibold">Current Mailing Address</label>
                            <div class="editable-field text-[13px]">{{ $user->current_address ?? '—' }}</div>
                        </div>

                        <!-- Telephone / Alternate Contact -->
                        <div class="grid grid-cols-2 gap-2 p-1.5">
                            <div>
                                <label class="block text-[12px] font-semibold">Telephone Nos. (Landline/Mobile)</label>
                                <div class="editable-field text-[13px]">{{ $user->phone ?? '—' }}</div>
                            </div>
                            <div>
                                <label class="block text-[12px] font-semibold">Alternate Contact No.</label>
                                <div class="editable-field text-[13px]">{{ $user->alternate_contact ?? '—' }}</div>
                            </div>
                        </div>

                        <!-- Civil Status / DOB / Age / Sex -->
                        <div class="grid grid-cols-4 gap-2 p-1.5">
                            <div>
                                <label class="block text-[12px] font-semibold">Civil Status</label>
                                <div class="editable-field text-[13px]">{{ $user->civil_status ?? '—' }}</div>
                            </div>
                            <div>
                                <label class="block text-[12px] font-semibold">Date of Birth</label>
                                <div class="editable-field text-[13px]">{{ $user->date_of_birth ?? '—' }}</div>
                            </div>
                            <div>
                                <label class="block text-[12px] font-semibold">Age</label>
                                <div class="editable-field text-[13px]">{{ $user->age ?? '—' }}</div>
                            </div>
                            <div>
                                <label class="block text-[12px] font-semibold">Sex</label>
                                <div class="editable-field text-[13px]">{{ $user->sex ?? '—' }}</div>
                            </div>
                        </div>

                        <!-- Parents -->
                        <div class="grid grid-cols-2 gap-2 p-1.5">
                            <div>
                                <label class="block text-[12px] font-semibold">Father’s Name</label>
                                <div class="editable-field text-[13px]">{{ $user->father_name ?? '—' }}</div>
                            </div>
                            <div>
                                <label class="block text-[12px] font-semibold">Mother’s Name</label>
                                <div class="editable-field text-[13px]">{{ $user->mother_name ?? '—' }}</div>
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
                            $strand_categories = $application->strand_category ?? [];
                            $applicant_types = $application->applicant_type ?? [];
                            $scholarship_types = $application->scholarship_type ?? [];
                            $research_approved = $application->research_approved ?? '';
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
                                <label><input type="checkbox" disabled {{ $research_approved == 'YES' ? 'checked' : '' }}
                                        class="ml-1 mr-1 text-xs">YES</label>
                                <label><input type="checkbox" disabled {{ $research_approved == 'NO' ? 'checked' : '' }}
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
                                {{ isset($user->employment_status) && is_array($user->employment_status) ? implode(', ', $user->employment_status) : '—' }}
                            </div>
                        </div>


                        <!-- a.1 Presently employed -->
                        <div class="px-3 py-2">
                            <p class="font-semibold">a.1 For those who are presently employed*</p>
                            <div class="grid grid-cols-12 gap-x-2 gap-y-1 mt-1">
                                <div class="col-span-2">Position</div>
                                <div class="col-span-4 border p-1 rounded">{{ $user->employed_position ?? '—' }}</div>

                                <div class="col-span-3 text-right pr-1">Length of Service</div>
                                <div class="col-span-3 border p-1 rounded">
                                    {{ $user->employed_length_of_service ?? '—' }}</div>

                                <div class="col-span-3 mt-1">Name of Company/Office</div>
                                <div class="col-span-9 mt-1 border p-1 rounded">
                                    {{ $user->employed_company_name ?? '—' }}</div>

                                <div class="col-span-3 mt-1">Address of Company/Office</div>
                                <div class="col-span-9 mt-1 border p-1 rounded">
                                    {{ $user->employed_company_address ?? '—' }}</div>

                                <div class="col-span-1 mt-1">Email</div>
                                <div class="col-span-5 mt-1 border p-1 rounded">{{ $user->employed_email ?? '—' }}
                                </div>

                                <div class="col-span-1 mt-1">Website</div>
                                <div class="col-span-5 mt-1 border p-1 rounded">{{ $user->employed_website ?? '—' }}
                                </div>

                                <div class="col-span-2 mt-1">Telephone No.</div>
                                <div class="col-span-4 mt-1 border p-1 rounded">{{ $user->employed_telephone ?? '—' }}
                                </div>

                                <div class="col-span-1 mt-1">Fax No.</div>
                                <div class="col-span-5 mt-1 border p-1 rounded">{{ $user->employed_fax ?? '—' }}</div>
                            </div>
                        </div>

                        <!-- a.2 Self-employed -->
                        <div class="px-3 py-2">
                            <p class="font-semibold">a.2 For those who are self-employed</p>
                            <div class="grid grid-cols-12 gap-x-2 gap-y-1 mt-1">
                                <div class="col-span-2">Business Name</div>
                                <div class="col-span-10 border p-1 rounded">
                                    {{ $user->self_employed_business_name ?? '—' }}</div>

                                <div class="col-span-2 mt-1">Address</div>
                                <div class="col-span-10 mt-1 border p-1 rounded">
                                    {{ $user->self_employed_address ?? '—' }}</div>

                                <div class="col-span-2 mt-1">Email/Website</div>
                                <div class="col-span-3 mt-1 border p-1 rounded">
                                    {{ $user->self_employed_email_website ?? '—' }}</div>

                                <div class="col-span-2 mt-1">Telephone No.</div>
                                <div class="col-span-3 mt-1 border p-1 rounded">
                                    {{ $user->self_employed_telephone ?? '—' }}</div>

                                <div class="col-span-1 mt-1">Fax No.</div>
                                <div class="col-span-1 mt-1 border p-1 rounded">{{ $user->self_employed_fax ?? '—' }}
                                </div>

                                <div class="col-span-2 mt-1">Type of Business</div>
                                <div class="col-span-4 mt-1 border p-1 rounded">
                                    {{ $user->self_employed_type_of_business ?? '—' }}</div>

                                <div class="col-span-2 mt-1">Years of Operation</div>
                                <div class="col-span-4 mt-1 border p-1 rounded">
                                    {{ $user->self_employed_years_of_operation ?? '—' }}</div>
                            </div>
                        </div>

                        <!-- Research Plans -->
                        <div class="px-3 py-2 border-t border-gray-400">
                            <p class="font-semibold">b. RESEARCH PLANS (Minimum of 300 words)</p>
                            <div class="border p-1 rounded mt-1">{{ $user->research_plans ?? '—' }}</div>
                        </div>

                        <!-- Career Plans -->
                        <div class="px-3 py-2 border-t border-gray-400">
                            <p class="font-semibold">c. CAREER PLANS (Minimum of 300 words)</p>
                            <div class="border p-1 rounded mt-1">{{ $user->career_plans ?? '—' }}</div>
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
                                    @forelse($user->research_involvements ?? [] as $research)
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
                                    @forelse($user->publications ?? [] as $pub)
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
                                    @forelse($user->awards ?? [] as $award)
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

                        <!-- General Requirements -->
                        <div class="space-y-2 pl-4">
                            <div>
                                <p>• Birth Certificate (Photocopy)</p>
                                <div class="p-1 border rounded">{{ $user->birth_certificate ?? 'Not uploaded' }}</div>
                            </div>

                            <div>
                                <p>• Certified True Copy of the Official Transcript of Record</p>
                                <div class="p-1 border rounded">{{ $user->transcript_record ?? 'Not uploaded' }}</div>
                            </div>

                            <div>
                                <p>• Endorsement 1 – Former professor in college for MS / former professor in MS program
                                    for PhD</p>
                                <div class="p-1 border rounded">{{ $user->endorsement_1 ?? 'Not uploaded' }}</div>
                            </div>

                            <div>
                                <p>• Endorsement 2 – Former professor in college for MS / former professor in MS program
                                    for PhD</p>
                                <div class="p-1 border rounded">{{ $user->endorsement_2 ?? 'Not uploaded' }}</div>
                            </div>
                        </div>

                        <!-- If Employed -->
                        <div class="mt-3 pl-4">
                            <p class="font-semibold">If Employed:</p>
                            <div class="space-y-2 pl-4 mt-1">
                                <div>
                                    <p>• Recommendation from Head of Agency</p>
                                    <div class="p-1 border rounded">
                                        {{ $user->recommendation_head_agency ?? 'Not uploaded' }}</div>
                                </div>
                                <div>
                                    <p>• Form 2A – Certificate of Employment and Permit to Study</p>
                                    <div class="p-1 border rounded">{{ $user->form_2a ?? 'Not uploaded' }}</div>
                                </div>
                                <div>
                                    <p>• Form 2B – Certificate of DepEd Employment and Permit to Study (for DepEd
                                        employees only)</p>
                                    <div class="p-1 border rounded">{{ $user->form_2b ?? 'Not uploaded' }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Other Requirements -->
                        <div class="mt-3 pl-4">
                            <p class="font-semibold">Other Requirements:</p>
                            <div class="space-y-2 pl-4 mt-1">
                                <div>
                                    <p>• Form C – Certification of Health Status</p>
                                    <div class="p-1 border rounded">
                                        {{ $user->form_c_health_status ?? 'Not uploaded' }}</div>
                                </div>
                                <div>
                                    <p>• Valid NBI Clearance</p>
                                    <div class="p-1 border rounded">{{ $user->nbi_clearance ?? 'Not uploaded' }}</div>
                                </div>
                                <div>
                                    <p>• Letter of Admission with Regular Status (includes Evaluation Sheet)</p>
                                    <div class="p-1 border rounded">{{ $user->letter_of_admission ?? 'Not uploaded' }}
                                    </div>
                                </div>
                                <div>
                                    <p>• Approved Program of Study</p>
                                    <div class="p-1 border rounded">
                                        {{ $user->approved_program_study ?? 'Not uploaded' }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Lateral Applicants -->
                        <div class="mt-3 pl-4">
                            <p class="font-semibold">Additional Requirements for Lateral Applicants:</p>
                            <div class="space-y-2 pl-4 mt-1">
                                <div>
                                    <p>• Certification from the university indicating:</p>
                                    <ul class="list-disc pl-8 text-[12px] mt-1">
                                        <li>Number of graduate units required in the program</li>
                                        <li>Number of graduate units already earned with corresponding grades</li>
                                    </ul>
                                    <div class="p-1 border rounded">
                                        {{ $user->lateral_certification ?? 'Not uploaded' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- VIII. DECLARATION -->
                    <h2 class="section-title mt-6">VIII. TRUTHFULNESS OF DATA AND DATA PRIVACY</h2>
                    <div class="section-box mb-4 text-[13px] leading-snug">
                        <p class="mb-2 text-justify">
                            I hereby certify that all information given above are true and correct to the best of my
                            knowledge. Any misinformation or withholding
                            of information will automatically disqualify me from the program, Project Science and
                            Technology Regional Alliance of Universities for National
                            Development (STRAND). I am willing to refund all the financial benefits received plus
                            appropriate interest if such misinformation is discovered.
                        </p>

                        <p class="mb-3 text-justify">
                            Moreover, I hereby authorize the Science Education Institute of the Department of Science
                            and Technology (SEI-DOST) to collect,
                            record, organize, update or modify, retrieve, consult, use, consolidate, block, erase or
                            destruct my personal data that I have provided in
                            relation to my application to this scholarship. I hereby affirm my right to be informed,
                            object to processing, access and rectify, suspend or
                            withdraw my personal data, and be indemnified in case of damages pursuant to the provisions
                            of the Republic Act No. 10173 of the Philippines,
                            Data Privacy Act of 2012 and its corresponding Implementing Rules and Regulations.
                        </p>

                        <div class="grid grid-cols-2 gap-6 mt-4">
                            <!-- Applicant Name -->
                            <div>
                                <label class="block text-[12px] font-semibold mb-1">Applicant Name</label>
                                <div class="p-1 border rounded bg-gray-100">
                                    {{ trim(($user->first_name ?? '') . ' ' . ($user->middle_name ?? '') . ' ' . ($user->last_name ?? '')) }}
                                </div>
                            </div>

                            <!-- Applicant Signature -->
                            <div>
                                <label class="block text-[12px] font-semibold mb-1">Applicant Signature</label>
                                @if (!empty($user->applicant_signature))
                                    <img src="{{ $user->applicant_signature }}" alt="Applicant Signature"
                                        class="border rounded w-full h-24 object-contain">
                                @else
                                    <div
                                        class="p-2 border rounded w-full h-24 flex items-center justify-center bg-gray-100 text-gray-500">
                                        No signature uploaded
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Terms Agreement -->
                        <div class="mt-4 flex items-center gap-2">
                            <input type="checkbox" class="h-4 w-4 border-gray-400" disabled
                                {{ $user->terms_and_conditions_agreed ? 'checked' : '' }}>
                            <span class="text-[12px]">I agree to the Terms, Conditions, and Data Privacy Policy.</span>
                        </div>
                    </div>
                @endforeach
            @endif

            <div class="mt-6">
                <a href="{{ route('applicant.dashboard') }}" class="text-blue-600 hover:underline">
                    ← Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
