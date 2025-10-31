<x-app-layout :headerTitle="'Application Form'">
    @php
        // Build auto fields safely (no 'use' here)
        $year = now()->year;
        $nextYear = $year + 1;
        // use a hyphen to avoid parsing issues with en-dash in blade expressions
        $academicYear = $year . '-' . $nextYear;
        $applicationNo = 'APP-' . now()->format('Y') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);

        // current authenticated user (may be null)
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

        .editable-field:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.12);
            border-color: #6366f1;
        }

        .section-title {
            color: #1e40af;
            font-weight: 700;
            font-size: 15px;
            margin-bottom: 6px;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 6px;
        }

        .table-xs th,
        .table-xs td {
            font-size: 13px;
            padding: 6px 8px;
            border: 1px solid #cbd5e1;
            vertical-align: top;
        }

        .section-box {
            border: 1px solid #cbd5e1;
            padding: 10px;
            border-radius: 6px;
            background: #fff;
        }

        /* ===== HEADER LAYOUT ===== */
        .header-row {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 12px;
        }

        .header-row .title {
            flex: 1;
            text-align: center;
            line-height: 1.15;
        }

        .header-row img.logo {
            width: 90px;
            height: 90px;
            object-fit: contain;
            border: 1px solid #d1d5db;
            padding: 4px;
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

        .add-row-btn {
            background: #edf2ff;
            border: 1px solid #c7d2fe;
            color: #3730a3;
            padding: 4px 8px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
        }

        .remove-row-btn {
            background: #fff1f2;
            border: 1px solid #fecaca;
            color: #991b1b;
            padding: 4px 6px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
        }
    </style>

    <div class="py-6 px-4 sm:px-6 lg:px-8 bg-gray-50 min-h-screen">
        <div class="print-area">
            <form id="applicationForm" method="POST" action="{{ route('applicant.application.store') }}"
                enctype="multipart/form-data">
                @csrf

                <!-- HEADER -->
                <div class="header-row">
                    <div class="logo-box">
                        <img src="{{ asset('images/DOST.png') }}" alt="DOST Logo" class="logo">
                    </div>

                    <div class="title">
                        <p class="text-sm font-semibold">DEPARTMENT OF SCIENCE AND TECHNOLOGY</p>
                        <p class="text-sm font-semibold">SCIENCE EDUCATION INSTITUTE</p>
                        <p class="text-xs">Bicutan, Taguig City</p>
                        <h1 class="text-base font-bold underline mt-1">APPLICATION FORM</h1>
                        <p class="text-xs mt-1">for the</p>
                        <h2 class="text-sm font-bold mt-1">
                            SCIENCE AND TECHNOLOGY REGIONAL ALLIANCE OF UNIVERSITIES<br>
                            FOR NATIONAL DEVELOPMENT (STRAND)
                        </h2>
                    </div>

                    <div class="photo-box relative">
                        <p class="font-semibold mb-1 text-[12px]">Attach here</p>
                        <p class="text-[11px]">1 latest passport size picture</p>

                        <input type="file" id="photoUpload" name="passport_picture" accept="image/*"
                            class="absolute inset-0 opacity-0 cursor-pointer z-10" onchange="previewPhoto(event)">

                        <div id="photoPreview" class="photo-preview">
                            <span>Click to Upload<br>Photo</span>
                        </div>
                    </div>
                </div>

                <!-- APPLICATION DETAILS -->
                <div class="grid grid-cols-4 gap-2 text-sm mb-4">
                    <div>
                        <label class="block text-[12px] font-semibold">Application No.</label>
                        <input type="text" name="application_no_display" value="{{ $applicationNo }}" readonly
                            data-auto-field="application_no"
                            class="w-full border border-gray-400 px-2 py-0.5 text-[13px] rounded-sm bg-gray-100 font-mono text-center">
                    </div>

                    <div>
                        <label class="block text-[12px] font-semibold">Academic Year</label>
                        <input type="text" name="academic_year_display" value="{{ $academicYear }}" readonly
                            data-auto-field="academic_year"
                            class="w-full border border-gray-400 px-2 py-0.5 text-[13px] rounded-sm bg-gray-100 font-mono text-center">
                    </div>

                    <div>
                        <label class="block text-[12px] font-semibold">School Term</label>
                        <select name="school_term"
                            class="w-full border border-gray-400 px-2 py-0.5 text-[13px] rounded-sm">
                            <option value="1st Semester" {{ old('school_term') == '1st Semester' ? 'selected' : '' }}>1st
                                Semester</option>
                            <option value="2nd Semester" {{ old('school_term') == '2nd Semester' ? 'selected' : '' }}>2nd
                                Semester</option>
                            <option value="Trimester" {{ old('school_term') == '3rd Semester' ? 'selected' : '' }}>3rd
                                Semester</option>
                        </select>
                    </div>
                </div>

                <!-- I. PERSONAL INFORMATION -->
                <h2 class="section-title">I. PERSONAL INFORMATION</h2>
                <div class="section-box text-[13px] text-gray-800 leading-snug">
                    <div class="grid grid-cols-4 gap-2 p-1.5">
                        <div>
                            <label class="block text-[12px] font-semibold">Last Name</label>
                            <div class="editable-field" contenteditable="false" data-field="last_name">
                                {{ old('last_name', $user->last_name ?? '') }}</div>
                        </div>
                        <div>
                            <label class="block text-[12px] font-semibold">First Name</label>
                            <div class="editable-field" contenteditable="false" data-field="first_name">
                                {{ old('first_name', $user->first_name ?? '') }}</div>
                        </div>
                        <div>
                            <label class="block text-[12px] font-semibold">Middle Name</label>
                            <div class="editable-field" contenteditable="false" data-field="middle_name">
                                {{ old('middle_name', $user->middle_name ?? '') }}</div>
                        </div>
                        <div>
                            <label class="block text-[12px] font-semibold">Suffix</label>
                            <div class="editable-field" contenteditable="true" data-field="suffix">
                                {{ old('suffix', $user->suffix ?? 'N/A') }}</div>
                        </div>
                    </div>

                    <!-- permanent address -->
                    <div class="grid grid-cols-6 gap-2 p-1.5">
                        <div class="col-span-2">
                            <label class="block text-[12px] font-semibold">Permanent Address (No.)</label>
                            <div class="editable-field" contenteditable="true" data-field="address_no">
                                {{ old('address_no', '') }}</div>
                        </div>
                        <div>
                            <label class="block text-[12px] font-semibold">Street</label>
                            <div class="editable-field" contenteditable="true" data-field="address_street">
                                {{ old('address_street', '') }}</div>
                        </div>
                        <div>
                            <label for="barangay_select" class="block text-[12px] font-semibold">Barangay</label>
                            <select id="barangay_select" name="barangay"
                                class="w-full border border-gray-400 rounded-sm px-2 py-1 text-[13px] bg-white">
                                <option value="">Select</option>
                            </select>
                        </div>
                        <div>
                            <label for="city_select" class="block text-[10px] font-semibold">City / Municipality</label>
                            <select id="city_select" name="city"
                                class="w-full border border-gray-400 rounded-sm px-2 py-1 text-[13px] bg-white">
                                <option value="">Select</option>
                            </select>
                        </div>
                        <div>
                            <label for="province_select" class="block text-[12px] font-semibold">Province</label>
                            <select id="province_select" name="province"
                                class="w-full border border-gray-400 rounded-sm px-2 py-1 text-[13px] bg-white">
                                <option value="">Select </option>
                            </select>
                        </div>
                    </div>

                    <!-- Row c -->
                    <div class="grid grid-cols-6 gap-2 p-1.5">
                        <div>
                            <label for="zip_code" class="block text-[12px] font-semibold">ZIP Code</label>
                            <input id="zip_code" name="zip_code" type="text" readonly
                                class="w-full border border-gray-400 rounded-sm px-2 py-1 text-[13px] bg-gray-100">
                        </div>
                        <div>
                            <label for="region_select" class="block text-[12px] font-semibold">Region</label>
                            <input id="region_select" name="region" type="text" readonly
                                class="w-full border border-gray-400 rounded-sm px-2 py-1 text-[13px] bg-gray-100">
                        </div>
                        <div>
                            <label for="district" class="block text-[12px] font-semibold">District</label>
                            <input id="district" name="district" type="text"
                                class="w-full border border-gray-400 rounded-sm px-2 py-1 text-[13px] bg-white"
                                placeholder="(Optional)">
                        </div>
                        <div>
                            <label class="block text-[12px] font-semibold">Passport No.</label>
                            <div class="editable-field" contenteditable="true" data-field="passport_no">
                                {{ old('passport_no', '') }}</div>
                        </div>
                        <div class="col-span-2">
                            <label class="block text-[12px] font-semibold">E-mail Address</label>
                            <div class="editable-field" contenteditable="true" data-field="email_address">
                                {{ old('email_address', $user->email ?? '') }}</div>
                        </div>
                    </div>

                    <!-- Row d -->
                    <div class="p-1.5">
                        <label class="block text-[12px] font-semibold">Current Mailing Address</label>
                        <div class="editable-field" contenteditable="true" data-field="current_address">
                            {{ old('current_address', '') }}</div>
                    </div>

                    <!-- Row e -->
                    <div class="grid grid-cols-2 gap-2 p-1.5">
                        <div>
                            <label class="block text-[12px] font-semibold">Telephone Nos. (Landline/Mobile)</label>
                            <div class="editable-field" contenteditable="true" data-field="telephone_nos">
                                {{ old('telephone_nos', $user->phone ?? '') }}</div>
                        </div>
                        <div>
                            <label class="block text-[12px] font-semibold">Alternate Contact No.</label>
                            <div class="editable-field" contenteditable="true" data-field="alternate_contact">
                                {{ old('alternate_contact', '') }}</div>
                        </div>
                    </div>

                    <!-- Row f -->
                    <div class="grid grid-cols-4 gap-2 p-1.5">

                        <!-- Civil Status -->
                        <div>
                            <label class="block text-[12px] font-semibold">Civil Status</label>
                            <select class="w-full border border-gray-300 px-2 py-1 text-[12px] rounded"
                                data-field="civil_status">
                                <option value="">Select</option>
                                <option value="Single" {{ old('civil_status') == 'Single' ? 'selected' : '' }}>Single
                                </option>
                                <option value="Married" {{ old('civil_status') == 'Married' ? 'selected' : '' }}>
                                    Married</option>
                                <option value="Widowed" {{ old('civil_status') == 'Widowed' ? 'selected' : '' }}>
                                    Widowed</option>
                                <option value="Separated" {{ old('civil_status') == 'Separated' ? 'selected' : '' }}>
                                    Separated</option>
                            </select>
                        </div>
                        <!-- Date of Birth -->
                        <div>
                            <label class="block text-[12px] font-semibold">Date of Birth</label>
                            <input type="date" class="w-full border border-gray-300 px-2 py-1 text-[12px] rounded"
                                data-field="date_of_birth" id="dob" value="{{ old('date_of_birth') }}">
                        </div>
                        <!-- Age -->
                        <div>
                            <label class="block text-[12px] font-semibold">Age</label>
                            <input type="text"
                                class="w-full border border-gray-300 px-2 py-1 text-[12px] rounded bg-gray-100 cursor-not-allowed"
                                data-field="age" id="age" readonly value="{{ old('age') }}">
                        </div>
                        <!-- Sex -->
                        <div>
                            <label class="block text-[12px] font-semibold">Sex</label>
                            <select class="w-full border border-gray-300 px-2 py-1 text-[12px] rounded"
                                data-field="sex">
                                <option value="">Select</option>
                                <option value="Male" {{ old('sex') == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('sex') == 'Female' ? 'selected' : '' }}>Female</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2 p-1.5">
                        <div>
                            <label class="block text-[12px] font-semibold">Father’s Name</label>
                            <div class="editable-field" contenteditable="true" data-field="father_name">
                                {{ old('father_name', '') }}</div>
                        </div>
                        <div>
                            <label class="block text-[12px] font-semibold">Mother’s Name</label>
                            <div class="editable-field" contenteditable="true" data-field="mother_name">
                                {{ old('mother_name', '') }}</div>
                        </div>
                    </div>
                </div>

                <!-- II. EDUCATIONAL BACKGROUND -->
                <h2 class="section-title mt-6">II. EDUCATIONAL BACKGROUND</h2>

                <table class="w-full border border-gray-400 text-[13px] text-gray-800 table-fixed mb-3">
                    <thead class="bg-gray-100">
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
                            <tr class="align-top">
                                <td class="border border-gray-400 font-semibold text-center py-1">{{ $label }}
                                </td>

                                <td class="border border-gray-400 px-1">
                                    <div class="editable-field min-h-[24px]" contenteditable="true"
                                        data-field="{{ $levelKey }}_period"></div>
                                </td>

                                <td class="border border-gray-400 px-1">
                                    <div class="editable-field min-h-[24px]" contenteditable="true"
                                        data-field="{{ $levelKey }}_field"></div>
                                </td>

                                <td class="border border-gray-400 px-1">
                                    <div class="editable-field min-h-[24px]" contenteditable="true"
                                        data-field="{{ $levelKey }}_university"></div>
                                </td>

                                <!-- Scholarship column -->
                                <td class="border border-gray-400 px-1 py-1">
                                    <div class="grid grid-cols-2 text-[12px] leading-tight gap-x-2">
                                        @if ($label === 'BS')
                                            <label><input type="checkbox" name="bs_scholarship_type[]" value="PSHS"
                                                    class="mr-1">PSHS</label>
                                            <label><input type="checkbox" name="bs_scholarship_type[]"
                                                    value="RA 7687" class="mr-1">RA 7687</label>
                                            <label><input type="checkbox" name="bs_scholarship_type[]" value="MERIT"
                                                    class="mr-1">MERIT</label>
                                            <label><input type="checkbox" name="bs_scholarship_type[]"
                                                    value="RA 10612" class="mr-1">RA 10612</label>
                                        @elseif($label === 'MS')
                                            <label><input type="checkbox" name="ms_scholarship_type[]"
                                                    value="NSDB/NSTA" class="mr-1">NSDB/NSTA</label>
                                            <label><input type="checkbox" name="ms_scholarship_type[]"
                                                    value="ASTHRDP" class="mr-1">ASTHRDP</label>
                                            <label><input type="checkbox" name="ms_scholarship_type[]" value="ERDT"
                                                    class="mr-1">ERDT</label>
                                            <label><input type="checkbox" name="ms_scholarship_type[]"
                                                    value="COUNCIL/SEI" class="mr-1">COUNCIL/SEI</label>
                                        @elseif($label === 'PHD')
                                            <label><input type="checkbox" name="phd_scholarship_type[]"
                                                    value="NSDB/NSTA" class="mr-1">NSDB/NSTA</label>
                                            <label><input type="checkbox" name="phd_scholarship_type[]"
                                                    value="ASTHRDP" class="mr-1">ASTHRDP</label>
                                            <label><input type="checkbox" name="phd_scholarship_type[]"
                                                    value="ERDT" class="mr-1">ERDT</label>
                                            <label><input type="checkbox" name="phd_scholarship_type[]"
                                                    value="COUNCIL/SEI" class="mr-1">COUNCIL/SEI</label>
                                        @endif
                                    </div>

                                    <!-- Editable underline for OTHERS -->
                                    <div class="text-[12px] mt-1 flex items-center gap-1">
                                        <span>OTHERS:</span>
                                        <div contenteditable="true"
                                            data-field="{{ $levelKey }}_scholarship_others"
                                            class="editable-field border-0 border-b border-gray-500 focus:outline-none focus:border-blue-500 w-36 inline-block min-h-[18px] px-1 bg-transparent">
                                        </div>
                                    </div>
                                </td>

                                <td class="border border-gray-400 px-1">
                                    <div class="editable-field min-h-[24px]" contenteditable="true"
                                        data-field="{{ $levelKey }}_remarks"></div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- III. GRADUATE SCHOLARSHIP INTENTIONS DATA -->
                <h2 class="section-title mt-6">III. GRADUATE SCHOLARSHIP INTENTIONS DATA</h2>

                <div class="border border-gray-400 rounded text-[13px] text-gray-800 leading-tight section-box p-0">

                    <!-- Notes -->
                    <div class="px-3 py-2 text-[12px] text-justify border-b border-gray-400">
                        <p class="mb-1"><strong>Notes:</strong></p>
                        <ol class="list-decimal list-inside space-y-1">
                            <li>An applicant for a graduate program should elect to go to another university if he/she
                                earned his/her
                                1<sup>st</sup> (BS) and/or 2<sup>nd</sup> (MS) degrees from the same university to avoid
                                inbreeding.</li>
                            <li>A faculty-applicant for a graduate program should elect to go to any of the member
                                universities of the
                                ASTHRDP National Science Consortium, or the ERDT Consortium, or CBPSME National
                                Consortium in Graduate
                                Science and Mathematics Education, or in a foreign university with good track record
                                and/or recognized
                                higher education/institution in the specialized field in S&T to be pursued.</li>
                        </ol>
                    </div>

                    <!-- Strand / Type / Scholarship table -->
                    <table class="w-full text-[13px] border-t border-b border-gray-400 text-center">
                        <thead class="bg-gray-100 font-semibold">
                            <tr>
                                <th class="border-r border-gray-400 py-1">STRAND CATEGORY</th>
                                <th class="border-r border-gray-400 py-1">TYPE OF APPLICANT<br><span
                                        class="font-normal text-xs">(for STRAND 2 only)</span></th>
                                <th class="py-1">TYPE OF SCHOLARSHIP APPLIED FOR</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="border-r border-gray-400 align-top px-3 py-2 text-left">
                                    <label><input type="checkbox" name="strand_category[]" value="STRAND 1"
                                            class="mr-1">STRAND 1</label><br>
                                    <label><input type="checkbox" name="strand_category[]" value="STRAND 2"
                                            class="mr-1">STRAND 2</label>
                                </td>

                                <td class="border-r border-gray-400 align-top px-3 py-2 text-left">
                                    <label><input type="checkbox" name="applicant_type[]" value="Student"
                                            class="mr-1">Student</label><br>
                                    <label><input type="checkbox" name="applicant_type[]" value="Faculty"
                                            class="mr-1">Faculty</label>
                                </td>

                                <td class="align-top px-3 py-2 text-left">
                                    <label><input type="checkbox" name="scholarship_type[]" value="MS"
                                            class="mr-1">MS</label><br>
                                    <label><input type="checkbox" name="scholarship_type[]" value="PhD"
                                            class="mr-1">PhD</label>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- New Applicant -->
                    <div class="px-3 py-2 border-t border-gray-400">
                        <p class="font-semibold">New Applicant</p>
                        <div class="mt-1 text-[13px] space-y-2">
                            <div class="flex items-center gap-2">
                                <span><strong>a.</strong> University where you applied/intend to enroll for graduate
                                    studies:</span>
                                <div contenteditable="true" data-field="new_applicant_university"
                                    class="editable-field flex-1 border-0 border-b border-gray-400 bg-transparent min-h-[20px]">
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <span><strong>b.</strong> Course/Degree:</span>
                                <div contenteditable="true" data-field="new_applicant_course"
                                    class="editable-field flex-1 border-0 border-b border-gray-400 bg-transparent min-h-[20px]">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Lateral Applicant -->
                    <div class="px-3 py-2 border-t border-gray-400">
                        <p class="font-semibold">Lateral Applicant</p>
                        <div class="mt-1 text-[13px] space-y-2">
                            <div class="flex items-center gap-2">
                                <span><strong>a.</strong> University enrolled in:</span>
                                <div contenteditable="true" data-field="lateral_university_enrolled"
                                    class="editable-field flex-1 border-0 border-b border-gray-400 bg-transparent min-h-[20px]">
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <span><strong>b.</strong> Course/Degree:</span>
                                <div contenteditable="true" data-field="lateral_course_degree"
                                    class="editable-field flex-1 border-0 border-b border-gray-400 bg-transparent min-h-[20px]">
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <div class="flex items-center gap-2">
                                    <span><strong>c.</strong> Number of units earned:</span>
                                    <div contenteditable="true" data-field="lateral_units_earned"
                                        class="editable-field flex-1 border-0 border-b border-gray-400 bg-transparent min-h-[20px]">
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span><strong>d.</strong> No. of remaining units/sems:</span>
                                    <div contenteditable="true" data-field="lateral_remaining_units"
                                        class="editable-field flex-1 border-0 border-b border-gray-400 bg-transparent min-h-[20px]">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Research topic approval -->
                    <div class="px-3 py-2 border-t border-gray-400 text-[13px]">
                        <div class="flex items-center gap-2">
                            <span><strong>e.</strong> Has your research topic been approved by the panel?</span>
                            <label><input type="checkbox" name="research_approved" value="YES"
                                    class="ml-2 mr-1">YES</label>
                            <label><input type="checkbox" name="research_approved" value="NO"
                                    class="ml-2 mr-1">NO</label>
                        </div>

                        <div class="mt-2">
                            <span>Title:</span>
                            <div contenteditable="true" data-field="research_title"
                                class="editable-field border-0 border-b border-gray-400 bg-transparent inline-block w-[90%] min-h-[20px]">
                            </div>
                        </div>

                        <div class="mt-2">
                            <span>Date of last enrollment in thesis/dissertation course:</span>
                            <div contenteditable="true" data-field="research_date"
                                class="editable-field border-0 border-b border-gray-400 bg-transparent inline-block w-[60%] min-h-[20px]">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- IV. CAREER/EMPLOYMENT INFORMATION -->
                <h2 class="section-title">IV. CAREER/EMPLOYMENT INFORMATION</h2>

                <div class="border border-gray-400 text-[13px] text-gray-800 leading-tight section-box">

                    <!-- a. Employment Status -->
                    <div class="px-3 py-2">
                        <p>a. Present Employment Status</p>
                        <div class="grid grid-cols-5 gap-3 mt-1 text-[13px]">
                            <label><input type="checkbox" name="employment_status[]" value="Permanent"
                                    class="mr-1">Permanent</label>
                            <label><input type="checkbox" name="employment_status[]" value="Contractual"
                                    class="mr-1">Contractual</label>
                            <label><input type="checkbox" name="employment_status[]" value="Probationary"
                                    class="mr-1">Probationary</label>
                            <label><input type="checkbox" name="employment_status[]" value="Self-employed"
                                    class="mr-1">Self-employed</label>
                            <label><input type="checkbox" name="employment_status[]" value="Unemployed"
                                    class="mr-1">Unemployed</label>
                        </div>
                    </div>

                    <!-- a.1 Presently employed -->
                    <div class="px-3 py-2">
                        <p class="font-semibold">a.1 For those who are presently employed*</p>

                        <div class="grid grid-cols-12 gap-x-2 gap-y-1 mt-1">
                            <div class="col-span-2">Position</div>
                            <div class="col-span-4 border-b border-gray-400 editable-field" contenteditable="true"
                                data-field="employed_position"></div>
                            <div class="col-span-3 text-right pr-1">Length of Service</div>
                            <div class="col-span-3 border-b border-gray-400 editable-field" contenteditable="true"
                                data-field="employed_length_of_service"></div>

                            <div class="col-span-3 mt-1">Name of Company/Office</div>
                            <div class="col-span-9 mt-1 border-b border-gray-400 editable-field"
                                contenteditable="true" data-field="employed_company_name"></div>

                            <div class="col-span-3 mt-1">Address of Company/Office</div>
                            <div class="col-span-9 mt-1 border-b border-gray-400 editable-field"
                                contenteditable="true" data-field="employed_company_address"></div>

                            <div class="col-span-1 mt-1">Email</div>
                            <div class="col-span-5 mt-1 border-b border-gray-400 editable-field"
                                contenteditable="true" data-field="employed_email"></div>
                            <div class="col-span-1 mt-1">Website</div>
                            <div class="col-span-5 mt-1 border-b border-gray-400 editable-field"
                                contenteditable="true" data-field="employed_website"></div>

                            <div class="col-span-2 mt-1">Telephone No.</div>
                            <div class="col-span-4 mt-1 border-b border-gray-400 editable-field"
                                contenteditable="true" data-field="employed_telephone"></div>
                            <div class="col-span-1 mt-1">Fax No.</div>
                            <div class="col-span-5 mt-1 border-b border-gray-400 editable-field"
                                contenteditable="true" data-field="employed_fax"></div>
                        </div>
                    </div>

                    <!-- a.2 Self-employed -->
                    <div class="px-3 py-2">
                        <p class="font-semibold">a.2 For those who are self-employed</p>

                        <div class="grid grid-cols-12 gap-x-2 gap-y-1 mt-1">
                            <div class="col-span-2">Business Name</div>
                            <div class="col-span-10 border-b border-gray-400 editable-field" contenteditable="true"
                                data-field="self_employed_business_name"></div>

                            <div class="col-span-2 mt-1">Address</div>
                            <div class="col-span-10 mt-1 border-b border-gray-400 editable-field"
                                contenteditable="true" data-field="self_employed_address"></div>

                            <div class="col-span-2 mt-1">Email/Website</div>
                            <div class="col-span-3 mt-1 border-b border-gray-400 editable-field"
                                contenteditable="true" data-field="self_employed_email_website"></div>

                            <div class="col-span-2 mt-1">Telephone No.</div>
                            <div class="col-span-3 mt-1 border-b border-gray-400 editable-field"
                                contenteditable="true" data-field="self_employed_telephone"></div>

                            <div class="col-span-1 mt-1">Fax No.</div>
                            <div class="col-span-1 mt-1 border-b border-gray-400 editable-field"
                                contenteditable="true" data-field="self_employed_fax"></div>

                            <div class="col-span-2 mt-1">Type of Business</div>
                            <div class="col-span-4 mt-1 border-b border-gray-400 editable-field"
                                contenteditable="true" data-field="self_employed_type_of_business"></div>

                            <div class="col-span-2 mt-1">Years of Operation</div>
                            <div class="col-span-4 mt-1 border-b border-gray-400 editable-field"
                                contenteditable="true" data-field="self_employed_years_of_operation"></div>
                        </div>
                    </div>

                    <!-- Scholarship Note -->
                    <div class="px-3 py-2 mt-1 bg-gray-100 border-t border-gray-400 italic text-[12px]">
                        *Once accepted in the scholarship program, the scholar must obtain permission to go on a Leave
                        of Absence (LOA)
                        from his/her employer and become a full-time student. The scholar must submit a letter from
                        his/her employer
                        approving the LOA.
                    </div>

                    <!-- b. Research Plans -->
                    <div class="px-3 py-2 border-t border-gray-400">
                        <p class="font-semibold">b. RESEARCH PLANS (Minimum of 300 words)</p>
                        <p>Briefly discuss your proposed research area/s.</p>
                        <div class="editable-field w-full mt-1 border-b border-gray-400 overflow-hidden resize-none min-h-[1.5rem]"
                            contenteditable="true"
                            oninput="this.style.height='auto';this.style.height=this.scrollHeight+'px';"
                            data-field="research_plans"></div>
                    </div>

                    <!-- c. Career Plans -->
                    <div class="px-3 py-2 border-t border-gray-400">
                        <p class="font-semibold">c. CAREER PLANS (Minimum of 300 words)</p>
                        <p>Discuss your future plans after graduation.</p>
                        <div class="editable-field w-full mt-1 border-b border-gray-400 overflow-hidden resize-none min-h-[1.5rem]"
                            contenteditable="true"
                            oninput="this.style.height='auto';this.style.height=this.scrollHeight+'px';"
                            data-field="career_plans"></div>
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
                                <tbody id="researchBody">
                                    <tr>
                                        <td class="table-xs">
                                            <div class="editable-field" contenteditable="true"
                                                data-field="research_involvements[0][field_title]"></div>
                                        </td>
                                        <td class="table-xs">
                                            <div class="editable-field" contenteditable="true"
                                                data-field="research_involvements[0][location_duration]"></div>
                                        </td>
                                        <td class="table-xs">
                                            <div class="editable-field" contenteditable="true"
                                                data-field="research_involvements[0][fund_source]"></div>
                                        </td>
                                        <td class="table-xs">
                                            <div class="editable-field" contenteditable="true"
                                                data-field="research_involvements[0][nature_of_involvement]"></div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <button type="button" class="add-row-btn" onclick="addResearchRow()">+ Add Row</button>
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
                                <tbody id="pubBody">
                                    <tr>
                                        <td class="table-xs">
                                            <div class="editable-field" contenteditable="true"
                                                data-field="publications[0][title]"></div>
                                        </td>
                                        <td class="table-xs">
                                            <div class="editable-field" contenteditable="true"
                                                data-field="publications[0][name_year]"></div>
                                        </td>
                                        <td class="table-xs">
                                            <div class="editable-field" contenteditable="true"
                                                data-field="publications[0][nature_of_involvement]"></div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <button type="button" class="add-row-btn" onclick="addPubRow()">+ Add Row</button>
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
                                <tbody id="awardBody">
                                    <tr>
                                        <td class="table-xs">
                                            <div class="editable-field" contenteditable="true"
                                                data-field="awards[0][title]"></div>
                                        </td>
                                        <td class="table-xs">
                                            <div class="editable-field" contenteditable="true"
                                                data-field="awards[0][giving_body]"></div>
                                        </td>
                                        <td class="table-xs">
                                            <div class="editable-field" contenteditable="true"
                                                data-field="awards[0][year]"></div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <button type="button" class="add-row-btn" onclick="addAwardRow()">+ Add Row</button>
                        </div>

                    </div>

                    <!-- VI. ATTACHED DOCUMENTS -->
                    <h2 class="section-title mt-6">VI. ATTACHED DOCUMENTS</h2>

                    <div class="section-box mb-4 text-[13px] leading-snug text-gray-800">
                        <p class="mb-2 font-semibold">Please attach clear scanned copies of the following documents
                            (PDF format only, max 20 MB each):</p>

                        <!-- General Requirements -->
                        <div class="space-y-2 pl-4">
                            <div>
                                <p>• Birth Certificate (Photocopy)</p>
                                <input type="file" name="birth_certificate" accept="application/pdf"
                                    class="block mt-1 w-full border border-gray-300 rounded-md p-1 text-sm text-gray-700">
                            </div>

                            <div>
                                <p>• Certified True Copy of the Official Transcript of Record</p>
                                <input type="file" name="transcript_record" accept="application/pdf"
                                    class="block mt-1 w-full border border-gray-300 rounded-md p-1 text-sm text-gray-700">
                            </div>

                            <div>
                                <p>• Endorsement 1 – Former professor in college for MS applicant / former professor in
                                    MS program for PhD applicant</p>
                                <input type="file" name="endorsement_1" accept="application/pdf"
                                    class="block mt-1 w-full border border-gray-300 rounded-md p-1 text-sm text-gray-700">
                            </div>

                            <div>
                                <p>• Endorsement 2 – Former professor in college for MS applicant / former professor in
                                    MS program for PhD applicant</p>
                                <input type="file" name="endorsement_2" accept="application/pdf"
                                    class="block mt-1 w-full border border-gray-300 rounded-md p-1 text-sm text-gray-700">
                            </div>
                        </div>

                        <!-- If Employed -->
                        <div class="mt-3 pl-4">
                            <p class="font-semibold">If Employed:</p>
                            <div class="space-y-2 pl-4 mt-1">
                                <div>
                                    <p>• Recommendation from Head of Agency</p>
                                    <input type="file" name="recommendation_head_agency" accept="application/pdf"
                                        class="block mt-1 w-full border border-gray-300 rounded-md p-1 text-sm text-gray-700">
                                </div>
                                <div>
                                    <p>• Form 2A – Certificate of Employment and Permit to Study</p>
                                    <input type="file" name="form_2a" accept="application/pdf"
                                        class="block mt-1 w-full border border-gray-300 rounded-md p-1 text-sm text-gray-700">
                                </div>
                                <div>
                                    <p>• Form 2B – Certificate of DepEd Employment and Permit to Study (for DepEd
                                        employees only)</p>
                                    <input type="file" name="form_2b" accept="application/pdf"
                                        class="block mt-1 w-full border border-gray-300 rounded-md p-1 text-sm text-gray-700">
                                </div>
                            </div>
                        </div>

                        <!-- Other Requirements -->
                        <div class="mt-3 pl-4">
                            <p class="font-semibold">Other Requirements:</p>
                            <div class="space-y-2 pl-4 mt-1">
                                <div>
                                    <p>• Form C – Certification of Health Status</p>
                                    <input type="file" name="form_c_health_status" accept="application/pdf"
                                        class="block mt-1 w-full border border-gray-300 rounded-md p-1 text-sm text-gray-700">
                                </div>
                                <div>
                                    <p>• Valid NBI Clearance</p>
                                    <input type="file" name="nbi_clearance" accept="application/pdf"
                                        class="block mt-1 w-full border border-gray-300 rounded-md p-1 text-sm text-gray-700">
                                </div>
                                <div>
                                    <p>• Letter of Admission with Regular Status (includes Evaluation Sheet)</p>
                                    <input type="file" name="letter_of_admission" accept="application/pdf"
                                        class="block mt-1 w-full border border-gray-300 rounded-md p-1 text-sm text-gray-700">
                                </div>
                                <div>
                                    <p>• Approved Program of Study</p>
                                    <input type="file" name="approved_program_study" accept="application/pdf"
                                        class="block mt-1 w-full border border-gray-300 rounded-md p-1 text-sm text-gray-700">
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
                                    <input type="file" name="lateral_certification" accept="application/pdf"
                                        class="block mt-1 w-full border border-gray-300 rounded-md p-1 text-sm text-gray-700">
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
                            <div>
                                <label class="block text-[12px] font-semibold mb-1">Applicant Name</label>
                                <div class="editable-field" contenteditable="true" data-field="applicant_name">
                                    {{ trim(($user->first_name ?? '') . ' ' . ($user->middle_name ?? '') . ' ' . ($user->last_name ?? '')) }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-[12px] font-semibold mb-1">Applicant Signature</label>
                                <canvas id="applicantSignature" class="border rounded w-full h-24"
                                    style="touch-action: none;"></canvas>
                                <button type="button" id="clearSignature"
                                    class="mt-2 px-2 py-1 text-sm bg-red-500 text-white rounded">Clear</button>
                                <input type="hidden" name="applicant_signature" id="applicantSignatureInput">
                            </div>
                            <div class="mt-4 flex items-center gap-2">
                                <input type="checkbox" name="terms_and_conditions_agreed" required
                                    class="h-4 w-4 border-gray-400">
                                <span class="text-[12px]">I agree to the Terms, Conditions, and Data Privacy
                                    Policy.</span>
                            </div>
                        </div>

                        <div class="text-right mt-8">
                            <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2 rounded-md shadow-sm">Submit
                                Application</button>
                        </div>
            </form>
        </div>
    </div>

    <!-- JS -->
    <script>
        /* ========================= PHOTO PREVIEW ========================= */
        function previewPhoto(event) {
            const file = event.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('photoPreview');
                preview.innerHTML = '';
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = "w-full h-full object-cover";
                preview.appendChild(img);
            };
            reader.readAsDataURL(file);
        }

        /* ========================= DYNAMIC TABLE ROWS ========================= */
        function addResearchRow() {
            const tbody = document.getElementById('researchBody');
            const idx = tbody.querySelectorAll('tr').length;
            const tr = document.createElement('tr');
            tr.innerHTML = `
        <td class="table-xs"><div class="editable-field" contenteditable="true" data-field="research_involvements[${idx}][field_title]"></div></td>
        <td class="table-xs"><div class="editable-field" contenteditable="true" data-field="research_involvements[${idx}][location_duration]"></div></td>
        <td class="table-xs"><div class="editable-field" contenteditable="true" data-field="research_involvements[${idx}][fund_source]"></div></td>
        <td class="table-xs"><div class="editable-field" contenteditable="true" data-field="research_involvements[${idx}][nature_of_involvement]"></div></td>
    `;
            tbody.appendChild(tr);
        }

        function addPubRow() {
            const tbody = document.getElementById('pubBody');
            const idx = tbody.querySelectorAll('tr').length;
            const tr = document.createElement('tr');
            tr.innerHTML = `
        <td class="table-xs"><div class="editable-field" contenteditable="true" data-field="publications[${idx}][title]"></div></td>
        <td class="table-xs"><div class="editable-field" contenteditable="true" data-field="publications[${idx}][name_year]"></div></td>
        <td class="table-xs"><div class="editable-field" contenteditable="true" data-field="publications[${idx}][nature_of_involvement]"></div></td>
    `;
            tbody.appendChild(tr);
        }

        function addAwardRow() {
            const tbody = document.getElementById('awardBody');
            const idx = tbody.querySelectorAll('tr').length;
            const tr = document.createElement('tr');
            tr.innerHTML = `
        <td class="table-xs"><div class="editable-field" contenteditable="true" data-field="awards[${idx}][title]"></div></td>
        <td class="table-xs"><div class="editable-field" contenteditable="true" data-field="awards[${idx}][giving_body]"></div></td>
        <td class="table-xs"><div class="editable-field" contenteditable="true" data-field="awards[${idx}][year]"></div></td>
    `;
            tbody.appendChild(tr);
        }

        /* ========================= BEFORE SUBMIT HANDLER ========================= */
        document.getElementById('applicationForm').addEventListener('submit', function(e) {
            const form = this;

            // Remove previously generated hidden inputs
            document.querySelectorAll('.generated-hidden').forEach(n => n.remove());

            // Convert editable fields to hidden inputs
            form.querySelectorAll('.editable-field').forEach(field => {
                const name = field.dataset.field;
                if (!name) return;
                let value = field.innerText.trim();

                // ✅ Auto-fill "N/A" for suffix if blank
                if (name === 'suffix' && (!value || value === '')) {
                    value = 'N/A';
                    field.innerText = 'N/A';
                }

                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = name;
                input.value = value;
                input.className = 'generated-hidden';
                form.appendChild(input);
            });

            // Include readonly display fields
            form.querySelectorAll('[data-auto-field]').forEach(el => {
                const fieldName = el.getAttribute('data-auto-field');
                const value = el.value ?? el.innerText ?? '';
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = fieldName;
                input.value = value;
                input.className = 'generated-hidden';
                form.appendChild(input);
            });
        });
    </script>

    <script>
        const dobInput = document.getElementById('dob');
        const ageInput = document.getElementById('age');

        dobInput.addEventListener('change', () => {
            const dob = new Date(dobInput.value);
            const today = new Date();
            let age = today.getFullYear() - dob.getFullYear();
            const m = today.getMonth() - dob.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) {
                age--;
            }
            ageInput.value = age >= 0 ? age : '';
        });
    </script>

    <script>
        const canvas = document.getElementById('applicantSignature');
        const ctx = canvas.getContext('2d');
        let drawing = false;

        function resizeCanvas() {
            const data = ctx.getImageData(0, 0, canvas.width, canvas.height);
            canvas.width = canvas.offsetWidth;
            canvas.height = canvas.offsetHeight;
            ctx.putImageData(data, 0, 0);
        }
        window.addEventListener('resize', resizeCanvas);
        resizeCanvas();

        canvas.addEventListener('mousedown', () => drawing = true);
        canvas.addEventListener('mouseup', () => drawing = false);
        canvas.addEventListener('mouseout', () => drawing = false);

        canvas.addEventListener('mousemove', draw);
        canvas.addEventListener('touchstart', (e) => {
            drawing = true;
            draw(e);
            e.preventDefault();
        });
        canvas.addEventListener('touchend', () => drawing = false);
        canvas.addEventListener('touchmove', draw);

        function getPos(e) {
            let rect = canvas.getBoundingClientRect();
            if (e.touches) {
                return {
                    x: e.touches[0].clientX - rect.left,
                    y: e.touches[0].clientY - rect.top
                };
            } else {
                return {
                    x: e.clientX - rect.left,
                    y: e.clientY - rect.top
                };
            }
        }

        function draw(e) {
            if (!drawing) return;
            const pos = getPos(e);
            ctx.lineWidth = 2;
            ctx.lineCap = 'round';
            ctx.strokeStyle = '#000';
            ctx.lineTo(pos.x, pos.y);
            ctx.stroke();
            ctx.beginPath();
            ctx.moveTo(pos.x, pos.y);

            // Save to hidden input for form submission
            document.getElementById('applicantSignatureInput').value = canvas.toDataURL();
        }

        document.getElementById('clearSignature').addEventListener('click', () => {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.beginPath();
            document.getElementById('applicantSignatureInput').value = '';
        });
    </script>

    <script>
        /* ========================= LOCATION SELECTION (PSGC API) ========================= */
        document.addEventListener('DOMContentLoaded', function() {
            const provinceSelect = document.getElementById('province_select');
            const citySelect = document.getElementById('city_select');
            const barangaySelect = document.getElementById('barangay_select');
            const regionInput = document.getElementById('region_select');
            const zipInput = document.getElementById('zip_code');

            const regionZipFallback = {
                "010000000": "2900",
                "020000000": "3500",
                "030000000": "2000",
                "040000000": "4000",
                "050000000": "4400",
                "060000000": "5000",
                "070000000": "6000",
                "080000000": "6500",
                "090000000": "7000",
                "100000000": "9000",
                "110000000": "8000",
                "120000000": "9600",
                "130000000": "1000",
                "140000000": "2600",
                "150000000": "9700",
                "160000000": "8600",
                "170000000": "5200"
            };

            async function setLocation(level, code) {
                try {
                    if (level === "provinces") {
                        const prov = await fetch(`https://psgc.gitlab.io/api/provinces/${code}/`).then(r => r
                            .json());
                        const region = await fetch(`https://psgc.gitlab.io/api/regions/${prov.regionCode}/`)
                            .then(r => r.json());
                        regionInput.value = region.name;
                        regionInput.dataset.code = region.code;
                        zipInput.value = prov.zipcode || regionZipFallback[region.code] || "";
                    }
                    if (level === "cities-municipalities") {
                        const city = await fetch(`https://psgc.gitlab.io/api/cities-municipalities/${code}/`)
                            .then(r => r.json());
                        const prov = await fetch(`https://psgc.gitlab.io/api/provinces/${city.provinceCode}/`)
                            .then(r => r.json());
                        const region = await fetch(`https://psgc.gitlab.io/api/regions/${prov.regionCode}/`)
                            .then(r => r.json());
                        regionInput.value = region.name;
                        regionInput.dataset.code = region.code;
                        zipInput.value = city.zipcode || prov.zipcode || regionZipFallback[region.code] || "";
                    }
                    if (level === "barangays") {
                        const brgy = await fetch(`https://psgc.gitlab.io/api/barangays/${code}/`).then(r => r
                            .json());
                        const cityCode = brgy.cityCode || brgy.municipalityCode;
                        if (!cityCode) return;
                        const city = await fetch(
                            `https://psgc.gitlab.io/api/cities-municipalities/${cityCode}/`).then(r => r
                            .json());
                        const prov = await fetch(`https://psgc.gitlab.io/api/provinces/${city.provinceCode}/`)
                            .then(r => r.json());
                        const region = await fetch(`https://psgc.gitlab.io/api/regions/${prov.regionCode}/`)
                            .then(r => r.json());
                        regionInput.value = region.name || "Unknown Region";
                        regionInput.dataset.code = region.code || "";
                        zipInput.value = brgy.zipcode || city.zipcode || prov.zipcode || regionZipFallback[
                            region.code] || "";
                    }
                } catch (err) {
                    console.error("Error setting location:", err);
                }
            }

            // Load provinces
            fetch('https://psgc.gitlab.io/api/provinces/')
                .then(res => res.json())
                .then(data => data.forEach(p => provinceSelect.add(new Option(p.name, p.code))))
                .catch(err => console.error('Error loading provinces:', err));

            // Province → City
            provinceSelect.addEventListener('change', function() {
                const provCode = this.value;
                citySelect.innerHTML = '<option value="">Select City / Municipality</option>';
                barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
                if (!provCode) return;
                fetch(`https://psgc.gitlab.io/api/provinces/${provCode}/cities-municipalities/`)
                    .then(res => res.json())
                    .then(data => data.forEach(c => citySelect.add(new Option(c.name, c.code))))
                    .catch(err => console.error('Error loading cities:', err));
                setLocation("provinces", provCode);
            });

            // City → Barangay
            citySelect.addEventListener('change', function() {
                const cityCode = this.value;
                barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
                if (!cityCode) return;
                setLocation("cities-municipalities", cityCode);
                fetch(`https://psgc.gitlab.io/api/cities-municipalities/${cityCode}/barangays/`)
                    .then(res => res.json())
                    .then(data => data.forEach(b => barangaySelect.add(new Option(b.name, b.code))))
                    .catch(err => console.error('Error loading barangays:', err));
            });

            // Barangay → finalize ZIP + Region
            barangaySelect.addEventListener('change', function() {
                const brgyCode = this.value;
                if (!brgyCode) return;
                setLocation("barangays", brgyCode);
            });
        });
    </script>
</x-app-layout>
