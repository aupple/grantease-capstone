<x-app-layout :headerTitle="'Application Form'">
    @php
        // ===== Auto-generate fields safely =====
        $year = now()->year;
        $nextYear = $year + 1;
        // Use hyphen instead of en dash to prevent Blade parsing issues
        $academicYear = $year . '-' . $nextYear;
        $applicationNo = 'APP-' . now()->format('Y') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);

        // Get current authenticated user (may be null)
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
            background: #fff;
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


    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <form id="applicationForm" method="POST" action="{{ route('applicant.application.store') }}"
                enctype="multipart/form-data" class="bg-white p-6 rounded shadow-md relative">
                @csrf

                <!-- ✅ SUCCESS TOAST -->
                <div id="successToast"
                    class="hidden fixed bottom-6 right-6 bg-green-600 text-white px-4 py-2 rounded shadow-lg transition-opacity duration-500 opacity-0">
                    ✅ Application submitted successfully!
                </div>


                <!-- =================== HEADER =================== -->
                <div class="flex justify-between items-start mb-6">

                    <!-- DOST Logo -->
                    <div class="w-[80px] flex-shrink-0">
                        <img src="{{ asset('images/DOST.png') }}" alt="DOST Logo" class="w-full h-auto">
                    </div>

                    <!-- Center Title -->
                    <div class="flex-1 text-center px-4">
                        <p class="text-sm font-semibold leading-tight">DEPARTMENT OF SCIENCE AND TECHNOLOGY</p>
                        <p class="text-sm font-semibold leading-tight">SCIENCE EDUCATION INSTITUTE</p>
                        <p class="text-xs">Bicutan, Taguig City</p>

                        <h1 class="text-base font-bold underline mt-1">APPLICATION FORM</h1>
                        <p class="text-xs mt-1">for the</p>
                        <h2 class="text-sm font-bold mt-1 leading-tight">
                            SCIENCE AND TECHNOLOGY REGIONAL ALLIANCE OF UNIVERSITIES<br>
                            FOR NATIONAL DEVELOPMENT (STRAND)
                        </h2>
                    </div>

                    <!-- ✅ Photo Upload (Fixed Layout) -->
                    <div class="flex flex-col items-center space-y-1 w-[140px] flex-shrink-0">
                        <p class="font-semibold text-[12px] leading-tight">Attach here</p>
                        <p class="text-[11px] leading-tight text-center">1 latest passport size picture</p>

                        <div
                            class="relative w-[120px] h-[140px] border border-gray-400 rounded-md overflow-hidden bg-gray-50">
                            <!-- Hidden file input -->
                            <input type="file" id="photoUpload" name="passport_picture" accept="image/*"
                                class="absolute inset-0 opacity-0 cursor-pointer z-10" onchange="previewPhoto(event)">

                            <!-- Preview area -->
                            <div id="photoPreview"
                                class="w-full h-full flex items-center justify-center text-[11px] text-gray-500">
                                <span>Click to Upload<br>Photo</span>
                            </div>
                        </div>
                    </div>

                </div>



                <!-- =================== APPLICATION DETAILS =================== -->
                <h2 class="text-sm font-semibold mb-2">Application Details</h2>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-4">
                    <div>
                        <label class="block text-[12px] font-semibold mb-1">Application No.</label>
                        <input type="text" readonly data-auto-field="application_no" value="{{ $applicationNo }}"
                            class="w-full border border-gray-300 px-2 py-1 text-[13px] rounded bg-gray-100 text-center font-mono">
                    </div>

                    <div>
                        <label class="block text-[12px] font-semibold mb-1">Academic Year</label>
                        <input type="text" readonly data-auto-field="academic_year" value="{{ $academicYear }}"
                            class="w-full border border-gray-300 px-2 py-1 text-[13px] rounded bg-gray-100 text-center font-mono">
                    </div>

                    <div>
                        <label class="block text-[12px] font-semibold mb-1">School Term</label>
                        <select name="school_term"
                            class="w-full border border-gray-300 px-2 py-1 text-[13px] rounded bg-white">
                            <option value="" disabled {{ old('school_term') ? '' : 'selected' }}>Select Term
                            </option>
                            <option value="1st Semester" {{ old('school_term') == '1st Semester' ? 'selected' : '' }}>1st
                                Semester</option>
                            <option value="2nd Semester" {{ old('school_term') == '2nd Semester' ? 'selected' : '' }}>2nd
                                Semester</option>
                            <option value="Trimester" {{ old('school_term') == 'Trimester' ? 'selected' : '' }}>Trimester
                            </option>
                        </select>
                    </div>
                </div>

                <!-- I. PERSONAL INFORMATION -->
                <h2 class="section-title">I. PERSONAL INFORMATION</h2>
                <div class="section-box text-[13px] text-gray-800 leading-snug">

                    <div class="grid grid-cols-4 gap-2 p-1.5">
                        <div>
                            <label class="block text-[12px] font-semibold">Last Name</label>
                            <input type="text" name="last_name" class="editable-field w-full"
                                value="{{ old('last_name', $user->last_name ?? '') }}" readonly>
                        </div>
                        <div>
                            <label class="block text-[12px] font-semibold">First Name</label>
                            <input type="text" name="first_name" class="editable-field w-full"
                                value="{{ old('first_name', $user->first_name ?? '') }}" readonly>
                        </div>
                        <div>
                            <label class="block text-[12px] font-semibold">Middle Name</label>
                            <input type="text" name="middle_name" class="editable-field w-full"
                                value="{{ old('middle_name', $user->middle_name ?? '') }}" readonly>
                        </div>
                        <div>
                            <label class="block text-[12px] font-semibold">Suffix</label>
                            <input type="text" name="suffix" class="editable-field w-full"
                                value="{{ old('suffix', $user->suffix ?? 'N/A') }}">
                        </div>
                    </div>

                    <!-- Permanent Address -->
                    <div class="grid grid-cols-6 gap-2 p-1.5">
                        <div class="col-span-2">
                            <label class="block text-[12px] font-semibold">Permanent Address (No.)</label>
                            <input type="text" name="address_no" class="editable-field w-full"
                                value="{{ old('address_no', '') }}">
                        </div>
                        <div>
                            <label class="block text-[12px] font-semibold">Street</label>
                            <input type="text" name="address_street" class="editable-field w-full"
                                value="{{ old('address_street', '') }}">
                        </div>
                        <div>
                            <label for="barangay_select" class="block text-[12px] font-semibold">Barangay</label>
                            <select id="barangay_select" name="barangay"
                                class="w-full border border-gray-400 rounded-sm px-2 py-1 text-[13px] bg-white">
                                <option value="">Select</option>
                            </select>
                        </div>
                        <div>
                            <label for="city_select" class="block text-[12px] font-semibold">City / Municipality</label>
                            <select id="city_select" name="city"
                                class="w-full border border-gray-400 rounded-sm px-2 py-1 text-[13px] bg-white">
                                <option value="">Select</option>
                            </select>
                        </div>
                        <div>
                            <label for="province_select" class="block text-[12px] font-semibold">Province</label>
                            <select id="province_select" name="province"
                                class="w-full border border-gray-400 rounded-sm px-2 py-1 text-[13px] bg-white">
                                <option value="">Select</option>
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
                            <input type="text" name="passport_no" class="editable-field w-full"
                                value="{{ old('passport_no', '') }}">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-[12px] font-semibold">E-mail Address</label>
                            <input type="email" name="email_address" class="editable-field w-full"
                                value="{{ old('email_address', $user->email ?? '') }}">
                        </div>
                    </div>

                    <!-- Row e -->
                    <div class="p-1.5">
                        <label class="block text-[12px] font-semibold">Current Mailing Address</label>
                        <input type="text" name="current_address" class="editable-field w-full"
                            value="{{ old('current_address', '') }}">
                    </div>

                    <!-- Row f -->
                    <div class="grid grid-cols-4 gap-2 p-1.5">
                        <div>
                            <label class="block text-[12px] font-semibold">Civil Status</label>
                            <select name="civil_status"
                                class="w-full border border-gray-300 px-2 py-1 text-[12px] rounded">
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
                        <div>
                            <label class="block text-[12px] font-semibold">Date of Birth</label>
                            <input type="date" name="date_of_birth" id="dob"
                                class="w-full border border-gray-300 px-2 py-1 text-[12px] rounded"
                                value="{{ old('date_of_birth') }}">
                        </div>
                        <div>
                            <label class="block text-[12px] font-semibold">Age</label>
                            <input type="text" name="age" id="age"
                                class="w-full border border-gray-300 px-2 py-1 text-[12px] rounded bg-gray-100 cursor-not-allowed"
                                readonly value="{{ old('age') }}">
                        </div>
                        <div>
                            <label class="block text-[12px] font-semibold">Sex</label>
                            <select name="sex"
                                class="w-full border border-gray-300 px-2 py-1 text-[12px] rounded">
                                <option value="">Select</option>
                                <option value="Male" {{ old('sex') == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('sex') == 'Female' ? 'selected' : '' }}>Female</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2 p-1.5">
                        <div>
                            <label class="block text-[12px] font-semibold">Father’s Name</label>
                            <input type="text" name="father_name" class="editable-field w-full"
                                value="{{ old('father_name', '') }}">
                        </div>
                        <div>
                            <label class="block text-[12px] font-semibold">Mother’s Name</label>
                            <input type="text" name="mother_name" class="editable-field w-full"
                                value="{{ old('mother_name', '') }}">
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

                                <!-- Period -->
                                <td class="border border-gray-400 px-1">
                                    <input type="text" name="{{ $levelKey }}_period"
                                        class="w-full border border-gray-300 px-2 py-1 text-[12px] rounded"
                                        value="{{ old($levelKey . '_period') }}" placeholder="e.g. 2018-2022">
                                </td>

                                <!-- Field -->
                                <td class="border border-gray-400 px-1">
                                    <input type="text" name="{{ $levelKey }}_field"
                                        class="w-full border border-gray-300 px-2 py-1 text-[12px] rounded"
                                        value="{{ old($levelKey . '_field') }}" placeholder="Field of Study">
                                </td>

                                <!-- University / School -->
                                <td class="border border-gray-400 px-1">
                                    <input type="text" name="{{ $levelKey }}_university"
                                        class="w-full border border-gray-300 px-2 py-1 text-[12px] rounded"
                                        value="{{ old($levelKey . '_university') }}" placeholder="Name of University">
                                </td>

                                <!-- Scholarship -->
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

                                    <!-- Others -->
                                    <div class="text-[12px] mt-1 flex items-center gap-1">
                                        <span>OTHERS:</span>
                                        <input type="text" name="{{ $levelKey }}_scholarship_others"
                                            class="border-b border-gray-500 focus:border-blue-500 w-36 text-[12px] bg-transparent outline-none"
                                            value="{{ old($levelKey . '_scholarship_others') }}">
                                    </div>
                                </td>

                                <!-- Remarks -->
                                <td class="border border-gray-400 px-1">
                                    <input type="text" name="{{ $levelKey }}_remarks"
                                        class="w-full border border-gray-300 px-2 py-1 text-[12px] rounded"
                                        value="{{ old($levelKey . '_remarks') }}" placeholder="Remarks (if any)">
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
                            <li>
                                An applicant for a graduate program should elect to go to another university if he/she
                                earned his/her
                                1<sup>st</sup> (BS) and/or 2<sup>nd</sup> (MS) degrees from the same university to avoid
                                inbreeding.
                            </li>
                            <li>
                                A faculty-applicant for a graduate program should elect to go to any of the member
                                universities of the
                                ASTHRDP National Science Consortium, or the ERDT Consortium, or CBPSME National
                                Consortium in Graduate
                                Science and Mathematics Education, or in a foreign university with good track record
                                and/or recognized
                                higher education/institution in the specialized field in S&T to be pursued.
                            </li>
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
                                <input type="text" name="new_applicant_university"
                                    class="flex-1 border-b border-gray-400 bg-transparent outline-none text-[13px] px-1"
                                    placeholder="Enter University" value="{{ old('new_applicant_university') }}">
                            </div>
                            <div class="flex items-center gap-2">
                                <span><strong>b.</strong> Course/Degree:</span>
                                <input type="text" name="new_applicant_course"
                                    class="flex-1 border-b border-gray-400 bg-transparent outline-none text-[13px] px-1"
                                    placeholder="Enter Course/Degree" value="{{ old('new_applicant_course') }}">
                            </div>
                        </div>
                    </div>

                    <!-- Lateral Applicant -->
                    <div class="px-3 py-2 border-t border-gray-400">
                        <p class="font-semibold">Lateral Applicant</p>
                        <div class="mt-1 text-[13px] space-y-2">
                            <div class="flex items-center gap-2">
                                <span><strong>a.</strong> University enrolled in:</span>
                                <input type="text" name="lateral_university_enrolled"
                                    class="flex-1 border-b border-gray-400 bg-transparent outline-none text-[13px] px-1"
                                    placeholder="Enter University" value="{{ old('lateral_university_enrolled') }}">
                            </div>
                            <div class="flex items-center gap-2">
                                <span><strong>b.</strong> Course/Degree:</span>
                                <input type="text" name="lateral_course_degree"
                                    class="flex-1 border-b border-gray-400 bg-transparent outline-none text-[13px] px-1"
                                    placeholder="Enter Course/Degree" value="{{ old('lateral_course_degree') }}">
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <div class="flex items-center gap-2">
                                    <span><strong>c.</strong> Number of units earned:</span>
                                    <input type="text" name="lateral_units_earned"
                                        class="flex-1 border-b border-gray-400 bg-transparent outline-none text-[13px] px-1"
                                        placeholder="e.g. 18" value="{{ old('lateral_units_earned') }}">
                                </div>
                                <div class="flex items-center gap-2">
                                    <span><strong>d.</strong> No. of remaining units/sems:</span>
                                    <input type="text" name="lateral_remaining_units"
                                        class="flex-1 border-b border-gray-400 bg-transparent outline-none text-[13px] px-1"
                                        placeholder="e.g. 2 sems" value="{{ old('lateral_remaining_units') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Research topic approval -->
                    <div class="px-3 py-2 border-t border-gray-400 text-[13px]">
                        <div class="flex items-center gap-2">
                            <span><strong>e.</strong> Has your research topic been approved by the panel?</span>
                            <label><input type="radio" name="research_approved" value="YES" class="ml-2 mr-1"
                                    {{ old('research_approved') == 'YES' ? 'checked' : '' }}>YES</label>
                            <label><input type="radio" name="research_approved" value="NO" class="ml-2 mr-1"
                                    {{ old('research_approved') == 'NO' ? 'checked' : '' }}>NO</label>
                        </div>

                        <div class="mt-2 flex items-center gap-2">
                            <span>Title:</span>
                            <input type="text" name="research_title"
                                class="border-b border-gray-400 bg-transparent outline-none text-[13px] px-1 flex-1"
                                placeholder="Enter Research Title" value="{{ old('research_title') }}">
                        </div>

                        <div class="mt-2 flex items-center gap-2">
                            <span>Date of last enrollment in thesis/dissertation course:</span>
                            <input type="date" name="research_date"
                                class="border-b border-gray-400 bg-transparent outline-none text-[13px] px-1"
                                value="{{ old('research_date') }}">
                        </div>
                    </div>
                </div>

                <!-- IV. CAREER/EMPLOYMENT INFORMATION -->
                <h2 class="section-title mt-6">IV. CAREER/EMPLOYMENT INFORMATION</h2>

                <div
                    class="bg-white border border-gray-300 rounded-lg shadow-sm p-6 text-[13px] text-gray-800 leading-tight">

                    <!-- a. Employment Status -->
                    <div class="mb-5">
                        <label class="block font-semibold mb-2">a. Present Employment Status</label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3">
                            @foreach (['Permanent', 'Contractual', 'Probationary', 'Self-employed', 'Unemployed'] as $status)
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="employment_status[]" value="{{ $status }}"
                                        class="mr-1">
                                    {{ $status }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- a.1 For those who are presently employed -->
                    <div class="mb-6">
                        <p class="font-semibold mb-2">a.1 For those who are presently employed*</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[12px]">Position</label>
                                <input type="text" name="employed_position"
                                    class="form-input w-full border-gray-300 rounded" />
                            </div>
                            <div>
                                <label class="block text-[12px]">Length of Service</label>
                                <input type="text" name="employed_length_of_service"
                                    class="form-input w-full border-gray-300 rounded" />
                            </div>
                            <div>
                                <label class="block text-[12px]">Name of Company/Office</label>
                                <input type="text" name="employed_company_name"
                                    class="form-input w-full border-gray-300 rounded" />
                            </div>
                            <div>
                                <label class="block text-[12px]">Address of Company/Office</label>
                                <input type="text" name="employed_company_address"
                                    class="form-input w-full border-gray-300 rounded" />
                            </div>
                            <div>
                                <label class="block text-[12px]">Email</label>
                                <input type="email" name="employed_email"
                                    class="form-input w-full border-gray-300 rounded" />
                            </div>
                            <div>
                                <label class="block text-[12px]">Website</label>
                                <input type="text" name="employed_website"
                                    class="form-input w-full border-gray-300 rounded" />
                            </div>
                            <div>
                                <label class="block text-[12px]">Telephone No.</label>
                                <input type="text" name="employed_telephone"
                                    class="form-input w-full border-gray-300 rounded" />
                            </div>
                            <div>
                                <label class="block text-[12px]">Fax No.</label>
                                <input type="text" name="employed_fax"
                                    class="form-input w-full border-gray-300 rounded" />
                            </div>
                        </div>
                    </div>

                    <!-- a.2 For those who are self-employed -->
                    <div class="mb-6">
                        <p class="font-semibold mb-2">a.2 For those who are self-employed</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[12px]">Business Name</label>
                                <input type="text" name="self_employed_business_name"
                                    class="form-input w-full border-gray-300 rounded" />
                            </div>
                            <div>
                                <label class="block text-[12px]">Address</label>
                                <input type="text" name="self_employed_address"
                                    class="form-input w-full border-gray-300 rounded" />
                            </div>
                            <div>
                                <label class="block text-[12px]">Email/Website</label>
                                <input type="text" name="self_employed_email_website"
                                    class="form-input w-full border-gray-300 rounded" />
                            </div>
                            <div>
                                <label class="block text-[12px]">Telephone No.</label>
                                <input type="text" name="self_employed_telephone"
                                    class="form-input w-full border-gray-300 rounded" />
                            </div>
                            <div>
                                <label class="block text-[12px]">Fax No.</label>
                                <input type="text" name="self_employed_fax"
                                    class="form-input w-full border-gray-300 rounded" />
                            </div>
                            <div>
                                <label class="block text-[12px]">Type of Business</label>
                                <input type="text" name="self_employed_type_of_business"
                                    class="form-input w-full border-gray-300 rounded" />
                            </div>
                            <div>
                                <label class="block text-[12px]">Years of Operation</label>
                                <input type="text" name="self_employed_years_of_operation"
                                    class="form-input w-full border-gray-300 rounded" />
                            </div>
                        </div>
                    </div>

                    <!-- Scholarship Note -->
                    <div class="mt-2 bg-gray-50 border border-gray-300 italic text-[12px] rounded p-3">
                        *Once accepted in the scholarship program, the scholar must obtain permission to go on a Leave
                        of Absence (LOA)
                        from his/her employer and become a full-time student. A letter from the employer approving the
                        LOA must be submitted.
                    </div>

                    <!-- b. Research Plans -->
                    <div class="mt-5">
                        <label class="block font-semibold mb-1">b. RESEARCH PLANS (Minimum of 300 words)</label>
                        <p class="text-[12px] mb-1">Briefly discuss your proposed research area/s.</p>
                        <textarea name="research_plans" rows="5" class="form-textarea w-full border-gray-300 rounded resize-none"></textarea>
                    </div>

                    <!-- c. Career Plans -->
                    <div class="mt-5">
                        <label class="block font-semibold mb-1">c. CAREER PLANS (Minimum of 300 words)</label>
                        <p class="text-[12px] mb-1">Discuss your future plans after graduation.</p>
                        <textarea name="career_plans" rows="5" class="form-textarea w-full border-gray-300 rounded resize-none"></textarea>
                    </div>

                </div>

                <!-- V. RESEARCH / PUBLICATIONS / AWARDS -->
                <h2 class="section-title mt-6">V. RESEARCH / PUBLICATIONS / AWARDS</h2>

                <div
                    class="bg-white border border-gray-300 rounded-lg shadow-sm p-6 text-[13px] text-gray-800 leading-snug">

                    <!-- a. R&D Involvement -->
                    <div class="mb-6">
                        <label class="block text-[13px] font-semibold mb-2">
                            a. Research & Development (R&D) Involvement (Last 5 Years)
                        </label>

                        <table class="w-full border border-gray-300 mb-2 text-[13px]">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="border border-gray-300 px-2 py-1 text-left">Field and Title of Research
                                    </th>
                                    <th class="border border-gray-300 px-2 py-1 text-left">Location / Duration</th>
                                    <th class="border border-gray-300 px-2 py-1 text-left">Fund Source</th>
                                    <th class="border border-gray-300 px-2 py-1 text-left">Nature of Involvement</th>
                                </tr>
                            </thead>
                            <tbody id="researchBody">
                                <tr>
                                    <td class="border border-gray-300 px-2 py-1">
                                        <input type="text" name="research_involvements[0][field_title]"
                                            class="form-input w-full border-gray-300 rounded" />
                                    </td>
                                    <td class="border border-gray-300 px-2 py-1">
                                        <input type="text" name="research_involvements[0][location_duration]"
                                            class="form-input w-full border-gray-300 rounded" />
                                    </td>
                                    <td class="border border-gray-300 px-2 py-1">
                                        <input type="text" name="research_involvements[0][fund_source]"
                                            class="form-input w-full border-gray-300 rounded" />
                                    </td>
                                    <td class="border border-gray-300 px-2 py-1">
                                        <input type="text" name="research_involvements[0][nature_of_involvement]"
                                            class="form-input w-full border-gray-300 rounded" />
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <button type="button"
                            onclick="addRow('researchBody', ['field_title', 'location_duration', 'fund_source', 'nature_of_involvement'], 'research_involvements')"
                            class="bg-blue-100 hover:bg-blue-200 text-blue-700 text-sm px-3 py-1 rounded">
                            + Add Row
                        </button>
                    </div>

                    <!-- b. Publications -->
                    <div class="mb-6">
                        <label class="block text-[13px] font-semibold mb-2">
                            b. Publications (Last 5 Years)
                        </label>

                        <table class="w-full border border-gray-300 mb-2 text-[13px]">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="border border-gray-300 px-2 py-1 text-left">Title of Article</th>
                                    <th class="border border-gray-300 px-2 py-1 text-left">Name / Year of Publication
                                    </th>
                                    <th class="border border-gray-300 px-2 py-1 text-left">Nature of Involvement</th>
                                </tr>
                            </thead>
                            <tbody id="pubBody">
                                <tr>
                                    <td class="border border-gray-300 px-2 py-1">
                                        <input type="text" name="publications[0][title]"
                                            class="form-input w-full border-gray-300 rounded" />
                                    </td>
                                    <td class="border border-gray-300 px-2 py-1">
                                        <input type="text" name="publications[0][name_year]"
                                            class="form-input w-full border-gray-300 rounded" />
                                    </td>
                                    <td class="border border-gray-300 px-2 py-1">
                                        <input type="text" name="publications[0][nature_of_involvement]"
                                            class="form-input w-full border-gray-300 rounded" />
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <button type="button"
                            onclick="addRow('pubBody', ['title', 'name_year', 'nature_of_involvement'], 'publications')"
                            class="bg-blue-100 hover:bg-blue-200 text-blue-700 text-sm px-3 py-1 rounded">
                            + Add Row
                        </button>
                    </div>

                    <!-- c. Awards -->
                    <div>
                        <label class="block text-[13px] font-semibold mb-2">
                            c. Awards / Recognitions Received
                        </label>

                        <table class="w-full border border-gray-300 mb-2 text-[13px]">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="border border-gray-300 px-2 py-1 text-left">Title of Award</th>
                                    <th class="border border-gray-300 px-2 py-1 text-left">Award Giving Body</th>
                                    <th class="border border-gray-300 px-2 py-1 text-left">Year</th>
                                </tr>
                            </thead>
                            <tbody id="awardBody">
                                <tr>
                                    <td class="border border-gray-300 px-2 py-1">
                                        <input type="text" name="awards[0][title]"
                                            class="form-input w-full border-gray-300 rounded" />
                                    </td>
                                    <td class="border border-gray-300 px-2 py-1">
                                        <input type="text" name="awards[0][giving_body]"
                                            class="form-input w-full border-gray-300 rounded" />
                                    </td>
                                    <td class="border border-gray-300 px-2 py-1">
                                        <input type="text" name="awards[0][year]"
                                            class="form-input w-full border-gray-300 rounded" />
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <button type="button"
                            onclick="addRow('awardBody', ['title', 'giving_body', 'year'], 'awards')"
                            class="bg-blue-100 hover:bg-blue-200 text-blue-700 text-sm px-3 py-1 rounded">
                            + Add Row
                        </button>
                    </div>
                </div>

                <!-- VI. ATTACHED DOCUMENTS -->
                <h2 class="section-title mt-6">VI. ATTACHED DOCUMENTS</h2>

                <div
                    class="bg-white border border-gray-300 rounded-lg shadow-sm p-6 text-[13px] leading-snug text-gray-800">

                    <p class="mb-4 font-semibold">
                        Please attach clear scanned copies of the following documents
                        <span class="font-normal">(PDF format only, max 20 MB each):</span>
                    </p>

                    <!-- General Requirements -->
                    <div class="mb-6">
                        <h3 class="font-semibold text-[13px] mb-2">General Requirements</h3>

                        <div class="space-y-3 pl-3">
                            <div>
                                <label class="block text-[13px]">• Birth Certificate (Photocopy)</label>
                                <input type="file" name="birth_certificate" accept="application/pdf"
                                    class="mt-1 w-full border border-gray-300 rounded-md p-1.5 text-sm text-gray-700">
                            </div>

                            <div>
                                <label class="block text-[13px]">• Certified True Copy of the Official Transcript of
                                    Record</label>
                                <input type="file" name="transcript_record" accept="application/pdf"
                                    class="mt-1 w-full border border-gray-300 rounded-md p-1.5 text-sm text-gray-700">
                            </div>

                            <div>
                                <label class="block text-[13px]">• Endorsement 1 – Former professor in college (for MS
                                    applicant) / Former professor in MS program (for PhD applicant)</label>
                                <input type="file" name="endorsement_1" accept="application/pdf"
                                    class="mt-1 w-full border border-gray-300 rounded-md p-1.5 text-sm text-gray-700">
                            </div>

                            <div>
                                <label class="block text-[13px]">• Endorsement 2 – Former professor in college (for MS
                                    applicant) / Former professor in MS program (for PhD applicant)</label>
                                <input type="file" name="endorsement_2" accept="application/pdf"
                                    class="mt-1 w-full border border-gray-300 rounded-md p-1.5 text-sm text-gray-700">
                            </div>
                        </div>
                    </div>

                    <!-- If Employed -->
                    <div class="mb-6">
                        <h3 class="font-semibold text-[13px] mb-2">If Employed:</h3>

                        <div class="space-y-3 pl-3">
                            <div>
                                <label class="block text-[13px]">• Recommendation from Head of Agency</label>
                                <input type="file" name="recommendation_head_agency" accept="application/pdf"
                                    class="mt-1 w-full border border-gray-300 rounded-md p-1.5 text-sm text-gray-700">
                            </div>

                            <div>
                                <label class="block text-[13px]">• Form 2A – Certificate of Employment and Permit to
                                    Study</label>
                                <input type="file" name="form_2a" accept="application/pdf"
                                    class="mt-1 w-full border border-gray-300 rounded-md p-1.5 text-sm text-gray-700">
                            </div>

                            <div>
                                <label class="block text-[13px]">• Form 2B – Certificate of DepEd Employment and Permit
                                    to Study (for DepEd employees only)</label>
                                <input type="file" name="form_2b" accept="application/pdf"
                                    class="mt-1 w-full border border-gray-300 rounded-md p-1.5 text-sm text-gray-700">
                            </div>
                        </div>
                    </div>

                    <!-- Other Requirements -->
                    <div class="mb-6">
                        <h3 class="font-semibold text-[13px] mb-2">Other Requirements:</h3>

                        <div class="space-y-3 pl-3">
                            <div>
                                <label class="block text-[13px]">• Form C – Certification of Health Status</label>
                                <input type="file" name="form_c_health_status" accept="application/pdf"
                                    class="mt-1 w-full border border-gray-300 rounded-md p-1.5 text-sm text-gray-700">
                            </div>

                            <div>
                                <label class="block text-[13px]">• Valid NBI Clearance</label>
                                <input type="file" name="nbi_clearance" accept="application/pdf"
                                    class="mt-1 w-full border border-gray-300 rounded-md p-1.5 text-sm text-gray-700">
                            </div>

                            <div>
                                <label class="block text-[13px]">• Letter of Admission with Regular Status (includes
                                    Evaluation Sheet)</label>
                                <input type="file" name="letter_of_admission" accept="application/pdf"
                                    class="mt-1 w-full border border-gray-300 rounded-md p-1.5 text-sm text-gray-700">
                            </div>

                            <div>
                                <label class="block text-[13px]">• Approved Program of Study</label>
                                <input type="file" name="approved_program_study" accept="application/pdf"
                                    class="mt-1 w-full border border-gray-300 rounded-md p-1.5 text-sm text-gray-700">
                            </div>
                        </div>
                    </div>

                    <!-- Lateral Applicants -->
                    <div>
                        <h3 class="font-semibold text-[13px] mb-2">Additional Requirements for Lateral Applicants:</h3>

                        <div class="pl-3 space-y-3">
                            <div>
                                <label class="block text-[13px]">• Certification from the university
                                    indicating:</label>
                                <ul class="list-disc pl-6 text-[12px] text-gray-700 mt-1">
                                    <li>Number of graduate units required in the program</li>
                                    <li>Number of graduate units already earned with corresponding grades</li>
                                </ul>
                                <input type="file" name="lateral_certification" accept="application/pdf"
                                    class="mt-2 w-full border border-gray-300 rounded-md p-1.5 text-sm text-gray-700">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- VIII. TRUTHFULNESS OF DATA AND DATA PRIVACY -->
                <h2 class="section-title mt-6">VIII. TRUTHFULNESS OF DATA AND DATA PRIVACY</h2>

                <div
                    class="bg-white border border-gray-300 rounded-lg shadow-sm p-6 text-[13px] leading-snug text-gray-800">

                    <p class="mb-3 text-justify">
                        I hereby certify that all information given above are true and correct to the best of my
                        knowledge.
                        Any misinformation or withholding of information will automatically disqualify me from the
                        program,
                        Project Science and Technology Regional Alliance of Universities for National Development
                        (STRAND).
                        I am willing to refund all the financial benefits received plus appropriate interest if such
                        misinformation is discovered.
                    </p>

                    <p class="mb-4 text-justify">
                        Moreover, I hereby authorize the Science Education Institute of the Department of Science and
                        Technology (SEI-DOST)
                        to collect, record, organize, update or modify, retrieve, consult, use, consolidate, block,
                        erase, or destruct my personal
                        data that I have provided in relation to my application to this scholarship. I hereby affirm my
                        right to be informed,
                        object to processing, access and rectify, suspend or withdraw my personal data, and be
                        indemnified in case of damages
                        pursuant to the provisions of Republic Act No. 10173 (Data Privacy Act of 2012) and its
                        corresponding Implementing Rules and Regulations.
                    </p>

                    <!-- Applicant Details -->
                    <div class="grid grid-cols-2 gap-6 mt-6">
                        <!-- Applicant Name -->
                        <div>
                            <label class="block text-[12px] font-semibold mb-1">Applicant Name</label>
                            <div class="editable-field border border-gray-300 rounded px-2 py-1 min-h-[28px]"
                                contenteditable="true" data-field="applicant_name">
                                {{ trim(($user->first_name ?? '') . ' ' . ($user->middle_name ?? '') . ' ' . ($user->last_name ?? '')) }}
                            </div>
                        </div>

                        <!-- Applicant Signature -->
                        <div>
                            <label class="block text-[12px] font-semibold mb-1">Applicant Signature</label>
                            <canvas id="applicantSignature"
                                class="border border-gray-300 rounded w-full h-24 bg-gray-50"
                                style="touch-action: none;"></canvas>
                            <div class="flex justify-between items-center mt-2">
                                <button type="button" id="clearSignature"
                                    class="px-3 py-1 text-sm bg-red-500 hover:bg-red-600 text-white rounded shadow">
                                    Clear
                                </button>
                                <span class="text-[11px] text-gray-500 italic">Sign inside the box above</span>
                            </div>
                            <input type="hidden" name="applicant_signature" id="applicantSignatureInput">
                        </div>
                    </div>

                    <!-- Agreement Checkbox -->
                    <div class="mt-6 flex items-start gap-2">
                        <input type="checkbox" name="terms_and_conditions_agreed" required
                            class="mt-0.5 h-4 w-4 border-gray-400 text-blue-600 focus:ring-blue-500">
                        <label class="text-[12px] leading-tight">
                            I agree to the Terms, Conditions, and Data Privacy Policy.
                        </label>
                    </div>
                </div>

                <!-- =================== SUBMIT BUTTON =================== -->
                <div class="text-right mt-6">
                    <button type="submit"
                        <a href="{{ route('applicant.dashboard') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2 rounded-md shadow-sm">
                        Submit Application
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ✅ JS: PHOTO PREVIEW -->
    <script>
        function previewPhoto(event) {
            const file = event.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('photoPreview');
                preview.innerHTML = `<img src="${e.target.result}" alt="Preview" class="object-cover w-full h-full">`;
            };
            reader.readAsDataURL(file);
        }
    </script>

    <!-- ✅ JS: ADD ROW FUNCTION -->
    <script>
        function addRow(tbodyId, fields, groupName) {
            const tbody = document.getElementById(tbodyId);
            if (!tbody) return;

            const rowCount = tbody.querySelectorAll('tr').length;
            const newRow = document.createElement('tr');

            fields.forEach(field => {
                const td = document.createElement('td');
                td.className = 'border border-gray-300 px-2 py-1';
                td.innerHTML = `
            <input type="text" 
                   name="${groupName}[${rowCount}][${field}]" 
                   class="form-input w-full border-gray-300 rounded text-[12px]" />
        `;
                newRow.appendChild(td);
            });

            tbody.appendChild(newRow);
        }
    </script>

    <!-- ✅ JS: AGE AUTO-CALCULATE -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dobInput = document.getElementById('dob');
            const ageInput = document.getElementById('age');

            if (dobInput && ageInput) {
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
            }
        });
    </script>

    <!-- ✅ JS: SIGNATURE PAD -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const canvas = document.getElementById('applicantSignature');
            const clearBtn = document.getElementById('clearSignature');
            const hiddenInput = document.getElementById('applicantSignatureInput');

            if (!canvas || !hiddenInput) return;

            const ctx = canvas.getContext('2d');
            let drawing = false;

            function getPos(e) {
                const rect = canvas.getBoundingClientRect();
                if (e.touches) {
                    return {
                        x: e.touches[0].clientX - rect.left,
                        y: e.touches[0].clientY - rect.top
                    };
                }
                return {
                    x: e.clientX - rect.left,
                    y: e.clientY - rect.top
                };
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
                hiddenInput.value = canvas.toDataURL();
            }

            canvas.addEventListener('mousedown', () => drawing = true);
            canvas.addEventListener('mouseup', () => {
                drawing = false;
                ctx.beginPath();
            });
            canvas.addEventListener('mousemove', draw);

            canvas.addEventListener('touchstart', (e) => {
                drawing = true;
                draw(e);
                e.preventDefault();
            });
            canvas.addEventListener('touchend', () => {
                drawing = false;
                ctx.beginPath();
            });
            canvas.addEventListener('touchmove', draw);

            if (clearBtn) {
                clearBtn.addEventListener('click', () => {
                    ctx.clearRect(0, 0, canvas.width, canvas.height);
                    ctx.beginPath();
                    hiddenInput.value = '';
                });
            }
        });
    </script>

    <!-- ✅ JS: PSGC LOCATION AUTO-FILL -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const provinceSelect = document.getElementById('province_select');
            const citySelect = document.getElementById('city_select');
            const barangaySelect = document.getElementById('barangay_select');
            const regionInput = document.getElementById('region_select');
            const zipInput = document.getElementById('zip_code');

            if (!provinceSelect || !citySelect || !barangaySelect) return;

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
                        if (regionInput) regionInput.value = region.name;
                        if (zipInput) zipInput.value = prov.zipcode || regionZipFallback[region.code] || "";
                    }
                    if (level === "cities-municipalities") {
                        const city = await fetch(`https://psgc.gitlab.io/api/cities-municipalities/${code}/`)
                            .then(r => r.json());
                        const prov = await fetch(`https://psgc.gitlab.io/api/provinces/${city.provinceCode}/`)
                            .then(r => r.json());
                        const region = await fetch(`https://psgc.gitlab.io/api/regions/${prov.regionCode}/`)
                            .then(r => r.json());
                        if (regionInput) regionInput.value = region.name;
                        if (zipInput) zipInput.value = city.zipcode || prov.zipcode || regionZipFallback[region
                            .code] || "";
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
                        if (regionInput) regionInput.value = region.name;
                        if (zipInput) zipInput.value = brgy.zipcode || city.zipcode || prov.zipcode ||
                            regionZipFallback[region.code] || "";
                    }
                } catch (err) {
                    console.error("Location error:", err);
                }
            }

            // Load provinces
            fetch('https://psgc.gitlab.io/api/provinces/')
                .then(res => res.json())
                .then(data => data.forEach(p => provinceSelect.add(new Option(p.name, p.code))));

            // Province → Cities
            provinceSelect.addEventListener('change', function() {
                const provCode = this.value;
                citySelect.innerHTML = '<option value="">Select City / Municipality</option>';
                barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
                if (!provCode) return;

                fetch(`https://psgc.gitlab.io/api/provinces/${provCode}/cities-municipalities/`)
                    .then(res => res.json())
                    .then(data => data.forEach(c => citySelect.add(new Option(c.name, c.code))));

                setLocation("provinces", provCode);
            });

            // City → Barangays
            citySelect.addEventListener('change', function() {
                const cityCode = this.value;
                barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
                if (!cityCode) return;

                setLocation("cities-municipalities", cityCode);

                fetch(`https://psgc.gitlab.io/api/cities-municipalities/${cityCode}/barangays/`)
                    .then(res => res.json())
                    .then(data => data.forEach(b => barangaySelect.add(new Option(b.name, b.code))));
            });

            // Barangay → Region & ZIP
            barangaySelect.addEventListener('change', function() {
                const brgyCode = this.value;
                if (!brgyCode) return;
                setLocation("barangays", brgyCode);
            });
        });
    </script>

    <!-- ✅ JS: FORM SUBMIT (handles success message) -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('applicationForm');
            if (!form) return;

            form.addEventListener('submit', function(e) {
                e.preventDefault(); // Stop auto reload

                const formData = new FormData(form);

                fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => {
                        if (response.ok) {
                            const toast = document.getElementById('successToast');
                            if (toast) {
                                toast.classList.remove('hidden');
                                toast.classList.add('opacity-100');
                                setTimeout(() => toast.classList.add('hidden'), 3000);
                            }

                            form.reset();

                            const preview = document.getElementById('photoPreview');
                            if (preview) preview.innerHTML = `<span>Click to Upload<br>Photo</span>`;
                        } else {
                            console.error('Form submission failed:', response.statusText);
                        }
                    })
                    .catch(err => console.error('Error submitting form:', err));
            });
        });
    </script>
</x-app-layout>
