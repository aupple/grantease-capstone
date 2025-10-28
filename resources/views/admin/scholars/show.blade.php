@extends('layouts.admin-layout')

@section('content')
    @php
        if (!function_exists('formatValue')) {
            function formatValue($value)
            {
                // Collections -> arrays
                if ($value instanceof \Illuminate\Support\Collection) {
                    $value = $value->toArray();
                }

                // Nested arrays -> flatten + implode
                if (is_array($value)) {
                    $flat = \Illuminate\Support\Arr::flatten($value);
                    return implode(', ', $flat) ?: 'N/A';
                }

                // JSON strings -> decode -> flatten -> implode
                if (is_string($value)) {
                    $decoded = json_decode($value, true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                        $flat = \Illuminate\Support\Arr::flatten($decoded);
                        return implode(', ', $flat) ?: 'N/A';
                    }
                    return $value === '' ? 'N/A' : $value;
                }

                // Objects -> cast if possible, otherwise json
                if (is_object($value)) {
                    return method_exists($value, '__toString') ? (string) $value : (json_encode($value) ?: 'N/A');
                }

                return $value ?: 'N/A';
            }
        }

        if (!function_exists('getLocationName')) {
            function getLocationName($code, $type = 'city')
            {
                $jsonUrls = [
                    'province' => 'https://psgc.gitlab.io/api/provinces/',
                    'city' => 'https://psgc.gitlab.io/api/cities-municipalities/',
                    'barangay' => 'https://psgc.gitlab.io/api/barangays/',
                    'district' => 'https://psgc.gitlab.io/api/districts/',
                ];

                // ✅ Make sure type is valid
                if (!isset($jsonUrls[$type])) {
                    return 'Unknown';
                }

                $cacheFile = storage_path("app/psgc_$type.json");
                $data = null;

                // ✅ Use cache if available
                if (file_exists($cacheFile)) {
                    $data = json_decode(file_get_contents($cacheFile), true);
                }

                // ✅ If cache is missing/invalid, try fetching from API
                if (empty($data)) {
                    try {
                        $json = file_get_contents($jsonUrls[$type]);
                        $data = json_decode($json, true);

                        // Only cache if valid JSON
                        if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {
                            file_put_contents($cacheFile, json_encode($data));
                        }
                    } catch (\Exception $e) {
                        return 'Unknown';
                    }
                }

                // ✅ Find code in data
                if (is_array($data)) {
                    foreach ($data as $item) {
                        if (isset($item['code']) && $item['code'] == $code) {
                            return $item['name'];
                        }
                    }
                }

                return 'Unknown';
            }
        }
    @endphp


    <div class="mb-6 relative">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-semibold text-gray-800 leading-tight">
                    Scholar #{{ $scholar->id }}
                </h2>
                <p class="text-sm text-gray-500">
                    Approved on {{ $scholar->updated_at->format('F d, Y') }}
                </p>
            </div>

            <div id="actionButtons" class="hidden flex gap-2">
                <form action="{{ route('admin.scholars.show', $scholar->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="approved">
                    <button type="submit"
                        class="bg-green-600 text-white text-sm px-4 py-1.5 rounded-md hover:bg-green-700 transition font-semibold">
                        Approve
                    </button>
                </form>
                <form action="{{ route('admin.scholars.show', $scholar->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="rejected">
                    <button type="submit"
                        class="bg-red-600 text-white text-sm px-4 py-1.5 rounded-md hover:bg-red-700 transition font-semibold">
                        Reject
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- LEFT SIDE -->
        <div x-data="{ sectionIndex: 0 }" class="col-span-2 space-y-6">

            <!-- ✅ STEP 0: Personal Information -->
            <div x-show="sectionIndex === 0"
                class="transition-all duration-300 bg-white/30 backdrop-blur-md border border-white/20 shadow-md rounded-2xl p-6">
                <div class="flex items-center gap-2 mb-6">
                    <svg class="w-6 h-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5.121 17.804A10.97 10.97 0 0112 15c2.45 0 4.712.755 6.559 2.028M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <h3 class="text-xl font-bold text-[#1e33a3]">Personal Information</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 text-sm text-gray-800">
                    <!-- Full Name -->
                    <div>
                        <p class="font-semibold text-gray-600">Full Name</p>
                        <p class="font-semibold">
                            {{ $scholar->applicationForm->user->full_name ??
                                ($scholar->applicationForm->user->first_name . ' ' . $scholar->applicationForm->user->last_name ?? 'N/A') }}
                        </p>
                    </div>

                    <!-- Email -->
                    <div>
                        <p class="font-semibold text-gray-600">Email</p>
                        <p class="font-semibold">
                            {{ $scholar->applicationForm->email_address ?? ($scholar->applicationForm->user->email ?? 'N/A') }}
                        </p>
                    </div>

                    <!-- Telephone -->
                    <div>
                        <p class="font-semibold text-gray-600">Telephone Nos.</p>
                        <p class="font-semibold">{{ $scholar->applicationForm->telephone_nos ?? 'N/A' }}</p>
                    </div>

                    <!-- Sex -->
                    <div>
                        <p class="font-semibold text-gray-600">Sex</p>
                        <p class="font-semibold">{{ $scholar->applicationForm->sex ?? 'N/A' }}</p>
                    </div>

                    <!-- Birthdate -->
                    <div>
                        <p class="font-semibold text-gray-600">Birthdate</p>
                        <p class="font-semibold">{{ $scholar->applicationForm->date_of_birth ?? 'N/A' }}</p>
                    </div>

                    <!-- Age -->
                    <div>
                        <p class="font-semibold text-gray-600">Age</p>
                        <p class="font-semibold">{{ $scholar->applicationForm->age ?? 'N/A' }}</p>
                    </div>

                    <!-- Civil Status -->
                    <div>
                        <p class="font-semibold text-gray-600">Civil Status</p>
                        <p class="font-semibold">{{ $scholar->applicationForm->civil_status ?? 'N/A' }}</p>
                    </div>

                    <!-- Address fields -->
                    <div>
                        <p class="font-semibold text-gray-600">Province</p>
                        <p class="font-semibold">{{ getLocationName($scholar->applicationForm->province, 'province') }}
                        </p>
                    </div>

                    <div>
                        <p class="font-semibold text-gray-600">City / Municipality</p>
                        <p class="font-semibold">{{ getLocationName($scholar->applicationForm->city, 'city') }}</p>
                    </div>

                    <div>
                        <p class="font-semibold text-gray-600">Barangay</p>
                        <p class="font-semibold">{{ getLocationName($scholar->applicationForm->barangay, 'barangay') }}
                        </p>
                    </div>

                    <div>
                        <p class="font-semibold text-gray-600">Street</p>
                        <p class="font-semibold">{{ $scholar->applicationForm->address_street ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <p class="font-semibold text-gray-600">House No.</p>
                        <p class="font-semibold">{{ $scholar->applicationForm->address_no ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <p class="font-semibold text-gray-600">Region</p>
                        <p class="font-semibold">{{ $scholar->applicationForm->region ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <p class="font-semibold text-gray-600">District</p>
                        <p class="font-semibold">{{ $scholar->applicationForm->district ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <p class="font-semibold text-gray-600">Zip Code</p>
                        <p class="font-semibold">{{ $scholar->applicationForm->zip_code ?? 'N/A' }}</p>
                    </div>

                    <!-- Passport -->
                    <div>
                        <p class="font-semibold text-gray-600">Passport No.</p>
                        <p class="font-semibold">{{ $scholar->applicationForm->passport_no ?? 'N/A' }}</p>
                    </div>

                    <!-- Mailing -->
                    <div class="md:col-span-2">
                        <p class="font-semibold text-gray-600">Current Mailing Address</p>
                        <p class="font-semibold">{{ $scholar->applicationForm->current_mailing_address ?? 'N/A' }}</p>
                    </div>

                    <!-- Parents -->
                    <div>
                        <p class="font-semibold text-gray-600">Father's Name</p>
                        <p class="font-semibold">{{ $scholar->applicationForm->father_name ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <p class="font-semibold text-gray-600">Mother's Name</p>
                        <p class="font-semibold">{{ $scholar->applicationForm->mother_name ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
            <!-- ✅ END STEP 0 -->

            <!-- ✅ STEP 1: Academic Background -->
            <div x-show="sectionIndex === 1"
                class="transition-all duration-300 bg-white/30 backdrop-blur-md border border-white/20 shadow-md rounded-2xl p-6">
                <div class="flex items-center gap-2 mb-6">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 14L21 9l-9-5-9 5 9 5zm0 0v6m0-6L3 9m18 0v6" />
                    </svg>
                    <h3 class="text-xl font-bold text-[#1e33a3]">Academic Background</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 text-sm text-gray-800">
                    <div>
                        <p class="font-semibold text-gray-600">Program Applied</p>
                        <p class="font-semibold">
                            {{ formatValue($scholar->applicationForm->program) }}
                        </p>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-600">School Term</p>
                        <p class="font-semibold">
                            {{ formatValue($scholar->applicationForm->school_term) }}
                        </p>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-600">Year Level</p>
                        <p class="font-semibold">
                            {{ formatValue($scholar->applicationForm->academic_year) }}
                        </p>
                    </div>
                </div>

                <hr class="my-4 border-dashed">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 text-sm text-gray-800">
                    @foreach ([
            'BS Field' => 'bs_field',
            'BS School' => 'bs_university',
            'BS Scholarship' => 'bs_scholarship_type',
            'BS Remarks' => 'bs_remarks',
            'MS Field' => 'ms_field',
            'MS School' => 'ms_university',
            'MS Scholarship' => 'ms_scholarship_type',
            'MS Remarks' => 'ms_remarks',
            'PhD Field' => 'phd_field',
            'PhD School' => 'phd_university',
            'PhD Scholarship' => 'phd_scholarship_type',
            'PhD Remarks' => 'phd_remarks',
            'Strand Category' => 'strand_category',
            'Scholarship Type' => 'scholarship_type',
            'New University' => 'new_applicant_university',
            'New Course' => 'new_applicant_course',
            'Lateral University' => 'lateral_university_enrolled',
            'Lateral Course' => 'lateral_course_degree',
            'Units Earned' => 'lateral_units_earned',
            'Units Remaining' => 'lateral_remaining_units',
            'Research Title' => 'research_title',
            'Research Approved' => 'research_topic_approved',
            'Last Enrollment Date' => 'last_enrollment_date',
        ] as $label => $field)
                        @php
                            $value = $scholar->applicationForm->$field ?? null;
                        @endphp
                        @if (!empty($value))
                            <div>
                                <p class="font-semibold text-gray-600">{{ $label }}</p>
                                <p class="font-semibold">{{ formatValue($value) }}</p>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
            <!-- ✅ END STEP 1 -->
            <!-- ✅ STEP 2: Employment -->
            <div x-show="sectionIndex === 2"
                class="transition-all duration-300 bg-white/30 backdrop-blur-md border border-white/20 shadow-md rounded-2xl p-6">
                <div class="flex items-center gap-2 mb-6">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 11c0-1.657-1.343-3-3-3H7a3 3 0 00-3 3v5h12v-5z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 13v6M8 13v6m4-6v6" />
                    </svg>
                    <h3 class="text-xl font-bold text-[#1e33a3]">Employment</h3>
                </div>

                <!-- Employment Status -->
                <div class="mb-4">
                    <p class="font-semibold text-gray-600">Employment Status</p>
                    <p class="font-semibold">{{ $scholar->applicationForm->employment_status ?? 'N/A' }}</p>
                </div>

                @if (in_array($scholar->applicationForm->employment_status, ['Permanent', 'Contractual', 'Probationary']))
                    <!-- Company-related fields -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 text-sm text-gray-800">
                        <div>
                            <p class="font-semibold text-gray-600">Position</p>
                            <p class="font-semibold">{{ $scholar->applicationForm->employed_position ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-600">Length of Service</p>
                            <p class="font-semibold">
                                {{ $scholar->applicationForm->employed_length_of_service ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-600">Company Name</p>
                            <p class="font-semibold">{{ $scholar->applicationForm->employed_company_name ?? 'N/A' }}
                            </p>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-600">Company Address</p>
                            <p class="font-semibold">
                                {{ $scholar->applicationForm->employed_company_address ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-600">Company Email</p>
                            <p class="font-semibold">{{ $scholar->applicationForm->employed_email ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-600">Company Website</p>
                            <p class="font-semibold">{{ $scholar->applicationForm->employed_website ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-600">Company Telephone</p>
                            <p class="font-semibold">{{ $scholar->applicationForm->employed_telephone ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-600">Company Fax</p>
                            <p class="font-semibold">{{ $scholar->applicationForm->employed_fax ?? 'N/A' }}</p>
                        </div>
                    </div>
                @elseif($scholar->applicationForm->employment_status === 'Self-employed')
                    <!-- Business-related fields -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 text-sm text-gray-800">
                        <div>
                            <p class="font-semibold text-gray-600">Business Name</p>
                            <p class="font-semibold">
                                {{ $scholar->applicationForm->self_employed_business_name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-600">Business Address</p>
                            <p class="font-semibold">{{ $scholar->applicationForm->self_employed_address ?? 'N/A' }}
                            </p>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-600">Business Email/Website</p>
                            <p class="font-semibold">
                                {{ $scholar->applicationForm->self_employed_email_website ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-600">Business Telephone</p>
                            <p class="font-semibold">{{ $scholar->applicationForm->self_employed_telephone ?? 'N/A' }}
                            </p>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-600">Business Fax</p>
                            <p class="font-semibold">{{ $scholar->applicationForm->self_employed_fax ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-600">Type of Business</p>
                            <p class="font-semibold">
                                {{ $scholar->applicationForm->self_employed_type_of_business ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-600">Years of Operation</p>
                            <p class="font-semibold">
                                {{ $scholar->applicationForm->self_employed_years_of_operation ?? 'N/A' }}</p>
                        </div>
                    </div>
                @elseif($scholar->applicationForm->employment_status === 'Unemployed')
                    <p class="text-gray-600 italic">The scholar is currently unemployed.</p>
                @endif

                <!-- Research and career plans -->
                <div class="mt-6">
                    <p class="font-semibold text-gray-600">Research Plans Summary</p>
                    <p class="font-semibold">{{ $scholar->applicationForm->research_plans ?? 'N/A' }}</p>
                </div>
                <div class="mt-4">
                    <p class="font-semibold text-gray-600">Career Plans Summary</p>
                    <p class="font-semibold">{{ $scholar->applicationForm->career_plans ?? 'N/A' }}</p>
                </div>
            </div>
            <!-- ✅ END STEP 2 -->

            <!-- ✅ STEP 3: Future Plans -->
            <div x-show="sectionIndex === 3"
                class="transition-all duration-300 bg-white/30 backdrop-blur-md border border-white/20 shadow-md rounded-2xl p-6">
                <div class="flex items-center gap-2 mb-6">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 17l6-6 4 4 8-8" />
                    </svg>
                    <h3 class="text-xl font-bold text-[#1e33a3]">Future Plans</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 text-sm text-gray-800">
                    @foreach ([
            'Career Plans' => 'career_plans',
            'Research Plans' => 'research_plans',
            'R&D Involvement' => 'rnd_involvement',
            'Publications' => 'publications',
            'Awards' => 'awards',
        ] as $label => $field)
                        <div>
                            <p class="font-semibold text-gray-600">{{ $label }}</p>
                            <p class="font-semibold">{{ formatValue($scholar->applicationForm->$field) }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
            <!-- ✅ END STEP 3 -->

            <!-- Declaration / Data Privacy -->
            <div x-show="sectionIndex === 4"
                class="transition-all duration-300 bg-white/30 backdrop-blur-md border border-white/20 shadow-md rounded-2xl p-6">
                <div class="flex items-center gap-2 mb-6">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    <h3 class="text-xl font-bold text-[#1e33a3]">Declaration & Data Privacy</h3>
                </div>

                <div class="text-sm text-gray-800 space-y-4">
                    <div>
                        <p class="font-semibold text-gray-600">Agreement to Terms & Privacy Policy:</p>
                        <p>{{ $scholar->applicationForm->terms_and_conditions_agreed ? 'Agreed' : 'Not Agreed' }}</p>
                    </div>

                    <div>
                        <p class="font-semibold text-gray-600">Applicant Signature (Printed Name):</p>
                        <p>{{ $scholar->applicationForm->applicant_signature ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <p class="font-semibold text-gray-600">Date of Declaration:</p>
                        <p>{{ $scholar->applicationForm->declaration_date ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <div class="flex justify-between items-center px-6">
                <button x-show="sectionIndex > 0" @click="sectionIndex = Math.max(sectionIndex - 1, 0)"
                    class="px-4 py-2 bg-gray-400 border border-gray-300 text-sm font-semibold rounded hover:bg-gray-300 transition">
                    ← Back
                </button>

                <!-- Spacer to maintain center alignment when Back button is hidden -->
                <div x-show="sectionIndex === 0"></div>

                <div class="text-sm font-semibold text-gray-600">
                    Step <span x-text="sectionIndex + 1"></span> of 5
                </div>

                <button x-show="sectionIndex < 4" @click="sectionIndex = Math.min(sectionIndex + 1, 4)"
                    class="px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded hover:bg-blue-400 transition">
                    Next →
                </button>

                <!-- Spacer to maintain center alignment when Next button is hidden -->
                <div x-show="sectionIndex === 4"></div>
            </div>
        </div>

        <div class="col-span-1 space-y-6">
            <!-- 📑 Documents -->
            <div class="bg-white/30 backdrop-blur-md border border-white/20 shadow-md rounded-2xl p-6">
                <h3 class="text-lg font-bold mb-4">Documents</h3>

                @php
                    $documents = [
                        'Passport Picture' => $scholar->applicationForm->passport_picture ?? null,
                        'Birth Certificate' => $scholar->applicationForm->birth_certificate_pdf ?? null,
                        'Transcript of Record' => $scholar->applicationForm->transcript_of_record_pdf ?? null,
                        'Endorsement Letter 1' => $scholar->applicationForm->endorsement_1_pdf ?? null,
                        'Endorsement Letter 2' => $scholar->applicationForm->endorsement_2_pdf ?? null,
                        'Recommendation of Head of Agency' =>
                            $scholar->applicationForm->recommendation_head_agency_pdf ?? null,
                        'Form 2A - Certificate of Employment' => $scholar->applicationForm->form_2a_pdf ?? null,
                        'Form 2B - Optional Employment Cert.' => $scholar->applicationForm->form_2b_pdf ?? null,
                        'Form A - Research Plans' => $scholar->applicationForm->form_a_research_plans_pdf ?? null,
                        'Form B - Career Plans' => $scholar->applicationForm->form_b_career_plans_pdf ?? null,
                        'Form C - Health Status' => $scholar->applicationForm->form_c_health_status_pdf ?? null,
                        'NBI Clearance' => $scholar->applicationForm->nbi_clearance_pdf ?? null,
                        'Letter of Admission' => $scholar->applicationForm->letter_of_admission_pdf ?? null,
                        'Approved Program of Study' => $scholar->applicationForm->approved_program_of_study_pdf ?? null,
                        'Lateral Certification' => $scholar->applicationForm->lateral_certification_pdf ?? null,
                    ];

                    $documents = array_filter($documents, fn($file) => !empty($file));

                    if (!function_exists('getFileUrlFromValue')) {
                        function getFileUrlFromValue($file)
                        {
                            if (!$file) {
                                return null;
                            }

                            if ($file instanceof \Illuminate\Support\Collection) {
                                $file = $file->first();
                            }

                            if (is_string($file)) {
                                $decoded = json_decode($file, true);
                                if (json_last_error() === JSON_ERROR_NONE) {
                                    $file = is_array($decoded) ? (count($decoded) ? $decoded[0] : null) : $decoded;
                                }
                            }

                            if (is_array($file)) {
                                $file = count($file) ? $file[0] : null;
                            }

                            if (is_object($file)) {
                                if (isset($file->path)) {
                                    $file = $file->path;
                                } elseif (isset($file->filename)) {
                                    $file = $file->filename;
                                } else {
                                    $file = json_encode($file);
                                }
                            }

                            if (!$file) {
                                return null;
                            }

                            if (\Illuminate\Support\Str::startsWith($file, 'storage/')) {
                                return asset($file);
                            }

                            return asset('storage/' . ltrim($file, '/'));
                        }
                    }
                @endphp

                <!-- 👇 scrollable container -->
                <div class="max-h-80 overflow-y-auto pr-2 space-y-4">
                    @php
                        $application = $scholar->applicationForm;
                        $verifiedDocuments = $application->verified_documents
                            ? json_decode($application->verified_documents, true)
                            : [];
                    @endphp

                    @foreach ($documents as $label => $file)
                        @php
                            $url = getFileUrlFromValue($file);
                            // convert label to snake_case key matching stored JSON
                            $key = Str::snake(str_replace(' ', '_', strtolower($label)));
                            $isVerified = isset($verifiedDocuments[$key]) && $verifiedDocuments[$key];
                        @endphp

                        <div class="flex items-center justify-between border-b border-gray-200 pb-2">
                            <p class="text-sm font-medium w-1/2">{{ $label }}</p>

                            <div class="w-1/4 text-center">
                                @if ($url)
                                    <a href="{{ $url }}" target="_blank"
                                        class="text-blue-600 hover:underline text-sm font-semibold">View File</a>
                                @elseif ($file)
                                    <span class="text-sm text-gray-500">Unreadable</span>
                                @else
                                    <span class="text-sm text-gray-400">No file</span>
                                @endif
                            </div>

                            <div class="w-1/4 text-right">
                                @if ($file && $url)
                                    <label class="inline-flex items-center text-sm cursor-pointer">
                                        <input type="checkbox" class="peer hidden checkbox-tracker"
                                            {{ $isVerified ? 'checked' : '' }} disabled>
                                        <div
                                            class="w-2.5 h-2.5 rounded-full border border-gray-400 peer-checked:bg-green-500 peer-checked:border-green-500 transition">
                                        </div>
                                        <span
                                            class="ml-2 text-xs text-gray-600 peer-checked:text-green-600 font-semibold">Verified</span>
                                    </label>
                                @else
                                    <label class="inline-flex items-center text-sm opacity-50 cursor-not-allowed">
                                        <input type="checkbox" disabled class="hidden">
                                        <div class="w-2.5 h-2.5 rounded-full border border-gray-300 bg-gray-200"></div>
                                        <span class="ml-2 text-xs text-gray-400 font-semibold">No file</span>
                                    </label>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <!-- End of Documents -->

            <!-- Application Info -->
            <div class="bg-white/30 backdrop-blur-md border border-white/20 shadow-md rounded-2xl p-6">
                <h3 class="text-lg font-bold mb-3">Application Info</h3>
                <div class="mb-3 flex items-center gap-3">
                    <strong class="text-sm">Status:</strong>
                    <span
                        class="px-3 py-1 rounded-full text-sm font-bold
                @if ($scholar->applicationForm->status === 'approved') bg-green-100 text-green-800
                @elseif ($scholar->applicationForm->status === 'rejected') bg-red-100 text-red-800
                @elseif ($scholar->applicationForm->status === 'pending') bg-yellow-100 text-yellow-800
                @elseif ($scholar->applicationForm->status === 'document_verification') bg-purple-100 text-purple-800
                @elseif ($scholar->applicationForm->status === 'for_interview') bg-blue-100 text-blue-800
                @else bg-gray-100 text-gray-800 @endif">
                        {{ ucfirst(str_replace('_', ' ', $scholar->applicationForm->status)) }}
                    </span>
                </div>

                <form
                    action="{{ route('admin.applications.update-status', $scholar->applicationForm->application_form_id) }}"
                    method="POST" class="flex items-center gap-2">
                    @csrf
                    <strong class="text-sm">Remarks:</strong>
                    <input type="text" name="remarks" class="text-xs border px-3 py-1 rounded w-64"
                        placeholder="Type your message here..." value="{{ $scholar->applicationForm->remarks }}">
                    <button type="submit"
                        class="text-xs text-white bg-blue-500 hover:bg-blue-600 px-3 py-1 rounded transition">Send</button>
                </form>
            </div>
        </div>
    </div>
    </div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
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
