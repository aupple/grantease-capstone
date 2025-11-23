<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Scholarship Application Form - STRAND
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Application Form -->
            <form method="POST" action="{{ route('applicant.application.store') }}" enctype="multipart/form-data"
                class="bg-white p-6 rounded shadow">
                @csrf

                <input type="hidden" name="program" value="{{ $program }}">
                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 text-red-800 border border-red-200 rounded-md">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <!-- Beautiful Circular Step Indicators -->
                <div class="flex justify-between items-center mb-8 relative">
                    <!-- Progress Bar Background -->
                    <div class="absolute left-0 right-0 top-1/2 -translate-y-1/2 h-2 bg-gray-200 z-0"
                        style="margin: 0 50px;">
                        <!-- Progress Bar -->
                        <div id="progress-bar" class="h-full bg-blue-600 transition-all duration-500 ease-in-out"
                            style="width: 0%;"></div>
                    </div>
                    @if (session('success'))
                        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                            class="mb-4 p-4 bg-green-100 text-green-800 border border-green-200 rounded-md">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Step 1 -->
                    <div class="step-indicator active flex flex-col items-center z-10" onclick="goToStep(1)"
                        data-step="1">
                        <div
                            class="w-10 h-10 rounded-full bg-blue-600 border-4 border-blue-100 flex items-center justify-center text-white font-bold mb-2 transition-all duration-300">
                            1</div>
                        <span class="text-xs font-medium text-blue-600">Basic Info</span>
                    </div>

                    <!-- Step 2 -->
                    <div class="step-indicator flex flex-col items-center z-10" onclick="goToStep(2)" data-step="2">
                        <div
                            class="w-10 h-10 rounded-full bg-gray-200 border-4 border-gray-100 flex items-center justify-center text-gray-600 font-bold mb-2 transition-all duration-300">
                            2</div>
                        <span class="text-xs font-medium text-gray-500">Personal Info</span>
                    </div>

                    <!-- Step 3 -->
                    <div class="step-indicator flex flex-col items-center z-10" onclick="goToStep(3)" data-step="3">
                        <div
                            class="w-10 h-10 rounded-full bg-gray-200 border-4 border-gray-100 flex items-center justify-center text-gray-600 font-bold mb-2 transition-all duration-300">
                            3</div>
                        <span class="text-xs font-medium text-gray-500">Education</span>
                    </div>

                    <!-- Step 4 -->
                    <div class="step-indicator flex flex-col items-center z-10" onclick="goToStep(4)" data-step="4">
                        <div
                            class="w-10 h-10 rounded-full bg-gray-200 border-4 border-gray-100 flex items-center justify-center text-gray-600 font-bold mb-2 transition-all duration-300">
                            4</div>
                        <span class="text-xs font-medium text-gray-500">Grad Intent</span>
                    </div>

                    <!-- Step 5 -->
                    <div class="step-indicator flex flex-col items-center z-10" onclick="goToStep(5)" data-step="5">
                        <div
                            class="w-10 h-10 rounded-full bg-gray-200 border-4 border-gray-100 flex items-center justify-center text-gray-600 font-bold mb-2 transition-all duration-300">
                            5</div>
                        <span class="text-xs font-medium text-gray-500">Employment</span>
                    </div>

                    <!-- Step 6 -->
                    <div class="step-indicator flex flex-col items-center z-10" onclick="goToStep(6)" data-step="6">
                        <div
                            class="w-10 h-10 rounded-full bg-gray-200 border-4 border-gray-100 flex items-center justify-center text-gray-600 font-bold mb-2 transition-all duration-300">
                            6</div>
                        <span class="text-xs font-medium text-gray-500">R&D</span>
                    </div>

                    <!-- Step 7 -->
                    <div class="step-indicator flex flex-col items-center z-10" onclick="goToStep(7)" data-step="7">
                        <div
                            class="w-10 h-10 rounded-full bg-gray-200 border-4 border-gray-100 flex items-center justify-center text-gray-600 font-bold mb-2 transition-all duration-300">
                            7</div>
                        <span class="text-xs font-medium text-gray-500">Upload Docs</span>
                    </div>

                    <!-- Step 8 -->
                    <div class="step-indicator flex flex-col items-center z-10" onclick="goToStep(8)" data-step="8">
                        <div
                            class="w-10 h-10 rounded-full bg-gray-200 border-4 border-gray-100 flex items-center justify-center text-gray-600 font-bold mb-2 transition-all duration-300">
                            8</div>
                        <span class="text-xs font-medium text-gray-500">Declaration</span>
                    </div>
                </div>


                <!-- Step 1: Basic Information -->
                <div class="step bg-white p-8 rounded-2xl shadow-md" id="step1">
                    <div class="text-center mb-8">
                        <h3 class="text-2xl font-bold text-gray-800">APPLICATION FORM</h3>
                        <p class="text-gray-500 text-sm">Please fill out all required fields accurately</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="academic_year" class="block text-sm font-medium text-gray-700">Academic
                                Year</label>
                            <input type="text" name="academic_year" id="academic_year"
                                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm 
                focus:ring-blue-500 focus:border-blue-500 sm:text-sm bg-gray-100"
                                readonly required>
                        </div>

                        <div>
                            <label for="school_term" class="block text-sm font-medium text-gray-700">School Term</label>
                            <select name="school_term" id="school_term"
                                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm 
                focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                required>
                                <option value="">Select Term</option>
                                <option value="First Semester">First Semester / Term</option>
                                <option value="Second Semester">Second Semester / Term</option>
                                <option value="Third Semester">Third Semester / Term</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="application_no" class="block text-sm font-medium text-gray-700">Application
                                No.</label>
                            <input type="text" name="application_no" id="application_no"
                                value="STRAND-{{ uniqid() }}" readonly
                                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm bg-gray-100 sm:text-sm">
                        </div>
                        <div>
                            <label for="passport_picture" class="block text-sm font-medium text-gray-700">Attach
                                Passport Size Picture</label>
                            <input type="file" name="passport_picture" id="passport_picture" accept="image/*"
                                class="mt-1 block w-full text-sm text-gray-500 border-gray-300 rounded-lg shadow-sm
                file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0
                file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700
                hover:file:bg-blue-100"
                                required>
                        </div>
                    </div>

                    <div class="flex justify-end mt-8">
                        <button type="button"
                            class="bg-blue-600 text-white px-8 py-2 rounded-lg font-medium hover:bg-blue-700 transition"
                            onclick="validateAndNext(1)">Next: Personal Info</button>
                    </div>
                </div>
                <!-- Step 2: Personal Information -->
                <div class="step bg-white p-8 rounded-2xl shadow-md hidden" id="step2">
                    <h3 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-3">Form 1. Information Sheet</h3>
                    <h4 class="text-lg font-semibold text-blue-700 mb-6">I. PERSONAL INFORMATION</h4>

                    <!-- 🧍 Name Section -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                        <div>
                            <label for="last_name" class="text-sm font-medium text-gray-700">Last Name</label>
                            <input type="text" name="last_name" id="last_name"
                                value="{{ Auth::user()->last_name ?? '' }}"
                                class="mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm bg-gray-100" readonly>
                        </div>

                        <div>
                            <label for="first_name" class="text-sm font-medium text-gray-700">First Name</label>
                            <input type="text" name="first_name" id="first_name"
                                value="{{ Auth::user()->first_name ?? '' }}"
                                class="mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm bg-gray-100" readonly>
                        </div>

                        <div>
                            <label for="middle_name" class="text-sm font-medium text-gray-700">Middle Name</label>
                            <input type="text" name="middle_name" id="middle_name"
                                value="{{ Auth::user()->middle_name ?? '' }}"
                                class="mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm bg-gray-100" readonly>
                        </div>

                        <div>
                            <label for="suffix" class="text-sm font-medium text-gray-700">Suffix</label>
                            <input type="text" name="suffix" id="suffix"
                                value="{{ Auth::user()->suffix ?? 'N/A' }}"
                                class="mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm bg-gray-100" readonly>
                        </div>
                    </div>
                    <!-- 🏠 Address Section -->
                    <h5 class="text-md font-semibold text-gray-800 mb-3">Permanent Address</h5>

                    <!-- Province / City / Barangay -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div>
                            <label for="province_select" class="text-sm font-medium text-gray-700">Province</label>
                            <select id="province_select" name="province"
                                class="mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm">
                                <option value="">Select Province</option>
                            </select>
                        </div>
                        <div>
                            <label for="city_select" class="text-sm font-medium text-gray-700">City /
                                Municipality</label>
                            <select id="city_select" name="city"
                                class="mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm">
                                <option value="">Select City / Municipality</option>
                            </select>
                        </div>
                        <div>
                            <label for="barangay_select" class="text-sm font-medium text-gray-700">Barangay</label>
                            <select id="barangay_select" name="barangay"
                                class="mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm">
                                <option value="">Select Barangay</option>
                            </select>
                        </div>
                    </div>

                    <!-- Street / House No. / Zip Code -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                        <div class="md:col-span-2">
                            <label for="address_street" class="text-sm font-medium text-gray-700">Street</label>
                            <input type="text" name="address_street" id="address_street" placeholder="Street"
                                class="mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm">
                        </div>
                        <div>
                            <label for="address_no" class="text-sm font-medium text-gray-700">House No.</label>
                            <input type="text" name="address_no" id="address_no" placeholder="No."
                                class="mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm">
                        </div>
                        <div>
                            <label for="zip_code" class="text-sm font-medium text-gray-700">Zip Code</label>
                            <input type="text" name="zip_code" id="zip_code"
                                class="mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm bg-gray-100" readonly>
                        </div>
                    </div>

                    <!-- Region / District / Passport -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                        <div>
                            <label for="region_select" class="text-sm font-medium text-gray-700">Region</label>
                            <input type="text" id="region_select" name="region"
                                class="mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm bg-gray-100" readonly>
                        </div>
                        <div>
                            <label for="district_select" class="text-sm font-medium text-gray-700">District</label>
                            <select id="district_select" name="district"
                                class="mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm">
                                <option value="">Select District</option>
                                <option>N/A</option>
                                <option>District 1</option>
                                <option>District 2</option>
                                <option>District 3</option>
                                <option>District 4</option>
                                <option>District 5</option>
                                <option>District 6</option>
                            </select>
                        </div>
                        <div>
                            <label for="passport_no" class="text-sm font-medium text-gray-700">Passport No.</label>
                            <input type="text" name="passport_no" id="passport_no"
                                class="mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm">
                        </div>
                    </div>

                    <!-- 📞 Contact Information -->
                    <h5 class="text-md font-semibold text-gray-800 mb-3">Contact Information</h5>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                        <div>
                            <label for="email_address" class="text-sm font-medium text-gray-700">Email Address</label>
                            <input type="email" name="email_address" id="email_address"
                                value="{{ Auth::user()->email ?? '' }}"
                                class="mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm bg-gray-100" readonly>
                        </div>
                        <div>
                            <label for="current_mailing_address" class="text-sm font-medium text-gray-700">Mailing
                                Address</label>
                            <input type="text" name="current_mailing_address" id="current_mailing_address"
                                placeholder="Enter mailing address"
                                class="mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm" required>
                        </div>
                        <div>
                            <label for="telephone_nos" class="text-sm font-medium text-gray-700">Telephone / Mobile
                                Number</label>
                            <input type="text" name="telephone_nos" id="telephone_nos" placeholder="09XXXXXXXXX"
                                class="numeric-only mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm" required>
                        </div>
                    </div>

                    <!-- 👤 Personal Details -->
                    <h5 class="text-md font-semibold text-gray-800 mb-3">Personal Details</h5>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                        <div>
                            <label for="civil_status" class="text-sm font-medium text-gray-700">Civil Status</label>
                            <select name="civil_status" id="civil_status"
                                class="mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm" required>
                                <option value="">Select</option>
                                <option>Single</option>
                                <option>Married</option>
                                <option>Widowed</option>
                                <option>Divorced</option>
                            </select>
                        </div>
                        <div>
                            <label for="date_of_birth" class="text-sm font-medium text-gray-700">Date of Birth</label>
                            <input type="date" name="date_of_birth" id="date_of_birth"
                                class="mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm" required>
                        </div>
                        <div>
                            <label for="age" class="text-sm font-medium text-gray-700">Age</label>
                            <input type="text" name="age" id="age"
                                class="mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm bg-gray-100" readonly>
                        </div>
                        <div>
                            <label for="sex" class="text-sm font-medium text-gray-700">Sex</label>
                            <select name="sex" id="sex"
                                class="mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm" required>
                                <option value="">Select</option>
                                <option>Male</option>
                                <option>Female</option>
                            </select>
                        </div>
                    </div>

                    <!-- 👨‍👩‍👧 Parents -->
                    <h5 class="text-md font-semibold text-gray-800 mb-3">Parents</h5>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-10">
                        <div>
                            <label for="father_name" class="text-sm font-medium text-gray-700">Father's Name</label>
                            <input type="text" name="father_name" id="father_name"
                                class="mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm" required>
                        </div>
                        <div>
                            <label for="mother_name" class="text-sm font-medium text-gray-700">Mother's Name</label>
                            <input type="text" name="mother_name" id="mother_name"
                                class="mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm" required>
                        </div>
                    </div>

                    <!-- 🔘 Navigation -->
                    <div class="flex justify-between">
                        <button type="button"
                            class="bg-gray-300 text-gray-800 px-6 py-2 rounded-lg hover:bg-gray-400 transition"
                            onclick="prevStep(1)">Back</button>
                        <button type="button"
                            class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition"
                            onclick="validateAndNext(2)">Next: Education</button>
                    </div>
                </div>

                <!-- Step 3: Educational Background -->
                <div class="step bg-white p-8 rounded-2xl shadow-md hidden" id="step3">
                    <h4 class="text-2xl font-semibold text-gray-800 mb-6 border-b pb-3">
                        II. Educational Background
                    </h4>

                    <!-- BS Degree -->
                    <div class="mb-8 bg-gray-50 border border-gray-200 rounded-xl p-6 shadow-sm">
                        <p class="text-lg font-semibold text-gray-800 mb-4">BS Degree</p>

                        <!-- Row 1 -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-5">
                            <input type="text" name="bs_degree" placeholder="Degree"
                                class="w-full border-gray-300 rounded-md shadow-sm sm:text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                required>

                            <input type="text" name="bs_period" placeholder="Period (Year Started – Year Ended)"
                                class="w-full border-gray-300 rounded-md shadow-sm sm:text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                required>

                            <input type="text" name="bs_field" placeholder="Field"
                                class="w-full border-gray-300 rounded-md shadow-sm sm:text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                required>

                            <input type="text" name="bs_university" placeholder="University/School"
                                class="w-full border-gray-300 rounded-md shadow-sm sm:text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                required>
                        </div>

                        <!-- Row 2 -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <!-- Scholarship -->
                            <div class="w-full border border-gray-300 rounded-md p-3 bg-white">
                                <span class="text-sm text-gray-700 font-semibold">Scholarship (if applicable)</span>
                                <div class="mt-2 flex flex-wrap gap-2">
                                    <label class="inline-flex items-center text-sm text-gray-700"><input
                                            type="checkbox" name="bs_scholarship_type[]" value="PSHS"
                                            class="form-checkbox text-blue-600 mr-2">PSHS</label>
                                    <label class="inline-flex items-center text-sm text-gray-700"><input
                                            type="checkbox" name="bs_scholarship_type[]" value="RA 7687"
                                            class="form-checkbox text-blue-600 mr-2">RA 7687</label>
                                    <label class="inline-flex items-center text-sm text-gray-700"><input
                                            type="checkbox" name="bs_scholarship_type[]" value="MERIT"
                                            class="form-checkbox text-blue-600 mr-2">MERIT</label>
                                    <label class="inline-flex items-center text-sm text-gray-700"><input
                                            type="checkbox" name="bs_scholarship_type[]" value="RA 10612"
                                            class="form-checkbox text-blue-600 mr-2">RA 10612</label>
                                    <label class="inline-flex items-center text-sm text-gray-700 mt-1">
                                        Others:
                                        <input type="text" name="bs_scholarship_others"
                                            class="ml-2 w-32 border-gray-300 rounded-md shadow-sm sm:text-sm focus:ring-1 focus:ring-blue-400">
                                    </label>
                                </div>
                            </div>

                            <input type="text" name="bs_remarks" placeholder="Remarks"
                                class="w-full border-gray-300 rounded-md shadow-sm sm:text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                required>
                        </div>
                    </div>

                    <!-- Add Buttons -->
                    <div id="degree-buttons" class="mb-8 flex gap-4">
                        <button type="button" onclick="showDegree('ms-degree-section', this)"
                            class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 transition">
                            + Add MS Degree
                        </button>

                        <button type="button" onclick="showDegree('phd-degree-section', this)"
                            class="bg-green-600 text-white px-5 py-2 rounded-lg hover:bg-green-700 transition">
                            + Add PhD Degree
                        </button>
                    </div>

                    <!-- MS Degree Section -->
                    <div id="ms-degree-section"
                        class="hidden mb-8 bg-gray-50 border border-gray-200 rounded-xl p-6 shadow-sm">
                        <div class="flex justify-between items-center mb-4">
                            <p class="text-lg font-semibold text-gray-800">MS Degree</p>
                            <button type="button" onclick="hideDegree('ms-degree-section')"
                                class="text-red-500 hover:text-red-700 text-sm font-medium">
                                ✕ Remove
                            </button>
                        </div>

                        <!-- Row 1 -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-5">
                            <input type="text" name="ms_degree" placeholder="Degree"
                                class="w-full border-gray-300 rounded-md shadow-sm sm:text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                            <input type="text" name="ms_period" placeholder="Period (Year Started – Year Ended)"
                                class="w-full border-gray-300 rounded-md shadow-sm sm:text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                            <input type="text" name="ms_field" placeholder="Field"
                                class="w-full border-gray-300 rounded-md shadow-sm sm:text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                            <input type="text" name="ms_university" placeholder="University/School"
                                class="w-full border-gray-300 rounded-md shadow-sm sm:text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Row 2 -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <!-- Scholarship -->
                            <div class="w-full border border-gray-300 rounded-md p-3 bg-white">
                                <span class="text-sm text-gray-700 font-semibold">Scholarship (if applicable)</span>
                                <div class="mt-2 flex flex-wrap gap-2">
                                    <label class="inline-flex items-center text-sm text-gray-700"><input
                                            type="checkbox" name="bs_scholarship_type[]" value="PSHS"
                                            class="form-checkbox text-blue-600 mr-2">PSHS</label>
                                    <label class="inline-flex items-center text-sm text-gray-700"><input
                                            type="checkbox" name="bs_scholarship_type[]" value="RA 7687"
                                            class="form-checkbox text-blue-600 mr-2">RA 7687</label>
                                    <label class="inline-flex items-center text-sm text-gray-700"><input
                                            type="checkbox" name="bs_scholarship_type[]" value="MERIT"
                                            class="form-checkbox text-blue-600 mr-2">MERIT</label>
                                    <label class="inline-flex items-center text-sm text-gray-700"><input
                                            type="checkbox" name="bs_scholarship_type[]" value="RA 10612"
                                            class="form-checkbox text-blue-600 mr-2">RA 10612</label>
                                    <label class="inline-flex items-center text-sm text-gray-700 mt-1">
                                        Others:
                                        <input type="text" name="bs_scholarship_others"
                                            class="ml-2 w-32 border-gray-300 rounded-md shadow-sm sm:text-sm focus:ring-1 focus:ring-blue-400">
                                    </label>
                                </div>
                            </div>

                            <input type="text" name="ms_remarks" placeholder="Remarks"
                                class="w-full border-gray-300 rounded-md shadow-sm sm:text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <!-- PhD Degree Section -->
                    <div id="phd-degree-section"
                        class="hidden mb-8 bg-gray-50 border border-gray-200 rounded-xl p-6 shadow-sm">
                        <div class="flex justify-between items-center mb-4">
                            <p class="text-lg font-semibold text-gray-800">PhD Degree</p>
                            <button type="button" onclick="hideDegree('phd-degree-section')"
                                class="text-red-500 hover:text-red-700 text-sm font-medium">
                                ✕ Remove
                            </button>
                        </div>

                        <!-- Row 1 -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-5">
                            <input type="text" name="phd_degree" placeholder="Degree"
                                class="w-full border-gray-300 rounded-md shadow-sm sm:text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                            <input type="text" name="phd_period" placeholder="Period (Year Started – Year Ended)"
                                class="w-full border-gray-300 rounded-md shadow-sm sm:text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                            <input type="text" name="phd_field" placeholder="Field"
                                class="w-full border-gray-300 rounded-md shadow-sm sm:text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                            <input type="text" name="phd_university" placeholder="University/School"
                                class="w-full border-gray-300 rounded-md shadow-sm sm:text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Row 2 -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <!-- Scholarship -->
                            <div class="w-full border border-gray-300 rounded-md p-3 bg-white">
                                <span class="text-sm text-gray-700 font-semibold">Scholarship (if applicable)</span>
                                <div class="mt-2 flex flex-wrap gap-2">
                                    <label class="inline-flex items-center text-sm text-gray-700"><input
                                            type="checkbox" name="bs_scholarship_type[]" value="PSHS"
                                            class="form-checkbox text-blue-600 mr-2">PSHS</label>
                                    <label class="inline-flex items-center text-sm text-gray-700"><input
                                            type="checkbox" name="bs_scholarship_type[]" value="RA 7687"
                                            class="form-checkbox text-blue-600 mr-2">RA 7687</label>
                                    <label class="inline-flex items-center text-sm text-gray-700"><input
                                            type="checkbox" name="bs_scholarship_type[]" value="MERIT"
                                            class="form-checkbox text-blue-600 mr-2">MERIT</label>
                                    <label class="inline-flex items-center text-sm text-gray-700"><input
                                            type="checkbox" name="bs_scholarship_type[]" value="RA 10612"
                                            class="form-checkbox text-blue-600 mr-2">RA 10612</label>
                                    <label class="inline-flex items-center text-sm text-gray-700 mt-1">
                                        Others:
                                        <input type="text" name="bs_scholarship_others"
                                            class="ml-2 w-32 border-gray-300 rounded-md shadow-sm sm:text-sm focus:ring-1 focus:ring-blue-400">
                                    </label>
                                </div>
                            </div>

                            <input type="text" name="phd_remarks" placeholder="Remarks"
                                class="w-full border-gray-300 rounded-md shadow-sm sm:text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <!-- 🔘 Navigation -->
                    <div class="flex justify-between mt-8">
                        <button type="button"
                            class="bg-gray-300 text-gray-800 px-6 py-2 rounded-lg hover:bg-gray-400 transition"
                            onclick="prevStep(2)">Back</button>
                        <button type="button"
                            class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition"
                            onclick="validateAndNext(3)">Next: Grad Intent</button>
                    </div>
                </div>

                <!-- Step 4: Graduate Scholarship Intentions Data -->
                <div class="step bg-white p-6 rounded-lg shadow-sm hidden" id="step4">
                    <h3 class="text-xl font-bold mb-4 border-b pb-2">Form 3. Graduate Scholarship Intentions Data</h3>
                    <h4 class="text-lg font-semibold text-blue-700 mb-3">III. GRADUATE SCHOLARSHIP INTENTIONS DATA</h4>

                    <!-- Notes -->
                    <div class="text-sm text-gray-600 bg-gray-50 border rounded-md p-4 mb-6 leading-relaxed">
                        <p class="mb-2">
                            <strong>Notes:</strong><br>
                            1. An applicant for a graduate program should elect to go to another university if he/she
                            earned his/her 1st (BS) and/or 2nd (MS) degrees from the same university to avoid
                            inbreeding.
                        </p>
                        <p>
                            2. A faculty-applicant for a graduate program should elect to go to any of the member
                            universities of the ASTHRDP National Science Consortium, ERDT Consortium, or CBPSME National
                            Consortium in Graduate Science and Mathematics Education, or in a foreign university with a
                            good track record and/or recognized institution in the specialized field in S&T to be
                            pursued.
                        </p>
                    </div>

                    <!-- Strand Category -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">STRAND CATEGORY</label>
                            <div class="flex flex-col space-y-2">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="strand_category" value="STRAND 1" class="form-radio"
                                        required>
                                    <span class="ml-2 text-sm text-gray-700">STRAND 1</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="strand_category" value="STRAND 2"
                                        class="form-radio">
                                    <span class="ml-2 text-sm text-gray-700">STRAND 2</span>
                                </label>
                            </div>
                        </div>

                        <!-- STRAND 2 only -->
                        <div id="strand2_fields" class="hidden">
                            <label class="block text-sm font-medium text-gray-700 mb-2">TYPE OF APPLICANT (for STRAND 2
                                only)</label>
                            <div class="flex flex-col space-y-2">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="applicant_type" value="Student" class="form-radio">
                                    <span class="ml-2 text-sm text-gray-700">Student</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="applicant_type" value="Faculty" class="form-radio">
                                    <span class="ml-2 text-sm text-gray-700">Faculty</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Scholarship Type -->
                    <div class="mb-8">
                        <label class="block text-sm font-medium text-gray-700 mb-2">TYPE OF SCHOLARSHIP APPLIED
                            FOR</label>
                        <div class="flex items-center space-x-6">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="scholarship_type[]" value="MS"
                                    class="form-checkbox">
                                <span class="ml-2 text-sm text-gray-700">MS</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="scholarship_type[]" value="PhD"
                                    class="form-checkbox">
                                <span class="ml-2 text-sm text-gray-700">PhD</span>
                            </label>
                        </div>
                    </div>

                    <!-- Applicant Selection -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select Applicant Type</label>
                        <div class="flex items-center space-x-6">
                            <label class="inline-flex items-center">
                                <input type="radio" name="applicant_status" value="new" class="form-radio">
                                <span class="ml-2 text-sm text-gray-700">New Applicant</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="applicant_status" value="lateral" class="form-radio">
                                <span class="ml-2 text-sm text-gray-700">Lateral Applicant</span>
                            </label>
                        </div>
                    </div>

                    <!-- New Applicant Section -->
                    <div id="newApplicantSection" class="bg-gray-50 border rounded-md p-4 mb-6 hidden">
                        <h5 class="text-md font-semibold text-gray-800 mb-3">New Applicant</h5>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="new_applicant_university"
                                    class="block text-sm font-medium text-gray-700">a. University where you
                                    applied/intend to enroll</label>
                                <input type="text" name="new_applicant_university" id="new_applicant_university"
                                    class="mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm">
                            </div>
                            <div>
                                <label for="new_applicant_course" class="block text-sm font-medium text-gray-700">b.
                                    Course/Degree</label>
                                <input type="text" name="new_applicant_course" id="new_applicant_course"
                                    class="mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm">
                            </div>
                        </div>
                    </div>

                    <!-- Lateral Applicant Section -->
                    <div id="lateralApplicantSection" class="bg-gray-50 border rounded-md p-4 mb-8 hidden">
                        <h5 class="text-md font-semibold text-gray-800 mb-3">Lateral Applicant</h5>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="lateral_university_enrolled"
                                    class="block text-sm font-medium text-gray-700">a. University enrolled in</label>
                                <input type="text" name="lateral_university_enrolled"
                                    id="lateral_university_enrolled"
                                    class="mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm">
                            </div>
                            <div>
                                <label for="lateral_course_degree" class="block text-sm font-medium text-gray-700">b.
                                    Course/Degree</label>
                                <input type="text" name="lateral_course_degree" id="lateral_course_degree"
                                    class="mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm">
                            </div>
                            <div>
                                <label for="lateral_units_earned" class="block text-sm font-medium text-gray-700">c.
                                    Number of units earned</label>
                                <input type="number" name="lateral_units_earned" id="lateral_units_earned"
                                    class="mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm">
                            </div>
                            <div>
                                <label for="lateral_remaining_units"
                                    class="block text-sm font-medium text-gray-700">d. Remaining units/sems</label>
                                <input type="text" name="lateral_remaining_units" id="lateral_remaining_units"
                                    class="mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm">
                            </div>
                        </div>

                        <div class="mt-4 space-y-4">
                            <div class="flex flex-wrap items-center gap-4">
                                <label class="text-sm font-medium text-gray-700">e. Has your research topic been
                                    approved by the panel?</label>
                                <div class="flex items-center space-x-4">
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="research_topic_approved" value="1"
                                            class="form-radio">
                                        <span class="ml-2 text-sm text-gray-700">Yes</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="research_topic_approved" value="0"
                                            class="form-radio">
                                        <span class="ml-2 text-sm text-gray-700">No</span>
                                    </label>
                                </div>
                            </div>

                            <div>
                                <label for="research_title"
                                    class="block text-sm font-medium text-gray-700">Title</label>
                                <input type="text" name="research_title" id="research_title"
                                    class="mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm">
                            </div>

                            <div>
                                <label for="last_enrollment_date" class="block text-sm font-medium text-gray-700">Date
                                    of last enrollment in thesis/dissertation course</label>
                                <input type="date" name="last_enrollment_date" id="last_enrollment_date"
                                    class="mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm">
                            </div>
                        </div>
                    </div>

                    <!-- Navigation -->
                    <div class="flex justify-between mt-6">
                        <button type="button"
                            class="bg-gray-300 text-gray-800 px-6 py-2 rounded-md hover:bg-gray-400 transition"
                            onclick="prevStep(3)">Back</button>
                        <button type="button"
                            class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition"
                            onclick="validateAndNext(4)">Next: Employment</button>
                    </div>
                </div>


                <!-- Step 5: Employment Information -->
                <div class="step bg-white p-6 rounded-lg shadow-sm hidden" id="step5">
                    <h4 class="text-lg font-semibold mb-3">IV. CAREER/EMPLOYMENT INFORMATION</h4>

                    <!-- Employment Status -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">a. Present Employment Status</label>
                        <div class="mt-1 flex flex-wrap gap-4">
                            <label class="inline-flex items-center">
                                <input type="radio" name="employment_status" value="Permanent" class="form-radio"
                                    required>
                                <span class="ml-2 text-sm text-gray-700">Permanent</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="employment_status" value="Contractual"
                                    class="form-radio">
                                <span class="ml-2 text-sm text-gray-700">Contractual</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="employment_status" value="Probationary"
                                    class="form-radio">
                                <span class="ml-2 text-sm text-gray-700">Probationary</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="employment_status" value="Self-employed"
                                    class="form-radio">
                                <span class="ml-2 text-sm text-gray-700">Self-employed</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="employment_status" value="Unemployed"
                                    class="form-radio">
                                <span class="ml-2 text-sm text-gray-700">Unemployed</span>
                            </label>
                        </div>
                    </div>

                    <!-- Employed Fields -->
                    <div id="employed_fields" class="mb-6 border p-4 rounded-md hidden">
                        <p class="font-semibold mb-2">a.1 For those who are presently employed*</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-2">
                            <div>
                                <label for="employed_position"
                                    class="block text-sm font-medium text-gray-700">Position</label>
                                <input type="text" name="employed_position" id="employed_position"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                            </div>
                            <div>
                                <label for="employed_length_of_service"
                                    class="block text-sm font-medium text-gray-700">Length of Service</label>
                                <input type="text" name="employed_length_of_service"
                                    id="employed_length_of_service"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                            </div>
                        </div>
                        <div class="mb-2">
                            <label for="employed_company_name" class="block text-sm font-medium text-gray-700">Name of
                                Company/Office</label>
                            <input type="text" name="employed_company_name" id="employed_company_name"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                        </div>
                        <div class="mb-2">
                            <label for="employed_company_address"
                                class="block text-sm font-medium text-gray-700">Address of Company/Office</label>
                            <input type="text" name="employed_company_address" id="employed_company_address"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-2">
                            <div>
                                <label for="employed_email"
                                    class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" name="employed_email" id="employed_email"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                            </div>
                            <div>
                                <label for="employed_website"
                                    class="block text-sm font-medium text-gray-700">Website</label>
                                <input type="url" name="employed_website" id="employed_website"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                            </div>
                            <div>
                                <label for="employed_telephone"
                                    class="block text-sm font-medium text-gray-700">Telephone No.</label>
                                <input type="text" name="employed_telephone" id="employed_telephone"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                            </div>
                        </div>
                        <div>
                            <label for="employed_fax" class="block text-sm font-medium text-gray-700">Fax No.</label>
                            <input type="text" name="employed_fax" id="employed_fax"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                        </div>
                        <p class="text-sm text-gray-600 mt-2">
                            *Once accepted in the scholarship program, the scholar must obtain permission to go on a
                            Leave of Absence (LOA) from his/her employer and become a full-time student. The scholar
                            must submit a letter from his/her employer approving the LOA.
                        </p>
                    </div>

                    <!-- Self-Employed Fields -->
                    <div id="self_employed_fields" class="mb-6 border p-4 rounded-md hidden">
                        <p class="font-semibold mb-2">a.2 For those who are self-employed</p>
                        <div class="mb-2">
                            <label for="self_employed_business_name"
                                class="block text-sm font-medium text-gray-700">Business Name</label>
                            <input type="text" name="self_employed_business_name" id="self_employed_business_name"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                        </div>
                        <div class="mb-2">
                            <label for="self_employed_address"
                                class="block text-sm font-medium text-gray-700">Address</label>
                            <input type="text" name="self_employed_address" id="self_employed_address"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-2">
                            <div>
                                <label for="self_employed_email_website"
                                    class="block text-sm font-medium text-gray-700">Email/Website</label>
                                <input type="text" name="self_employed_email_website"
                                    id="self_employed_email_website"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                            </div>
                            <div>
                                <label for="self_employed_telephone"
                                    class="block text-sm font-medium text-gray-700">Telephone No.</label>
                                <input type="text" name="self_employed_telephone" id="self_employed_telephone"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="self_employed_fax" class="block text-sm font-medium text-gray-700">Fax
                                    No.</label>
                                <input type="text" name="self_employed_fax" id="self_employed_fax"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                            </div>
                            <div>
                                <label for="self_employed_type_of_business"
                                    class="block text-sm font-medium text-gray-700">Type of Business</label>
                                <input type="text" name="self_employed_type_of_business"
                                    id="self_employed_type_of_business"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                            </div>
                        </div>
                        <div>
                            <label for="self_employed_years_of_operation"
                                class="block text-sm font-medium text-gray-700">Years of Operation</label>
                            <input type="text" name="self_employed_years_of_operation"
                                id="self_employed_years_of_operation"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                        </div>
                    </div>

                    <!-- Research Plans -->
                    <div class="mb-4">
                        <label for="research_plans" class="block text-sm font-medium text-gray-700">b. RESEARCH PLANS
                            (Minimum 300 words)</label>
                        <textarea name="research_plans" id="research_plans" rows="4"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm"
                            placeholder="Briefly discuss your proposed research area/s." required></textarea>
                    </div>

                    <!-- Career Plans -->
                    <div class="mb-6">
                        <label for="career_plans" class="block text-sm font-medium text-gray-700">c. CAREER PLANS
                            (Minimum of 300 words)</label>
                        <textarea name="career_plans" id="career_plans" rows="4"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm"
                            placeholder="Discuss your future plans after graduation." required></textarea>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="flex justify-between mt-8">
                        <button type="button"
                            class="bg-gray-300 text-gray-800 px-6 py-2 rounded-md hover:bg-gray-400"
                            onclick="prevStep(4)">Back</button>
                        <button type="button" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700"
                            onclick="validateAndNext(5)">Next: R&D / Pubs / Awards</button>
                    </div>
                </div>
                <!-- Step 6: Research, Publications, Awards -->
                <div class="step bg-white p-8 rounded-2xl shadow-md hidden" id="step6">
                    <!-- V. Research and Development Involvement -->
                    <h4 class="text-2xl font-semibold text-gray-800 mb-3 border-b pb-2">
                        V. Research and Development Involvement (Last Five Years)
                    </h4>
                    <p class="text-sm text-gray-600 mb-5">Use additional sheet if necessary.</p>

                    <div id="rd_involvement_container" class="space-y-5 mb-6">
                        <div class="rd_involvement_item bg-gray-50 border border-gray-200 rounded-xl p-6 shadow-sm">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 items-start">
                                <div class="flex flex-col justify-between">
                                    <label for="rd_field_title_1"
                                        class="block text-sm font-medium text-gray-700 h-[40px] flex items-end">
                                        Field & Title of Research <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="rd_involvement[0][field_title]" id="rd_field_title_1"
                                        class="mt-2 w-full border border-gray-300 rounded-md shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500"
                                        required>
                                </div>
                                <div class="flex flex-col justify-between">
                                    <label for="rd_location_duration_1"
                                        class="block text-sm font-medium text-gray-700 h-[40px] flex items-end">
                                        Location / Duration <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="rd_involvement[0][location_duration]"
                                        id="rd_location_duration_1"
                                        class="mt-2 w-full border border-gray-300 rounded-md shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500"
                                        required>
                                </div>
                                <div class="flex flex-col justify-between">
                                    <label for="rd_fund_source_1"
                                        class="block text-sm font-medium text-gray-700 h-[40px] flex items-end">
                                        Fund Source
                                    </label>
                                    <input type="text" name="rd_involvement[0][fund_source]" id="rd_fund_source_1"
                                        class="mt-2 w-full border border-gray-300 rounded-md shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div class="flex flex-col justify-between">
                                    <label for="rd_nature_of_involvement_1"
                                        class="block text-sm font-medium text-gray-700 h-[40px] flex items-end">
                                        Nature of Involvement
                                    </label>
                                    <input type="text" name="rd_involvement[0][nature_of_involvement]"
                                        id="rd_nature_of_involvement_1"
                                        class="mt-2 w-full border border-gray-300 rounded-md shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" id="add_rd_involvement"
                        class="bg-green-500 text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-green-600 transition mb-10">
                        + Add R&D Involvement
                    </button>

                    <!-- VI. Publications -->
                    <h4 class="text-2xl font-semibold text-gray-800 mb-3 border-b pb-2">VI. Publications (Last Five
                        Years)</h4>
                    <p class="text-sm text-gray-600 mb-5">Use additional sheet if necessary.</p>

                    <div id="publications_container" class="space-y-5 mb-6">
                        <div class="publication_item bg-gray-50 border border-gray-200 rounded-xl p-6 shadow-sm">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label for="pub_title_1" class="block text-sm font-medium text-gray-700">
                                        Title of Article <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="publications[0][title]" id="pub_title_1"
                                        class="mt-2 w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500"
                                        required>
                                </div>
                                <div>
                                    <label for="pub_name_year_1" class="block text-sm font-medium text-gray-700">
                                        Name / Year of Publication
                                    </label>
                                    <input type="text" name="publications[0][name_year]" id="pub_name_year_1"
                                        class="mt-2 w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label for="pub_nature_of_involvement_1"
                                        class="block text-sm font-medium text-gray-700">
                                        Nature of Involvement
                                    </label>
                                    <input type="text" name="publications[0][nature_of_involvement]"
                                        id="pub_nature_of_involvement_1"
                                        class="mt-2 w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="button" id="add_publication"
                        class="bg-green-500 text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-green-600 transition mb-10">
                        + Add Publication
                    </button>

                    <!-- VII. Awards Received -->
                    <h4 class="text-2xl font-semibold text-gray-800 mb-3 border-b pb-2">VII. Awards Received</h4>
                    <p class="text-sm text-gray-600 mb-5">Include national, regional, or institutional awards.</p>

                    <div id="awards_container" class="space-y-5 mb-6">
                        <div class="award_item bg-gray-50 border border-gray-200 rounded-xl p-6 shadow-sm">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label for="award_title_1" class="block text-sm font-medium text-gray-700">
                                        Title of Award <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="awards[0][title]" id="award_title_1"
                                        class="mt-2 w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500"
                                        required>
                                </div>
                                <div>
                                    <label for="award_giving_body_1" class="block text-sm font-medium text-gray-700">
                                        Award Giving Body
                                    </label>
                                    <input type="text" name="awards[0][giving_body]" id="award_giving_body_1"
                                        class="mt-2 w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label for="award_year_1" class="block text-sm font-medium text-gray-700">
                                        Year of Award
                                    </label>
                                    <input type="text" name="awards[0][year]" id="award_year_1"
                                        class="mt-2 w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="button" id="add_award"
                        class="bg-green-500 text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-green-600 transition mb-10">
                        + Add Award
                    </button>

                    <!-- Navigation Buttons -->
                    <div class="flex justify-between mt-10">
                        <button type="button"
                            class="bg-gray-200 text-gray-800 px-6 py-2 rounded-lg hover:bg-gray-300 transition"
                            onclick="prevStep(5)">Back</button>
                        <button type="button"
                            class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition"
                            onclick="validateAndNext(6)">Next: Upload Docs</button>
                    </div>
                </div>

                <!-- ✅ Step 7: Upload Documents -->
                <div class="step bg-white p-6 rounded-2xl shadow-md hidden" id="step7">
                    <!-- Header -->
                    <h4 class="text-xl font-semibold mb-6 text-gray-800 flex items-center gap-2 border-b pb-3">
                        📂 Upload Required Documents
                        <span class="text-sm font-normal text-gray-500">(PDF only)</span>
                    </h4>

                    <div class="space-y-6">
                        <!-- 🔹 Required Documents -->
                        <section class="bg-gray-50 p-5 rounded-lg shadow-sm border border-gray-100">
                            <h5 class="text-base font-semibold mb-4 text-gray-800 flex items-center gap-2">
                                <i class="fa-solid fa-file-circle-check text-blue-500"></i> Required Documents
                            </h5>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Birth Certificate <span class="text-red-500">*</span>
                                    </label>
                                    <input type="file" name="birth_certificate_pdf" accept="application/pdf"
                                        required
                                        class="w-full border border-gray-300 rounded-md text-sm p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Transcript of Records <span class="text-red-500">*</span>
                                    </label>
                                    <input type="file" name="transcript_of_record_pdf" accept="application/pdf"
                                        required
                                        class="w-full border border-gray-300 rounded-md text-sm p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Endorsement 1 <span class="text-red-500">*</span>
                                    </label>
                                    <input type="file" name="endorsement_1_pdf" accept="application/pdf" required
                                        class="w-full border border-gray-300 rounded-md text-sm p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Endorsement 2 <span class="text-red-500">*</span>
                                    </label>
                                    <input type="file" name="endorsement_2_pdf" accept="application/pdf" required
                                        class="w-full border border-gray-300 rounded-md text-sm p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                </div>
                            </div>
                        </section>

                        <!-- 🔹 If Employed Section -->
                        <section id="if_employed_section"
                            class="bg-gray-50 p-5 rounded-lg shadow-sm border border-gray-100 hidden">
                            <h5 class="text-base font-semibold mb-4 text-gray-800 flex items-center gap-2">
                                <i class="fa-solid fa-briefcase text-blue-500"></i> For Employed Applicants
                            </h5>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Recommendation from Head of Agency <span class="text-red-500">*</span>
                                    </label>
                                    <input type="file" name="recommendation_head_agency_pdf" id="recommendation_head_agency_pdf"
                                        accept="application/pdf"
                                        class="w-full border border-gray-300 rounded-md text-sm p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition employed-required">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Form 2A – Certificate of Employment <span class="text-red-500">*</span>
                                    </label>
                                    <input type="file" name="form_2a_pdf" id="form_2a_pdf" accept="application/pdf"
                                        class="w-full border border-gray-300 rounded-md text-sm p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition employed-required">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Form 2B – Certificate of Employment (Optional)
                                    </label>
                                    <input type="file" name="form_2b_pdf" accept="application/pdf"
                                        class="w-full border border-gray-300 rounded-md text-sm p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                </div>
                            </div>
                        </section>

                        <!-- 🔹 Additional Requirements -->
                        <section class="bg-gray-50 p-5 rounded-lg shadow-sm border border-gray-100">
                            <h5 class="text-base font-semibold mb-4 text-gray-800 flex items-center gap-2">
                                <i class="fa-solid fa-folder-plus text-blue-500"></i> Additional Requirements
                            </h5>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Form A – Research Plans <span class="text-red-500">*</span>
                                    </label>
                                    <input type="file" name="form_a_research_plans_pdf" accept="application/pdf"
                                        required
                                        class="w-full border border-gray-300 rounded-md text-sm p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Form B – Career Plans <span class="text-red-500">*</span>
                                    </label>
                                    <input type="file" name="form_b_career_plans_pdf" accept="application/pdf"
                                        required
                                        class="w-full border border-gray-300 rounded-md text-sm p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Form C – Health Status <span class="text-red-500">*</span>
                                    </label>
                                    <input type="file" name="form_c_health_status_pdf" accept="application/pdf"
                                        required
                                        class="w-full border border-gray-300 rounded-md text-sm p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        NBI Clearance <span class="text-red-500">*</span>
                                    </label>
                                    <input type="file" name="nbi_clearance_pdf" accept="application/pdf" required
                                        class="w-full border border-gray-300 rounded-md text-sm p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                </div>
                            </div>
                        </section>
                    </div>

                    <!-- 🔹 Navigation Buttons -->
                    <div class="flex justify-between mt-8 border-t pt-4">
                        <button type="button" onclick="prevStep(6)"
                            class="px-5 py-2 rounded-md bg-gray-100 text-gray-700 font-medium hover:bg-gray-200 transition-all">
                            Back
                        </button>
                        <button type="button" onclick="validateAndNext(7)"
                            class="px-5 py-2 rounded-md bg-blue-600 text-white font-medium hover:bg-blue-700 shadow-sm transition-all">
                            Next: Declaration
                        </button>
                    </div>
                </div>

                <!-- Step 8: Truthfulness of Data and Data Privacy -->
                <div class="step bg-white p-6 rounded-lg shadow-sm hidden" id="step8">
                    <h4 class="text-lg font-semibold mb-3 mt-6">VIII. TRUTHFULNESS OF DATA AND DATA PRIVACY</h4>

                    <div class="mb-6 text-sm text-gray-700 border p-4 rounded-md bg-gray-50">
                        <p class="mb-2">
                            I hereby certify that all information given above are true and correct to the best of my
                            knowledge. Any misinformation or withholding of information will automatically disqualify me
                            from the program, Project Science and Technology Regional Alliance of Universities for
                            National Development (STRAND). I am willing to refund all the financial benefits received
                            plus appropriate interest if such misinformation is discovered.
                        </p>
                        <p class="mb-2">
                            Moreover, I hereby authorize the Science Education Institute of the Department of Science
                            and Technology (SEI-DOST) to collect, record, organize, update or modify, retrieve, consult,
                            use, consolidate, block, erase or destruct my personal data that I have provided in relation
                            to my application to this scholarship. I hereby affirm my right to be informed, object to
                            processing, access and rectify, suspend or withdraw my personal data, and be indemnified in
                            case of damages pursuant to the provisions of the Republic Act No. 10173 of the Philippines,
                            Data Privacy Act of 2012 and its corresponding Implementing Rules and Regulations.
                        </p>

                        <div class="mt-4">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="terms_and_conditions_agreed" value="1" required
                                    class="form-checkbox h-5 w-5 text-blue-600">
                                <span class="ml-2 text-sm font-medium text-gray-900">I understand and agree to the
                                    terms and conditions stated above.</span>
                            </label>
                        </div>

                        <!-- Applicant Name and Date side by side -->
                        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Printed Name -->
                            <div>
                                <label for="applicant_signature"
                                    class="block text-sm font-medium text-gray-700">Printed Name of Applicant</label>
                                <input type="text" name="applicant_signature" id="applicant_signature"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm bg-gray-100"
                                    readonly required>
                            </div>

                            <!-- Date -->
                            <div>
                                <label for="declaration_date"
                                    class="block text-sm font-medium text-gray-700">Date</label>
                                <input type="date" name="declaration_date" id="declaration_date"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm bg-gray-100"
                                    readonly required>
                            </div>
                        </div>

                        <!-- E-Signature Section -->
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700">E-Signature:</label>
                            <canvas id="signature-pad" class="border border-gray-300 rounded-md bg-white mt-1"
                                width="400" height="150"></canvas>

                            <div class="mt-2 flex gap-3">
                                <button type="button" id="clear-signature"
                                    class="px-3 py-1 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400">Clear</button>
                            </div>

                            <!-- Hidden input to store the base64 signature image -->
                            <input type="hidden" name="signature_image" id="signature_image">
                        </div>

                        <!-- Navigation Buttons -->
                        <div class="flex justify-between mt-8">
                            <button type="button"
                                class="bg-gray-300 text-gray-800 px-6 py-2 rounded-md hover:bg-gray-400"
                                onclick="prevStep(7)">Back</button>
                            <button type="submit"
                                class="bg-blue-600 text-white px-6 py-3 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Submit Application
                            </button>
                        </div>
                    </div>
                </div>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        currentStep = 1;
                        updateStepIndicator();
                        attachEmploymentStatusListener();
                        attachDynamicFieldListeners();
                        attachStrandCategoryListener();
                        attachNumericValidation();
                        attachAgeCalculation();
                        attachAcademicYear();
                        attachSignaturePad();
                        attachIfEmployedListener();
                        attachDegreeButtons();
                        attachLiveValidation();
                        attachApplicantTypeToggle();
                    });

                    let currentStep = 1;
                    const totalSteps = 8;

                    /* ✅ Updated Validation Function */
                    function validateCurrentStep(step) {
                        const currentStepElement = document.getElementById(`step${step}`);
                        if (!currentStepElement) return true;

                        let isValid = true;
                        let firstInvalidInput = null;

                        // Get all required fields
                        const requiredInputs = currentStepElement.querySelectorAll(
                            'input[required], select[required], textarea[required]');

                        requiredInputs.forEach(input => {
                            if (input.disabled || input.offsetParent === null) return; // skip hidden/disabled

                            let valid = true;
                            if (input.type === 'radio') {
                                const group = currentStepElement.querySelectorAll(`input[name="${input.name}"]`);
                                const checked = Array.from(group).some(r => r.checked);
                                valid = checked;
                            } else if (input.type === 'checkbox') {
                                valid = input.checked;
                            } else if (input.type === 'file') {
                                valid = input.files && input.files.length > 0;
                            } else {
                                valid = input.value.trim() !== '';
                            }

                            // Toggle red border
                            if (!valid) {
                                isValid = false;
                                firstInvalidInput = firstInvalidInput || input;
                                input.classList.add('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
                            } else {
                                input.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
                            }
                        });

                        // Conditional validation for employment
                        const employmentStatus = document.querySelector('input[name="employment_status"]:checked');
                        if (step === 5 && employmentStatus) {
                            if (['Permanent', 'Contractual', 'Probationary'].includes(employmentStatus.value)) {
                                const employedFields = document.getElementById('employed_fields');
                                if (!employedFields.classList.contains('hidden')) {
                                    employedFields.querySelectorAll('input[required]').forEach(input => {
                                        if (input.value.trim() === '') {
                                            isValid = false;
                                            firstInvalidInput = firstInvalidInput || input;
                                        }
                                    });
                                }
                            } else if (employmentStatus.value === 'Self-employed') {
                                const selfFields = document.getElementById('self_employed_fields');
                                if (!selfFields.classList.contains('hidden')) {
                                    selfFields.querySelectorAll('input[required]').forEach(input => {
                                        if (input.value.trim() === '') {
                                            isValid = false;
                                            firstInvalidInput = firstInvalidInput || input;
                                        }
                                    });
                                }
                            }
                        }

                        // Focus + alert
                        if (!isValid && firstInvalidInput) {
                            firstInvalidInput.scrollIntoView({
                                behavior: 'smooth',
                                block: 'center'
                            });
                            alert('⚠️ Please fill out all required fields before proceeding.');
                        }

                        return isValid;
                    }

                    /* ✅ Real-time validation feedback */
                    function attachLiveValidation() {
                        const allInputs = document.querySelectorAll('input[required], select[required], textarea[required]');

                        allInputs.forEach(input => {
                            input.addEventListener('input', () => {
                                if (input.type === 'file') {
                                    if (input.files && input.files.length > 0) {
                                        input.classList.remove('border-red-500', 'focus:border-red-500',
                                            'focus:ring-red-500');
                                    }
                                } else if (input.value.trim() !== '') {
                                    input.classList.remove('border-red-500', 'focus:border-red-500',
                                        'focus:ring-red-500');
                                }
                            });

                            input.addEventListener('change', () => {
                                if (input.type === 'radio' || input.type === 'checkbox' || input.tagName === 'SELECT') {
                                    input.classList.remove('border-red-500', 'focus:border-red-500',
                                        'focus:ring-red-500');
                                }
                            });
                        });
                    }

                    /* ✅ Navigation and Step Functions */
                    function validateAndNext(step) {
                        if (validateCurrentStep(currentStep)) {
                            document.getElementById(`step${currentStep}`).classList.add('hidden');
                            currentStep = step + 1;
                            document.getElementById(`step${currentStep}`).classList.remove('hidden');
                            updateStepIndicator();
                        }
                    }

                    function nextStep(step) {
                        document.getElementById(`step${currentStep}`).classList.add('hidden');
                        currentStep = step;
                        document.getElementById(`step${currentStep}`).classList.remove('hidden');
                        updateStepIndicator();
                    }

                    function prevStep(step) {
                        document.getElementById(`step${currentStep}`).classList.add('hidden');
                        currentStep = step;
                        document.getElementById(`step${currentStep}`).classList.remove('hidden');
                        updateStepIndicator();
                    }

                    function goToStep(step) {
                        if (step < currentStep) prevStep(step);
                        else if (step > currentStep) nextStep(step);
                    }

                    function updateStepIndicator() {
                        const progressBar = document.getElementById('progress-bar');
                        const progressPercentage = ((currentStep - 1) / (totalSteps - 1)) * 100;
                        progressBar.style.width = `${progressPercentage}%`;

                        document.querySelectorAll('.step-indicator').forEach(indicator => {
                            const stepNumber = parseInt(indicator.dataset.step);
                            const circle = indicator.querySelector('div:first-child');
                            const label = indicator.querySelector('span');

                            circle.classList.remove('bg-blue-600', 'bg-gray-200', 'text-white', 'text-gray-600');
                            label.classList.remove('text-blue-600', 'text-gray-500');

                            if (stepNumber === currentStep || stepNumber < currentStep) {
                                circle.classList.add('bg-blue-600', 'text-white');
                                label.classList.add('text-blue-600');
                            } else {
                                circle.classList.add('bg-gray-200', 'text-gray-600');
                                label.classList.add('text-gray-500');
                            }
                        });
                    }

                    function autoFillSignatureAndDate() {
                        const firstName = document.getElementById("first_name")?.value || "";
                        const lastName = document.getElementById("last_name")?.value || "";
                        const fullName = `${firstName} ${lastName}`.trim();

                        const signatureInput = document.getElementById("applicant_signature");
                        if (signatureInput) signatureInput.value = fullName;

                        const today = new Date().toISOString().split("T")[0];
                        const dateInput = document.getElementById("declaration_date");
                        if (dateInput) dateInput.value = today;
                    }

                    // Run on load + whenever user updates name
                    document.addEventListener("DOMContentLoaded", autoFillSignatureAndDate);
                    document.getElementById("first_name")?.addEventListener("input", autoFillSignatureAndDate);
                    document.getElementById("last_name")?.addEventListener("input", autoFillSignatureAndDate);


                    function validateAndNext(step) {
                        if (validateCurrentStep(currentStep)) {
                            document.getElementById(`step${currentStep}`).classList.add('hidden');
                            currentStep = step + 1;
                            document.getElementById(`step${currentStep}`).classList.remove('hidden');
                            updateStepIndicator();
                        } else {
                            alert('Please fill in all required fields before proceeding.');
                        }
                    }

                    function nextStep(step) {
                        document.getElementById(`step${currentStep}`).classList.add('hidden');
                        currentStep = step;
                        document.getElementById(`step${currentStep}`).classList.remove('hidden');
                        updateStepIndicator();
                    }

                    function prevStep(step) {
                        document.getElementById(`step${currentStep}`).classList.add('hidden');
                        currentStep = step;
                        document.getElementById(`step${currentStep}`).classList.remove('hidden');
                        updateStepIndicator();
                    }

                    function goToStep(step) {
                        if (step < currentStep) {
                            prevStep(step);
                        } else if (step > currentStep) {
                            nextStep(step);
                        }
                    }

                    function updateStepIndicator() {
                        const progressBar = document.getElementById('progress-bar');
                        const progressPercentage = ((currentStep - 1) / (totalSteps - 1)) * 100;
                        progressBar.style.width = `${progressPercentage}%`;

                        document.querySelectorAll('.step-indicator').forEach((indicator, index) => {
                            const stepNumber = parseInt(indicator.dataset.step);
                            const circle = indicator.querySelector('div:first-child');
                            const label = indicator.querySelector('span');

                            circle.classList.remove('bg-blue-600', 'bg-gray-200', 'border-blue-100', 'border-gray-100',
                                'text-white', 'text-gray-600');
                            label.classList.remove('text-blue-600', 'text-gray-500');

                            if (stepNumber === currentStep) {
                                circle.classList.add('bg-blue-600', 'border-blue-100', 'text-white');
                                label.classList.add('text-blue-600');
                            } else if (stepNumber < currentStep) {
                                circle.classList.add('bg-blue-600', 'border-blue-100', 'text-white');
                                label.classList.add('text-blue-600');
                            } else {
                                circle.classList.add('bg-gray-200', 'border-gray-100', 'text-gray-600');
                                label.classList.add('text-gray-500');
                            }
                        });
                    }

                    function attachEmploymentStatusListeners() {

    const employmentRadios = document.querySelectorAll('input[name="employment_status"]');

    const employedFields = document.getElementById('employed_fields');
    const selfEmployedFields = document.getElementById('self_employed_fields');
    const employedUploadSection = document.getElementById('if_employed_section');

    if (!employmentRadios.length) return;

    employmentRadios.forEach(radio => {
        radio.addEventListener('change', function () {

            // Reset visibility
            employedFields.classList.add('hidden');
            selfEmployedFields.classList.add('hidden');
            employedUploadSection.classList.add('hidden');

            // Remove all required attributes (clean slate)
            employedFields.querySelectorAll('input').forEach(el => el.required = false);
            selfEmployedFields.querySelectorAll('input').forEach(el => el.required = false);

            // EMPLOYED → show employment inputs + upload documents
            if (['Permanent', 'Contractual', 'Probationary'].includes(this.value)) {

                employedFields.classList.remove('hidden');
                employedUploadSection.classList.remove('hidden');

                // Make employed fields required
                employedFields.querySelectorAll('input').forEach(el => el.required = true);
            }

            // SELF-EMPLOYED → show business fields only
            if (this.value === 'Self-employed') {

                selfEmployedFields.classList.remove('hidden');

                // Make self-employed fields required
                selfEmployedFields.querySelectorAll('input').forEach(el => el.required = true);
            }

            // UNEMPLOYED → all hidden, nothing required
        });
    });
}

                    // ✅ Function to handle "New Applicant" vs "Lateral Applicant" toggle
                    function attachApplicantTypeToggle() {
                        const applicantRadios = document.querySelectorAll('input[name="applicant_status"]');
                        const newSection = document.getElementById('newApplicantSection');
                        const lateralSection = document.getElementById('lateralApplicantSection');

                        applicantRadios.forEach(radio => {
                            radio.addEventListener('change', function() {
                                if (this.value === 'new') {
                                    newSection.classList.remove('hidden');
                                    lateralSection.classList.add('hidden');
                                } else if (this.value === 'lateral') {
                                    newSection.classList.add('hidden');
                                    lateralSection.classList.remove('hidden');
                                }
                            });
                        });
                    }

                    function attachDegreeButtons() {
                        const degreeButtonsContainer = document.getElementById("degree-buttons");

                        window.showDegree = function(sectionId, button) {
                            const section = document.getElementById(sectionId);
                            const buttonsContainer = document.getElementById("degree-buttons");
                            section.classList.remove("hidden");
                            section.insertAdjacentElement("afterend", buttonsContainer);

                            // ✅ Make all text inputs in this section required
                            section.querySelectorAll('input[type="text"]').forEach(input => {
                                input.required = true;
                            });

                            // Disable this button to prevent duplicate sections
                            button.disabled = true;
                        };

                        window.hideDegree = function(sectionId) {
                            const section = document.getElementById(sectionId);
                            const buttonsContainer = document.getElementById("degree-buttons");
                            section.classList.add("hidden");

                            // ✅ Remove "required" from hidden inputs
                            section.querySelectorAll('input[type="text"]').forEach(input => {
                                input.required = false;
                            });

                            // Place buttons back under BS section
                            const bsSection = document.querySelector("#step3 > .bg-gray-50");
                            bsSection.insertAdjacentElement("afterend", buttonsContainer);

                            // ✅ Re-enable button for this section
                            const relatedButton =
                                sectionId === "ms-degree-section" ?
                                document.querySelector('button[onclick*="ms-degree-section"]') :
                                document.querySelector('button[onclick*="phd-degree-section"]');
                            if (relatedButton) relatedButton.disabled = false;
                        };
                    }

                    function attachStrandCategoryListener() {
                        document.querySelectorAll('input[name="strand_category"]').forEach(radio => {
                            radio.addEventListener('change', function() {
                                const strand2Fields = document.getElementById('strand2_fields');
                                if (this.value === 'STRAND 2') {
                                    strand2Fields.classList.remove('hidden');
                                } else {
                                    strand2Fields.classList.add('hidden');
                                }
                            });
                        });
                    }

                    function attachDynamicFieldListeners() {
                        // --- Research & Development Involvement ---
                        let rdInvolvementCount = 1;
                        document.getElementById('add_rd_involvement').addEventListener('click', function() {
                            rdInvolvementCount++;
                            const container = document.getElementById('rd_involvement_container');
                            const newItem = document.createElement('div');
                            newItem.classList.add('rd_involvement_item', 'grid', 'grid-cols-1', 'md:grid-cols-4', 'gap-4',
                                'border', 'p-3', 'rounded-md');
                            newItem.innerHTML = `
            <div>
                <label for="rd_field_title_${rdInvolvementCount}" class="block text-sm font-medium text-gray-700">
                    FIELD AND TITLE OF RESEARCH <span class="text-red-500">*</span>
                </label>
                <input type="text" name="rd_involvement[${rdInvolvementCount - 1}][field_title]" 
                    id="rd_field_title_${rdInvolvementCount}" 
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm"
                    required>
            </div>
            <div>
                <label for="rd_location_duration_${rdInvolvementCount}" class="block text-sm font-medium text-gray-700">
                    LOCATION/DURATION <span class="text-red-500">*</span>
                </label>
                <input type="text" name="rd_involvement[${rdInvolvementCount - 1}][location_duration]" 
                    id="rd_location_duration_${rdInvolvementCount}" 
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm"
                    required>
            </div>
            <div>
                <label for="rd_fund_source_${rdInvolvementCount}" class="block text-sm font-medium text-gray-700">
                    FUND SOURCE
                </label>
                <input type="text" name="rd_involvement[${rdInvolvementCount - 1}][fund_source]" 
                    id="rd_fund_source_${rdInvolvementCount}" 
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
            </div>
            <div>
                <label for="rd_nature_of_involvement_${rdInvolvementCount}" class="block text-sm font-medium text-gray-700">
                    NATURE OF INVOLVEMENT
                </label>
                <input type="text" name="rd_involvement[${rdInvolvementCount - 1}][nature_of_involvement]" 
                    id="rd_nature_of_involvement_${rdInvolvementCount}" 
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
            </div>
        `;
                            container.appendChild(newItem);
                        });

                        // --- Publications ---
                        let publicationCount = 1;
                        document.getElementById('add_publication').addEventListener('click', function() {
                            publicationCount++;
                            const container = document.getElementById('publications_container');
                            const newItem = document.createElement('div');
                            newItem.classList.add('publication_item', 'grid', 'grid-cols-1', 'md:grid-cols-3', 'gap-4',
                                'border', 'p-3', 'rounded-md');
                            newItem.innerHTML = `
            <div>
                <label for="pub_title_${publicationCount}" class="block text-sm font-medium text-gray-700">
                    TITLE OF ARTICLE <span class="text-red-500">*</span>
                </label>
                <input type="text" name="publications[${publicationCount - 1}][title]" 
                    id="pub_title_${publicationCount}" 
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm"
                    required>
            </div>
            <div>
                <label for="pub_name_year_${publicationCount}" class="block text-sm font-medium text-gray-700">
                    NAME / YEAR OF PUBLICATION
                </label>
                <input type="text" name="publications[${publicationCount - 1}][name_year]" 
                    id="pub_name_year_${publicationCount}" 
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
            </div>
            <div>
                <label for="pub_nature_of_involvement_${publicationCount}" class="block text-sm font-medium text-gray-700">
                    NATURE OF INVOLVEMENT
                </label>
                <input type="text" name="publications[${publicationCount - 1}][nature_of_involvement]" 
                    id="pub_nature_of_involvement_${publicationCount}" 
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
            </div>
        `;
                            container.appendChild(newItem);
                        });

                        // --- Awards ---
                        let awardCount = 1;
                        document.getElementById('add_award').addEventListener('click', function() {
                            awardCount++;
                            const container = document.getElementById('awards_container');
                            const newItem = document.createElement('div');
                            newItem.classList.add('award_item', 'grid', 'grid-cols-1', 'md:grid-cols-3', 'gap-4', 'border',
                                'p-3', 'rounded-md');
                            newItem.innerHTML = `
            <div>
                <label for="award_title_${awardCount}" class="block text-sm font-medium text-gray-700">
                    TITLE OF AWARD <span class="text-red-500">*</span>
                </label>
                <input type="text" name="awards[${awardCount - 1}][title]" 
                    id="award_title_${awardCount}" 
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm"
                    required>
            </div>
            <div>
                <label for="award_giving_body_${awardCount}" class="block text-sm font-medium text-gray-700">
                    AWARD GIVING BODY
                </label>
                <input type="text" name="awards[${awardCount - 1}][giving_body]" 
                    id="award_giving_body_${awardCount}" 
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
            </div>
            <div>
                <label for="award_year_${awardCount}" class="block text-sm font-medium text-gray-700">
                    YEAR OF AWARD
                </label>
                <input type="text" name="awards[${awardCount - 1}][year]" 
                    id="award_year_${awardCount}" 
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
            </div>
        `;
                            container.appendChild(newItem);
                        });
                    }

                    // ✅ Numeric-only validation
                    function attachNumericValidation() {
                        document.querySelectorAll(".numeric-only").forEach(input => {
                            input.addEventListener("input", function() {
                                this.value = this.value.replace(/[^0-9]/g, "");
                            });
                        });
                    }

                    function attachSignaturePad() {
                        const canvas = document.getElementById('signature-pad');
                        if (!canvas) return;
                        const ctx = canvas.getContext('2d');
                        const clearBtn = document.getElementById('clear-signature');
                        const signatureInput = document.getElementById('signature_image');
                        let drawing = false;

                        canvas.addEventListener('mousedown', e => {
                            drawing = true;
                            ctx.beginPath();
                            ctx.moveTo(e.offsetX, e.offsetY);
                        });

                        canvas.addEventListener('mousemove', e => {
                            if (drawing) {
                                ctx.lineTo(e.offsetX, e.offsetY);
                                ctx.strokeStyle = '#000';
                                ctx.lineWidth = 2;
                                ctx.lineCap = 'round';
                                ctx.stroke();
                            }
                        });

                        canvas.addEventListener('mouseup', () => {
                            drawing = false;
                            signatureInput.value = canvas.toDataURL('image/png');
                        });

                        canvas.addEventListener('mouseleave', () => {
                            if (drawing) {
                                drawing = false;
                                signatureInput.value = canvas.toDataURL('image/png');
                            }
                        });

                        clearBtn.addEventListener('click', () => {
                            ctx.clearRect(0, 0, canvas.width, canvas.height);
                            signatureInput.value = '';
                        });
                    }
                    // ✅ Auto Age Calculation
                    function attachAgeCalculation() {
                        const dobInput = document.getElementById("date_of_birth");
                        const ageInput = document.getElementById("age");

                        if (dobInput && ageInput) {
                            dobInput.addEventListener("change", function() {
                                const dob = new Date(this.value);
                                if (!isNaN(dob.getTime())) {
                                    const today = new Date();
                                    let age = today.getFullYear() - dob.getFullYear();
                                    const m = today.getMonth() - dob.getMonth();
                                    if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) {
                                        age--;
                                    }
                                    ageInput.value = age >= 0 ? age : "";
                                } else {
                                    ageInput.value = "";
                                }
                            });
                        }
                    }

                    // ✅ Auto Academic Year
                    function attachAcademicYear() {
                        const academicYearInput = document.getElementById("academic_year");
                        if (academicYearInput) {
                            const today = new Date();
                            let year = today.getFullYear();
                            let month = today.getMonth() + 1;

                            let startYear, endYear;
                            if (month >= 6) {
                                startYear = year;
                                endYear = year + 1;
                            } else {
                                startYear = year - 1;
                                endYear = year;
                            }

                            academicYearInput.value = `${startYear}-${endYear}`;
                        }
                    }
                    document.addEventListener('DOMContentLoaded', function() {
                        const provinceSelect = document.getElementById('province_select');
                        const citySelect = document.getElementById('city_select');
                        const barangaySelect = document.getElementById('barangay_select');
                        const regionInput = document.getElementById('region_select'); // readonly
                        const zipInput = document.getElementById('zip_code');

                        // 🔹 Fallback ZIPs by Region
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

                        // 🔹 Automatically set Region & ZIP
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

                                    // ✅ Auto-fill Region and ZIP
                                    regionInput.value = region.name;
                                    regionInput.dataset.code = region.code;
                                    zipInput.value = city.zipcode || prov.zipcode || regionZipFallback[region.code] || "";
                                }

                                if (level === "barangays") {
                                    const brgy = await fetch(`https://psgc.gitlab.io/api/barangays/${code}/`).then(r => r
                                        .json());

                                    // ✅ Some barangays use "municipalityCode" instead of "cityCode"
                                    const cityCode = brgy.cityCode || brgy.municipalityCode;
                                    if (!cityCode) {
                                        console.warn("Barangay has no city/municipality code:", brgy);
                                        return;
                                    }

                                    const city = await fetch(
                                        `https://psgc.gitlab.io/api/cities-municipalities/${cityCode}/`).then(r => r
                                        .json());
                                    const prov = await fetch(`https://psgc.gitlab.io/api/provinces/${city.provinceCode}/`)
                                        .then(r => r.json());
                                    const region = await fetch(`https://psgc.gitlab.io/api/regions/${prov.regionCode}/`)
                                        .then(r => r.json());

                                    // ✅ Always populate Region safely
                                    regionInput.value = region.name || "Unknown Region";
                                    regionInput.dataset.code = region.code || "";
                                    zipInput.value = brgy.zipcode || city.zipcode || prov.zipcode || regionZipFallback[
                                        region.code] || "";
                                }
                            } catch (err) {
                                console.error("Error setting location:", err);
                            }
                        }

                        // 🔹 Load all provinces
                        fetch('https://psgc.gitlab.io/api/provinces/')
                            .then(res => res.json())
                            .then(data => data.forEach(p => provinceSelect.add(new Option(p.name, p.code))))
                            .catch(err => console.error('Error loading provinces:', err));

                        // 🔹 Province → City / Municipality
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

                        // 🔹 City → Auto-fill Region + Load Barangays
                        citySelect.addEventListener('change', function() {
                            const cityCode = this.value;
                            barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
                            if (!cityCode) return;

                            // ✅ Auto-fill Region and ZIP
                            setLocation("cities-municipalities", cityCode);

                            fetch(`https://psgc.gitlab.io/api/cities-municipalities/${cityCode}/barangays/`)
                                .then(res => res.json())
                                .then(data => data.forEach(b => barangaySelect.add(new Option(b.name, b.code))))
                                .catch(err => console.error('Error loading barangays:', err));
                        });

                        // 🔹 Barangay → Finalize ZIP & Region
                        barangaySelect.addEventListener('change', function() {
                            const brgyCode = this.value;
                            if (!brgyCode) return;
                            setLocation("barangays", brgyCode);
                        });
                    });
                </script>
            </form>
</x-app-layout>
