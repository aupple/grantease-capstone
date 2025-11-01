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
                    Application #{{ $application->application_form_id }}
                </h2>
                <p class="text-sm text-gray-500">Submitted on {{ $application->created_at->format('F d, Y') }}</p>
            </div>

            <div id="actionButtons" class="hidden flex gap-2">
                <form action="{{ route('admin.applications.update-status', $application->application_form_id) }}"
                    method="POST">
                    @csrf
                    <input type="hidden" name="status" value="approved">
                    <button type="submit"
                        class="bg-green-600 text-white text-sm px-4 py-1.5 rounded-md hover:bg-green-700 transition font-semibold">Approve</button>
                </form>
                <form action="{{ route('admin.applications.update-status', $application->application_form_id) }}"
                    method="POST">
                    @csrf
                    <input type="hidden" name="status" value="rejected">
                    <button type="submit"
                        class="bg-red-600 text-white text-sm px-4 py-1.5 rounded-md hover:bg-red-700 transition font-semibold">Reject</button>
                </form>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- LEFT SIDE -->
        <div x-data="{ sectionIndex: 0 }" class="col-span-2 space-y-6">

            <!-- Personal Information -->
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
                            {{ $application->user->full_name ??
                                ($application->user->first_name . ' ' . $application->user->last_name ?? 'N/A') }}
                        </p>
                    </div>

                    <!-- Email -->
                    <div>
                        <p class="font-semibold text-gray-600">Email</p>
                        <p class="font-semibold">{{ $application->email_address ?? ($application->user->email ?? 'N/A') }}
                        </p>
                    </div>

                    <!-- Telephone -->
                    <div>
                        <p class="font-semibold text-gray-600">Telephone Nos.</p>
                        <p class="font-semibold">{{ $application->telephone_nos ?? 'N/A' }}</p>
                    </div>

                    <!-- Sex -->
                    <div>
                        <p class="font-semibold text-gray-600">Sex</p>
                        <p class="font-semibold">{{ $application->sex ?? 'N/A' }}</p>
                    </div>

                    <!-- Birthdate -->
                    <div>
                        <p class="font-semibold text-gray-600">Birthdate</p>
                        <p class="font-semibold">{{ $application->date_of_birth ?? 'N/A' }}</p>
                    </div>

                    <!-- Age -->
                    <div>
                        <p class="font-semibold text-gray-600">Age</p>
                        <p class="font-semibold">{{ $application->age ?? 'N/A' }}</p>
                    </div>

                    <!-- Civil Status -->
                    <div>
                        <p class="font-semibold text-gray-600">Civil Status</p>
                        <p class="font-semibold">{{ $application->civil_status ?? 'N/A' }}</p>
                    </div>

                    <!-- Address fields -->
                    <div>
                        <p class="font-semibold text-gray-600">Province</p>
                        <p class="font-semibold">{{ getLocationName($application->province, 'province') }}</p>
                    </div>

                    <div>
                        <p class="font-semibold text-gray-600">City / Municipality</p>
                        <p class="font-semibold">{{ getLocationName($application->city, 'city') }}</p>
                    </div>

                    <div>
                        <p class="font-semibold text-gray-600">Barangay</p>
                        <p class="font-semibold">{{ getLocationName($application->barangay, 'barangay') }}</p>
                    </div>

                    <div>
                        <p class="font-semibold text-gray-600">Street</p>
                        <p class="font-semibold">{{ $application->address_street ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <p class="font-semibold text-gray-600">House No.</p>
                        <p class="font-semibold">{{ $application->address_no ?? 'address_no' }}</p>
                    </div>

                    <div>
                        <p class="font-semibold text-gray-600">Region</p>
                        <p class="font-semibold">{{ $application->region, 'region' ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <p class="font-semibold text-gray-600">District</p>
                        <p class="font-semibold">{{ $application->district, 'district' ?? 'N/A' }}</p>
                    </div>


                    <div>
                        <p class="font-semibold text-gray-600">Zip Code</p>
                        <p class="font-semibold">{{ $application->zip_code ?? 'N/A' }}</p>
                    </div>

                    <!-- Passport -->
                    <div>
                        <p class="font-semibold text-gray-600">Passport No.</p>
                        <p class="font-semibold">{{ $application->passport_no ?? 'N/A' }}</p>
                    </div>

                    <!-- Mailing -->
                    <div class="md:col-span-2">
                        <p class="font-semibold text-gray-600">Current Mailing Address</p>
                        <p class="font-semibold">{{ $application->current_mailing_address ?? 'N/A' }}</p>
                    </div>

                    <!-- Parents -->
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
                            {{ is_array($application->program) ? implode(', ', $application->program) : $application->program ?? 'N/A' }}
                        </p>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-600">School Term</p>
                        <p class="font-semibold">
                            {{ is_array($application->school_term) ? implode(', ', $application->school_term) : $application->school_term ?? 'N/A' }}
                        </p>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-600">Year Level</p>
                        <p class="font-semibold">
                            {{ is_array($application->academic_year) ? implode(', ', $application->academic_year) : $application->academic_year ?? 'N/A' }}
                        </p>
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
            'Last Thesis Date' => 'last_thesis_date',
        ] as $label => $field)
                        @if (!empty($application->$field))
                            <div>
                                <p class="font-semibold text-gray-600">{{ $label }}</p>
                                <p class="font-semibold">
                                    {{ is_array($application->$field) ? implode(', ', $application->$field) : $application->$field }}
                                </p>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
            <!-- End of Academic background -->


            <!-- Employment -->
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
                    <p class="font-semibold">{{ $application->employment_status ?? 'N/A' }}</p>
                </div>

                @if (in_array($application->employment_status, ['Permanent', 'Contractual', 'Probationary']))
                    <!-- Company-related fields -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 text-sm text-gray-800">
                        <div>
                            <p class="font-semibold text-gray-600">Position</p>
                            <p class="font-semibold">{{ $application->employed_position ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-600">Length of Service</p>
                            <p class="font-semibold">{{ $application->employed_length_of_service ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-600">Company Name</p>
                            <p class="font-semibold">{{ $application->employed_company_name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-600">Company Address</p>
                            <p class="font-semibold">{{ $application->employed_company_address ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-600">Company Email</p>
                            <p class="font-semibold">{{ $application->employed_email ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-600">Company Website</p>
                            <p class="font-semibold">{{ $application->employed_website ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-600">Company Telephone</p>
                            <p class="font-semibold">{{ $application->employed_telephone ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-600">Company Fax</p>
                            <p class="font-semibold">{{ $application->employed_fax ?? 'N/A' }}</p>
                        </div>
                    </div>
                @elseif($application->employment_status === 'Self-employed')
                    <!-- Business-related fields -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 text-sm text-gray-800">
                        <div>
                            <p class="font-semibold text-gray-600">Business Name</p>
                            <p class="font-semibold">{{ $application->self_employed_business_name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-600">Business Address</p>
                            <p class="font-semibold">{{ $application->self_employed_address ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-600">Business Email/Website</p>
                            <p class="font-semibold">{{ $application->self_employed_email_website ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-600">Business Telephone</p>
                            <p class="font-semibold">{{ $application->self_employed_telephone ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-600">Business Fax</p>
                            <p class="font-semibold">{{ $application->self_employed_fax ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-600">Type of Business</p>
                            <p class="font-semibold">{{ $application->self_employed_type_of_business ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-600">Years of Operation</p>
                            <p class="font-semibold">{{ $application->self_employed_years_of_operation ?? 'N/A' }}</p>
                        </div>
                    </div>
                @elseif($application->employment_status === 'Unemployed')
                    <!-- Just show unemployed -->
                    <p class="text-gray-600 italic">The applicant is currently unemployed.</p>
                @endif

                <!-- Always show research and career plans -->
                <div class="mt-6">
                    <p class="font-semibold text-gray-600">Research Plans Summary</p>
                    <p class="font-semibold">{{ $application->research_plans ?? 'N/A' }}</p>
                </div>
                <div class="mt-4">
                    <p class="font-semibold text-gray-600">Career Plans Summary</p>
                    <p class="font-semibold">{{ $application->career_plans ?? 'N/A' }}</p>
                </div>
            </div>


            <!-- Future Plans -->
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

                            @php
                                $value = $application->$field;

                                // Convert Collection -> array
                                if ($value instanceof \Illuminate\Support\Collection) {
                                    $value = $value->toArray();
                                }

                                // If nested array, flatten and implode
                                if (is_array($value)) {
                                    $value = \Illuminate\Support\Arr::flatten($value);
                                    $value = implode(', ', $value);
                                }
                                // If JSON string representing array, decode and implode
                                elseif (is_string($value)) {
                                    $decoded = json_decode($value, true);
                                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                        $decoded = \Illuminate\Support\Arr::flatten($decoded);
                                        $value = implode(', ', $decoded);
                                    }
                                }
                                // If object, try to cast or fallback to json
                                elseif (is_object($value)) {
                                    $value = method_exists($value, '__toString')
                                        ? (string) $value
                                        : json_encode($value);
                                }

                                // final fallback
                                if (empty($value)) {
                                    $value = 'N/A';
                                }
                            @endphp

                            <p class="font-semibold">{{ $value }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
            <!-- End of Future Plans -->


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
                        <p>{{ $application->terms_and_conditions_agreed ? 'Agreed' : 'Not Agreed' }}</p>
                    </div>

                    <div>
                        <p class="font-semibold text-gray-600">Applicant Signature (Printed Name):</p>
                        <p>{{ $application->applicant_signature ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <p class="font-semibold text-gray-600">Date of Declaration:</p>
                        <p>{{ $application->declaration_date ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <div class="flex justify-between items-center px-6">
                <button x-show="sectionIndex > 0" @click="sectionIndex = Math.max(sectionIndex - 1, 0)"
                    class="px-4 py-2 bg-gray-400 border border-gray-300 text-sm font-semibold rounded hover:bg-gray-300 transition">
                    ← Back
                </button>

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

        <!-- RIGHT SIDE -->
        <div class="col-span-1 space-y-6">

            <!-- 📑 Documents -->
            <div class="bg-white/30 backdrop-blur-md border border-white/20 shadow-md rounded-2xl p-6">
                <h3 class="text-lg font-bold mb-4">Documents</h3>

                @php
                    $documents = [
                        'Passport Picture' => $application->passport_picture ?? null,
                        'Birth Certificate' => $application->birth_certificate_pdf ?? null,
                        'Transcript of Record' => $application->transcript_of_record_pdf ?? null,
                        'Endorsement Letter 1' => $application->endorsement_1_pdf ?? null,
                        'Endorsement Letter 2' => $application->endorsement_2_pdf ?? null,
                        'Recommendation of Head of Agency' => $application->recommendation_head_agency_pdf ?? null,
                        'Form 2A - Certificate of Employment' => $application->form_2a_pdf ?? null,
                        'Form 2B - Optional Employment Cert.' => $application->form_2b_pdf ?? null,
                        'Form A - Research Plans' => $application->form_a_research_plans_pdf ?? null,
                        'Form B - Career Plans' => $application->form_b_career_plans_pdf ?? null,
                        'Form C - Health Status' => $application->form_c_health_status_pdf ?? null,
                        'NBI Clearance' => $application->nbi_clearance_pdf ?? null,
                        'Letter of Admission' => $application->letter_of_admission_pdf ?? null,
                        'Approved Program of Study' => $application->approved_program_of_study_pdf ?? null,
                        'Lateral Certification' => $application->lateral_certification_pdf ?? null,
                    ];

                    $documents = array_filter($documents, fn($file) => !empty($file));

                    if (!function_exists('getFileUrlFromValue')) {
                        function getFileUrlFromValue($file)
                        {
                            if (!$file) {
                                return null;
                            }

                            if (is_string($file)) {
                                $decoded = json_decode($file, true);
                                if (json_last_error() === JSON_ERROR_NONE) {
                                    $file = is_array($decoded) ? $decoded[0] ?? null : $decoded;
                                }
                            }

                            if (is_array($file)) {
                                $file = $file[0] ?? null;
                            }

                            return $file ? asset('storage/' . ltrim($file, '/')) : null;
                        }
                    }

                    // Decode verified documents from the database
                    $verifiedDocs = $application->verified_documents
                        ? json_decode($application->verified_documents, true)
                        : [];
                @endphp

                <div class="max-h-80 overflow-y-auto pr-2 space-y-4">
                    @foreach ($documents as $label => $file)
                        @php
                            $url = getFileUrlFromValue($file);
                            $isChecked = isset($verifiedDocs[$label]) && $verifiedDocs[$label] === true;
                        @endphp

                        <div class="flex items-center justify-between border-b border-gray-200 pb-2">
                            <!-- Document Label -->
                            <p class="text-sm font-medium w-1/2">{{ $label }}</p>

                            <!-- View File Link -->
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

                            <!-- Verification Checkbox -->
                            <div class="w-1/4 text-right">
                                @if ($url)
                                    <label class="inline-flex items-center text-sm cursor-pointer">
                                        <input type="checkbox" class="peer hidden checkbox-tracker"
                                            data-document="{{ $label }}" {{ $isChecked ? 'checked' : '' }}>
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
                    @if ($application->status === 'approved') bg-green-200 text-green-800
                    @elseif ($application->status === 'rejected') bg-red-200 text-red-800
                    @elseif ($application->status === 'pending') bg-yellow-200 text-yellow-800
                    @elseif ($application->status === 'document_verification') bg-blue-200 text-blue-800 
                    @else bg-gray-100 text-gray-800 @endif">
                        {{ $application->status === 'document_verified' ? 'Document verified' : ucfirst(str_replace('_', ' ', $application->status)) }}
                    </span>
                </div>

                <form action="{{ route('admin.applications.update-status', $application->application_form_id) }}"
                    method="POST" class="flex items-center gap-2">
                    @csrf
                    <strong class="text-sm">Remarks:</strong>
                    <input type="text" name="remarks" class="text-xs border px-3 py-1 rounded w-64"
                        placeholder="Type your message here..." value="{{ $application->remarks }}">
                    <button type="submit"
                        class="text-xs text-white bg-blue-500 hover:bg-blue-600 px-3 py-1 rounded transition">Send</button>
                </form>
            </div>

            <!-- ✅ Quick Actions -->
            <div class="bg-white/30 backdrop-blur-md border border-white/20 shadow-md rounded-2xl p-6">
                <h3 class="text-lg font-bold mb-4">Quick Actions</h3>
                <div class="space-y-2">
                    <a href="#"
                        class="block w-full text-center bg-gray-50 border border-gray-200 text-sm text-gray-800 rounded-md px-4 py-2 hover:bg-gray-100 transition">📄
                        Print Application</a>
                    <a href="#"
                        class="block w-full text-center bg-gray-50 border border-gray-200 text-sm text-gray-800 rounded-md px-4 py-2 hover:bg-gray-100 transition">📥
                        Download Documents</a>
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('.checkbox-tracker');
        const actionButtons = document.getElementById('actionButtons');
        const applicationId = "{{ $application->application_form_id }}";

        // Initialize checkboxes from database state
        checkboxes.forEach(cb => {
            // The checked attribute should already be set from PHP
            // No need to manually set it here
        });

        function toggleActionButtons() {
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            const currentStatus = "{{ $application->status }}";

            if (allChecked) {
                actionButtons.classList.remove('hidden');

                // Only update status if NOT already in document_verification, approved, or rejected
                if (currentStatus !== 'document_verification' &&
                    currentStatus !== 'approved' &&
                    currentStatus !== 'rejected') {
                    updateApplicantStatus(applicationId, 'document_verification');
                }
            } else {
                actionButtons.classList.add('hidden');

                // If unchecking and status is document_verification, revert to pending
                if (currentStatus === 'document_verification') {
                    updateApplicantStatus(applicationId, 'pending');
                }
            }
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', function() {
                const documentId = cb.dataset.document;
                const verified = cb.checked;

                fetch(`/admin/applications/${applicationId}/verify-document`, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            document: documentId,
                            verified: verified
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        console.log('✅ Document verification saved:', data);
                        toggleActionButtons();
                    })
                    .catch(err => {
                        console.error('❌ Error verifying document:', err);
                        // Revert checkbox on error
                        cb.checked = !verified;
                    });
            });
        });

        function updateApplicantStatus(id, status) {
            fetch(`/admin/applications/${id}/status`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        status: status
                    })
                })
                .then(res => res.json())
                .then(data => {
                    console.log("✅ Status updated to:", data.status);
                    // Update the status badge without full page reload
                    updateStatusBadge(data.status);
                })
                .catch(err => console.error("❌ Error updating status:", err));
        }

        function updateStatusBadge(status) {
            const statusBadge = document.querySelector('.application-status-badge');
            if (statusBadge) {
                statusBadge.textContent = status.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());

                // Update badge colors
                statusBadge.className = 'application-status-badge px-3 py-1 rounded-full text-sm font-bold';

                if (status === 'approved') {
                    statusBadge.classList.add('bg-green-200', 'text-green-800');
                } else if (status === 'rejected') {
                    statusBadge.classList.add('bg-red-200', 'text-red-800');
                } else if (status === 'pending') {
                    statusBadge.classList.add('bg-yellow-200', 'text-yellow-800');
                } else if (status === 'document_verification') {
                    statusBadge.classList.add('bg-blue-200', 'text-blue-800');
                } else {
                    statusBadge.classList.add('bg-gray-100', 'text-gray-800');
                }
            }
        }

        // Initial check
        toggleActionButtons();
    });
</script>
