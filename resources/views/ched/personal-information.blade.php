<x-ched-layout>
    <x-slot name="headerTitle">
        Personal Information
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8 min-h-screen bg-gray-100">
        <div class="max-w-5xl mx-auto">

            <!-- Success Message -->
            @if (session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded-lg">
                    <div class="flex">
                        <svg class="h-6 w-6 text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <p class="text-sm text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <!-- Main Card -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">

                <!-- Header with Photo -->
                <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-8 py-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-6">
                            <!-- Passport Photo -->
                            @if ($personalInfo && $personalInfo->passport_photo)
                                <img src="{{ Storage::url($personalInfo->passport_photo) }}" alt="Passport Photo"
                                    class="w-24 h-24 rounded-full border-4 border-white shadow-lg object-cover">
                            @else
                                <div
                                    class="w-24 h-24 rounded-full border-4 border-white bg-gray-300 flex items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                            @endif

                            <!-- Name -->
                            <div class="text-white">
                                <h1 class="text-3xl font-bold">
                                    {{ $personalInfo->first_name }}
                                    {{ $personalInfo->middle_name }}
                                    {{ $personalInfo->last_name }}
                                </h1>
                                <p class="text-blue-200 text-sm mt-1">Application No:
                                    {{ $personalInfo->application_no }}</p>
                            </div>
                        </div>

                        <!-- Edit Button -->
                        <a href="{{ route('ched.personal-form') }}"
                            class="bg-white text-blue-600 px-6 py-2 rounded-lg font-semibold hover:bg-blue-50 transition shadow-md">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit Information
                        </a>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-8">

                    <!-- Academic Information -->
                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            Academic Information
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-gray-50 p-4 rounded-lg">
                            <div>
                                <p class="text-sm text-gray-600">Academic Year</p>
                                <p class="font-semibold text-gray-900">{{ $personalInfo->academic_year }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">School Term</p>
                                <p class="font-semibold text-gray-900">{{ $personalInfo->school_term }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">School/University</p>
                                <p class="font-semibold text-gray-900">{{ $personalInfo->school ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Level</p>
                                <p class="font-semibold text-gray-900">{{ $personalInfo->year_level ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Personal Details -->
                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Personal Details
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 bg-gray-50 p-4 rounded-lg">
                            <div>
                                <p class="text-sm text-gray-600">Date of Birth</p>
                                <p class="font-semibold text-gray-900">
                                    {{ $personalInfo->date_of_birth->format('F d, Y') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Age</p>
                                <p class="font-semibold text-gray-900">{{ $personalInfo->age }} years old</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Sex</p>
                                <p class="font-semibold text-gray-900">{{ $personalInfo->sex }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Civil Status</p>
                                <p class="font-semibold text-gray-900">{{ $personalInfo->civil_status }}</p>
                            </div>
                            @if ($personalInfo->passport_no)
                                <div>
                                    <p class="text-sm text-gray-600">Passport No.</p>
                                    <p class="font-semibold text-gray-900">{{ $personalInfo->passport_no }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            Contact Information
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-gray-50 p-4 rounded-lg">
                            <div>
                                <p class="text-sm text-gray-600">Email Address</p>
                                <p class="font-semibold text-gray-900">{{ $personalInfo->email }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Contact Number</p>
                                <p class="font-semibold text-gray-900">{{ $personalInfo->contact_no }}</p>
                            </div>
                            @if ($personalInfo->mailing_address)
                                <div class="md:col-span-2">
                                    <p class="text-sm text-gray-600">Mailing Address</p>
                                    <p class="font-semibold text-gray-900">{{ $personalInfo->mailing_address }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Permanent Address
                        </h2>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-gray-900 leading-relaxed">
                                @if ($personalInfo->house_no)
                                    {{ $personalInfo->house_no }},
                                @endif
                                {{ $personalInfo->street }},
                                {{ $barangayName }},
                                {{ $cityName }},
                                {{ $provinceName }},
                                {{ $personalInfo->region }}
                                @if ($personalInfo->district)
                                    ({{ $personalInfo->district }})
                                @endif
                                - {{ $personalInfo->zip_code }}
                            </p>
                        </div>
                    </div>

                    <!-- Parents Information -->
                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Parents Information
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-gray-50 p-4 rounded-lg">
                            @if ($personalInfo->father_name)
                                <div>
                                    <p class="text-sm text-gray-600">Father's Name</p>
                                    <p class="font-semibold text-gray-900">{{ $personalInfo->father_name }}</p>
                                </div>
                            @endif
                            @if ($personalInfo->mother_name)
                                <div>
                                    <p class="text-sm text-gray-600">Mother's Name</p>
                                    <p class="font-semibold text-gray-900">{{ $personalInfo->mother_name }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-ched-layout>
