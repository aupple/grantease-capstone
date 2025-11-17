<x-ched-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Personal Information') }}
        </h2>
    </x-slot>

    <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8 bg-gray-50">
        <div class="max-w-4xl mx-auto">
            <!-- Step Indicator -->
            <div class="mb-8">
                <div class="flex items-center justify-center">
                    <!-- Step 1 -->
                    <div class="flex flex-col items-center relative">
                        <div id="step1-circle"
                            class="w-12 h-12 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-lg z-10 transition-all duration-300">
                            1
                        </div>
                        <p id="step1-label" class="text-sm mt-2 font-medium text-blue-600 transition-all duration-300">
                            Basic Info</p>
                    </div>

                    <!-- Connector Line -->
                    <div id="connector" class="w-32 h-1 bg-gray-300 mx-4 transition-all duration-300"></div>

                    <!-- Step 2 -->
                    <div class="flex flex-col items-center relative">
                        <div id="step2-circle"
                            class="w-12 h-12 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center font-semibold text-lg z-10 transition-all duration-300">
                            2
                        </div>
                        <p id="step2-label" class="text-sm mt-2 text-gray-500 transition-all duration-300">Personal Info
                        </p>
                    </div>
                </div>
            </div>

            <!-- Form Container -->
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <!-- Form Title -->
                <div class="text-center mb-8">
                    <p class="text-gray-600">Please fill out all required fields accurately</p>
                </div>

                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded-lg">
                        <div class="flex">
                            <svg class="h-6 w-6 text-red-400 mr-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            <div>
                                <h3 class="text-sm font-semibold text-red-800">Please correct the following errors:</h3>
                                <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <form id="personalInfoForm" action="{{ route('ched.personal-form.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf

                    <!-- Step 1: Basic Information -->
                    <div id="step1" class="step-content">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Academic Year -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Academic Year</label>
                                <input type="text" name="academic_year"
                                    value="{{ old('academic_year', '2025-2026') }}" readonly
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>

                            <!-- School Term -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">School Term <span
                                        class="text-red-500">*</span></label>
                                <select name="school_term" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Select Term</option>
                                    <option value="1st Semester"
                                        {{ old('school_term') == '1st Semester' ? 'selected' : '' }}>1st Semester
                                    </option>
                                    <option value="2nd Semester"
                                        {{ old('school_term') == '2nd Semester' ? 'selected' : '' }}>2nd Semester
                                    </option>
                                    <option value="Summer" {{ old('school_term') == 'Summer' ? 'selected' : '' }}>Summer
                                    </option>
                                </select>
                            </div>

                            <!-- School/University -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">School/University <span
                                        class="text-red-500">*</span></label>
                                <input type="text" name="school" required value="{{ old('school') }}"
                                    placeholder="Enter your school/university"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>

                            <!-- Year Level -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Level <span
                                        class="text-red-500">*</span></label>
                                <select name="year_level" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Select Level</option>
                                    <option value="MS"
                                        {{ old('year_level') == "Master's Degree" ? 'selected' : '' }}>Master's Degree
                                        (MS/MA)</option>
                                    <option value="PhD"
                                        {{ old('year_level') == 'Doctoral Degree' ? 'selected' : '' }}>Doctoral Degree
                                        (PhD)</option>
                                </select>
                            </div>

                            <!-- Application No - DYNAMIC -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Application No.</label>
                                <input type="text" name="application_no"
                                    value="{{ old('application_no', 'CHED-' . strtoupper(uniqid())) }}" readonly
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>

                            <!-- Passport Size Picture -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Attach Passport Size
                                    Picture <span class="text-red-500">*</span></label>
                                <input type="file" name="passport_photo" accept="image/*" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                <p class="text-xs text-gray-500 mt-1">Maximum size: 2MB (JPG, PNG)</p>
                            </div>
                        </div>

                        <!-- Next Button -->
                        <div class="flex justify-end mt-8">
                            <button type="button" id="nextBtn"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-8 py-3 rounded-lg transition shadow-md">
                                Next: Personal Info
                            </button>
                        </div>
                    </div>

                    <!-- Step 2: Personal Information -->
                    <div id="step2" class="step-content hidden">
                        <!-- Personal Information Section -->
                        <div class="mb-8">
                            <h2 class="text-xl font-bold text-blue-700 mb-6">I. PERSONAL INFORMATION</h2>

                            <!-- Name Fields -->
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Last Name <span
                                            class="text-red-500">*</span></label>
                                    <input type="text" name="last_name" required
                                        value="{{ old('last_name', auth()->user()->last_name ?? '') }}"
                                        placeholder="Enter last name"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">First Name <span
                                            class="text-red-500">*</span></label>
                                    <input type="text" name="first_name" required
                                        value="{{ old('first_name', auth()->user()->first_name ?? '') }}"
                                        placeholder="Enter first name"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Middle Name</label>
                                    <input type="text" name="middle_name"
                                        value="{{ old('middle_name', auth()->user()->middle_name ?? '') }}"
                                        placeholder="Enter middle name"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Suffix</label>
                                    <input type="text" name="suffix" value="{{ old('suffix') }}"
                                        placeholder="Jr., Sr., III (optional)"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                            </div>

                            <!-- Permanent Address -->
                            <h3 class="text-md font-semibold text-gray-800 mb-4">Permanent Address</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Province <span class="text-red-500">*</span>
                                    </label>
                                    <select id="province_select" name="province" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                        <option value="">Select Province</option>
                                    </select>
                                </div>

                                <!-- City / Municipality -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        City / Municipality <span class="text-red-500">*</span>
                                    </label>
                                    <select id="city_select" name="city" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                        <option value="">Select City / Municipality</option>
                                    </select>
                                </div>

                                <!-- Barangay -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Barangay <span class="text-red-500">*</span>
                                    </label>
                                    <select id="barangay_select" name="barangay" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                        <option value="">Select Barangay</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Street <span
                                            class="text-red-500">*</span></label>
                                    <input type="text" name="street" required value="{{ old('street') }}"
                                        placeholder="Enter street name"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">House No.</label>
                                    <input type="text" name="house_no" value="{{ old('house_no') }}"
                                        placeholder="House number (optional)"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Zip Code <span
                                            class="text-red-500">*</span></label>
                                    <input type="text" name="zip_code" required value="{{ old('zip_code') }}"
                                        placeholder="e.g., 9000"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Region <span
                                            class="text-red-500">*</span></label>
                                    <input type="text" name="region" required
                                        value="{{ old('region', 'Region X') }}" placeholder="e.g., Region X"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">District</label>
                                    <select name="district"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="">Select District</option>
                                        <option value="District 1"
                                            {{ old('district') == 'District 1' ? 'selected' : '' }}>District 1</option>
                                        <option value="District 2"
                                            {{ old('district') == 'District 2' ? 'selected' : '' }}>District 2</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Passport No.</label>
                                <input type="text" name="passport_no" value="{{ old('passport_no') }}"
                                    placeholder="e.g., P1234567 (optional)"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="mb-8">
                            <h3 class="text-md font-semibold text-gray-800 mb-4">Contact Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Address <span
                                            class="text-red-500">*</span></label>
                                    <input type="email" name="email" required
                                        value="{{ old('email', auth()->user()->email ?? '') }}"
                                        placeholder="your.email@example.com"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Mailing Address</label>
                                    <input type="text" name="mailing_address"
                                        value="{{ old('mailing_address') }}"
                                        placeholder="If different from permanent address"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Telephone / Mobile
                                        Number <span class="text-red-500">*</span></label>
                                    <input type="tel" name="contact_no" required value="{{ old('contact_no') }}"
                                        placeholder="09XXXXXXXXX"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                            </div>
                        </div>

                        <!-- Personal Details -->
                        <div class="mb-8">
                            <h3 class="text-md font-semibold text-gray-800 mb-4">Personal Details</h3>
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Civil Status <span
                                            class="text-red-500">*</span></label>
                                    <select name="civil_status" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="">Select</option>
                                        <option value="Single"
                                            {{ old('civil_status') == 'Single' ? 'selected' : '' }}>Single</option>
                                        <option value="Married"
                                            {{ old('civil_status') == 'Married' ? 'selected' : '' }}>Married</option>
                                        <option value="Widowed"
                                            {{ old('civil_status') == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                                        <option value="Separated"
                                            {{ old('civil_status') == 'Separated' ? 'selected' : '' }}>Separated
                                        </option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Date of Birth <span
                                            class="text-red-500">*</span></label>
                                    <input type="date" name="date_of_birth" required
                                        value="{{ old('date_of_birth') }}"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Age</label>
                                    <input type="number" name="age" value="{{ old('age') }}"
                                        placeholder="Auto-calculated" readonly
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Sex <span
                                            class="text-red-500">*</span></label>
                                    <select name="sex" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="">Select</option>
                                        <option value="Male" {{ old('sex') == 'Male' ? 'selected' : '' }}>Male
                                        </option>
                                        <option value="Female" {{ old('sex') == 'Female' ? 'selected' : '' }}>Female
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Parents -->
                        <div class="mb-8">
                            <h3 class="text-md font-semibold text-gray-800 mb-4">Parents</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Father's Name</label>
                                    <input type="text" name="father_name" value="{{ old('father_name') }}"
                                        placeholder="Enter father's full name"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Mother's Name</label>
                                    <input type="text" name="mother_name" value="{{ old('mother_name') }}"
                                        placeholder="Enter mother's full name"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-between mt-8">
                            <button type="button" id="backBtn"
                                class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold px-8 py-3 rounded-lg transition">
                                Back
                            </button>
                            <button type="submit"
                                class="bg-green-600 hover:bg-green-700 text-white font-semibold px-8 py-3 rounded-lg transition shadow-md">
                                Submit Application
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // ------------------------------------------------------------
            // STEP NAVIGATION
            // ------------------------------------------------------------
            const step1 = document.getElementById('step1');
            const step2 = document.getElementById('step2');
            const nextBtn = document.getElementById('nextBtn');
            const backBtn = document.getElementById('backBtn');
            const step1Circle = document.getElementById('step1-circle');
            const step2Circle = document.getElementById('step2-circle');
            const step1Label = document.getElementById('step1-label');
            const step2Label = document.getElementById('step2-label');
            const connector = document.getElementById('connector');

            nextBtn.addEventListener('click', function() {
                const schoolTerm = document.querySelector('[name="school_term"]').value;
                const passportPhoto = document.querySelector('[name="passport_photo"]').value;

                if (!schoolTerm || !passportPhoto) {
                    alert('Please fill in all required fields in Step 1');
                    return;
                }

                step1.classList.add('hidden');
                step2.classList.remove('hidden');

                step1Circle.classList.remove('bg-blue-600', 'text-white');
                step1Circle.classList.add('bg-green-600', 'text-white');
                step1Circle.innerHTML = '✓';
                step1Label.classList.remove('text-blue-600');
                step1Label.classList.add('text-green-600');

                step2Circle.classList.remove('bg-gray-300', 'text-gray-600');
                step2Circle.classList.add('bg-blue-600', 'text-white');
                step2Label.classList.remove('text-gray-500');
                step2Label.classList.add('text-blue-600', 'font-medium');

                connector.classList.remove('bg-gray-300');
                connector.classList.add('bg-blue-600');

                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });

            backBtn.addEventListener('click', function() {
                step1.classList.remove('hidden');
                step2.classList.add('hidden');

                step1Circle.classList.remove('bg-green-600');
                step1Circle.classList.add('bg-blue-600');
                step1Circle.innerHTML = '1';
                step1Label.classList.remove('text-green-600');
                step1Label.classList.add('text-blue-600');

                step2Circle.classList.remove('bg-blue-600', 'text-white');
                step2Circle.classList.add('bg-gray-300', 'text-gray-600');
                step2Label.classList.remove('text-blue-600', 'font-medium');
                step2Label.classList.add('text-gray-500');

                connector.classList.remove('bg-blue-600');
                connector.classList.add('bg-gray-300');

                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });

            // ------------------------------------------------------------
            // AUTO-CALCULATE AGE
            // ------------------------------------------------------------
            document.querySelector('[name="date_of_birth"]').addEventListener('change', function() {
                const dob = new Date(this.value);
                const today = new Date();
                let age = today.getFullYear() - dob.getFullYear();
                const monthDiff = today.getMonth() - dob.getMonth();

                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
                    age--;
                }

                document.querySelector('[name="age"]').value = age;
            });

            // ------------------------------------------------------------
            // PSGC — Province → City → Barangay (Step 2)
            // ------------------------------------------------------------
            document.addEventListener('DOMContentLoaded', async () => {
                const provinceSelect = document.getElementById('province_select');
                const citySelect = document.getElementById('city_select');
                const barangaySelect = document.getElementById('barangay_select');
                const regionInput = document.getElementById('regionInput'); // optional hidden input
                const zipInput = document.getElementById('zipInput'); // optional hidden input
                const regionZipFallback = {}; // optional fallback

                async function setLocation(level, code) {
                    try {
                        if (level === "provinces") {
                            const prov = await fetch(`https://psgc.gitlab.io/api/provinces/${code}/`).then(r =>
                                r.json());
                            const region = await fetch(`https://psgc.gitlab.io/api/regions/${prov.regionCode}/`)
                                .then(r => r.json());
                            if (regionInput) regionInput.value = region.name;
                            if (zipInput) zipInput.value = prov.zipcode || regionZipFallback[region.code] || "";
                        }
                        if (level === "cities-municipalities") {
                            const city = await fetch(
                                `https://psgc.gitlab.io/api/cities-municipalities/${code}/`).then(r => r
                                .json());
                            const prov = await fetch(
                                `https://psgc.gitlab.io/api/provinces/${city.provinceCode}/`).then(r => r
                                .json());
                            const region = await fetch(`https://psgc.gitlab.io/api/regions/${prov.regionCode}/`)
                                .then(r => r.json());
                            if (regionInput) regionInput.value = region.name;
                            if (zipInput) zipInput.value = city.zipcode || prov.zipcode || regionZipFallback[
                                region.code] || "";
                        }
                        if (level === "barangays") {
                            const brgy = await fetch(`https://psgc.gitlab.io/api/barangays/${code}/`).then(r =>
                                r.json());
                            const cityCode = brgy.cityCode || brgy.municipalityCode;
                            if (!cityCode) return;
                            const city = await fetch(
                                `https://psgc.gitlab.io/api/cities-municipalities/${cityCode}/`).then(r => r
                                .json());
                            const prov = await fetch(
                                `https://psgc.gitlab.io/api/provinces/${city.provinceCode}/`).then(r => r
                                .json());
                            const region = await fetch(`https://psgc.gitlab.io/api/regions/${prov.regionCode}/`)
                                .then(r => r.json());
                            if (regionInput) regionInput.value = region.name || "Unknown Region";
                            if (zipInput) zipInput.value = brgy.zipcode || city.zipcode || prov.zipcode ||
                                regionZipFallback[region.code] || "";
                        }
                    } catch (err) {
                        console.error("Error setting location:", err);
                    }
                }

                // Load provinces
                fetch('https://psgc.gitlab.io/api/provinces/')
                    .then(res => res.json())
                    .then(data => {
                        provinceSelect.innerHTML = '<option value="">Select Province</option>';
                        data.forEach(p => provinceSelect.add(new Option(p.name, p.code)));
                    })
                    .catch(err => console.error('Error loading provinces:', err));

                // Province → City
                provinceSelect.addEventListener('change', function() {
                    const provCode = this.value;
                    citySelect.innerHTML = '<option value="">Select City / Municipality</option>';
                    barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
                    if (!provCode) return;
                    setLocation("provinces", provCode);

                    fetch(`https://psgc.gitlab.io/api/provinces/${provCode}/cities-municipalities/`)
                        .then(res => res.json())
                        .then(data => data.forEach(c => citySelect.add(new Option(c.name, c.code))))
                        .catch(err => console.error('Error loading cities:', err));
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

                // Barangay → finalize region & ZIP
                barangaySelect.addEventListener('change', function() {
                    const brgyCode = this.value;
                    if (!brgyCode) return;
                    setLocation("barangays", brgyCode);
                });
            });
        </script>
    @endpush
</x-ched-layout>
