<x-ched-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <!-- ✅ Normal background only -->
    <div class="py-6 px-4 sm:px-6 lg:px-8 min-h-screen bg-gray-100">

        <!-- 💠 Keep Glassmorphism ONLY for the Welcome Box -->
        <div class="max-w-7xl mx-auto">
            <div class="backdrop-blur-xl bg-white/30 border border-white/20 shadow-lg rounded-2xl p-8">

                <!-- Welcome Header -->
                <div class="mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">
                        Welcome, {{ auth()->user()->first_name }} {{ auth()->user()->middle_name }}
                        {{ auth()->user()->last_name }}!
                    </h1>
                    <p class="text-sm text-gray-700">Manage your scholarship applications</p>
                </div>
                <!-- ADD THIS: Profile Completion Alert -->
                @if (!auth()->user()->personal_info_completed ?? true)
                    <div class="mb-6 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <svg class="h-6 w-6 text-yellow-400 mr-3 flex-shrink-0" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <div>
                                    <h3 class="text-sm font-semibold text-yellow-800">Action Required: Complete Your
                                        Personal Information</h3>
                                    <p class="text-xs text-yellow-700 mt-1">Your profile is incomplete. Please fill out
                                        your personal information for CHED monitoring.</p>
                                </div>
                            </div>
                            <a href="{{ route('ched.personal-form') }}"
                                class="ml-4 bg-yellow-400 hover:bg-yellow-500 text-yellow-900 font-semibold px-6 py-2 rounded-lg transition shadow-sm whitespace-nowrap">
                                Complete Now →
                            </a>
                        </div>
                    </div>
                @endif

                <!-- Grid: Application Status -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

                    <!-- Application Status -->
                    <div class="bg-white border border-gray-200 shadow-md rounded-2xl p-6 col-span-2">
                        <h2 class="font-semibold text-gray-800 mb-4">Application Status</h2>

                        @php
                            $latestApplication = auth()
                                ->user()
                                ->applicationForms()
                                ->where('program', 'CHED')
                                ->latest()
                                ->first();
                        @endphp

                        @if ($latestApplication)
                            <div class="border rounded-xl p-4 bg-gray-100">
                                <div class="flex justify-between items-center mb-2">
                                    <div>
                                        <p class="font-semibold">{{ $latestApplication->program }} Scholarship</p>
                                        <p class="text-sm text-gray-500">
                                            Submitted on: {{ $latestApplication->created_at->format('F d, Y') }}
                                        </p>
                                    </div>
                                </div>

                                <!-- ✅ Application Status Train -->
                                @php
                                    // Map your ENUM values to the simplified train steps
                                    $statusMap = [
                                        'submitted' => 'pending',
                                        'pending' => 'pending',
                                        'document_verification' => 'verified',
                                        'approved' => 'verdict',
                                        'rejected' => 'verdict',
                                    ];

                                    $currentStatus = strtolower($latestApplication->status);
                                    $trainStatus = $statusMap[$currentStatus] ?? 'pending';

                                    $statuses = ['pending', 'verified', 'verdict'];

                                    $currentIndex = match ($trainStatus) {
                                        'pending' => 0,
                                        'verified' => 1,
                                        'verdict' => 2,
                                        default => 0,
                                    };
                                @endphp

                                <div class="relative mt-6">
                                    <div class="flex justify-between items-center">
                                        @foreach ($statuses as $index => $status)
                                            @php
                                                $label =
                                                    $status === 'verdict' &&
                                                    in_array($currentStatus, ['approved', 'rejected'])
                                                        ? $currentStatus
                                                        : $status;
                                            @endphp

                                            <div class="flex flex-col items-center relative w-full">
                                                <!-- Connector Line -->
                                                @if ($index < count($statuses) - 1)
                                                    <div
                                                        class="absolute top-4 left-1/2 w-full h-[2px] z-0
                                    {{ $index < $currentIndex ? 'bg-blue-600' : 'bg-gray-300' }}">
                                                    </div>
                                                @endif

                                                <!-- Step Circle -->
                                                <div
                                                    class="relative z-10 w-8 h-8 flex items-center justify-center rounded-full text-white
                                @if ($label === 'rejected') bg-red-600
                                @elseif($label === 'approved')
                                    bg-green-600
                                @elseif($index <= $currentIndex)
                                    bg-blue-600
                                @else
                                    bg-gray-300 text-gray-700 @endif">
                                                    @if ($label === 'rejected')
                                                        3
                                                    @elseif($label === 'approved')
                                                        3
                                                    @else
                                                        {{ $index + 1 }}
                                                    @endif
                                                </div>

                                                <!-- Step Label -->
                                                <p
                                                    class="text-xs mt-2 capitalize
                                @if ($label === 'rejected') text-red-600 font-semibold
                                @elseif($label === 'approved')
                                    text-green-700 font-semibold
                                @elseif($index <= $currentIndex)
                                    text-blue-700 font-medium
                                @else
                                    text-gray-500 @endif">
                                                    {{ $label }}
                                                </p>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="mt-5 text-right">
                                    <a href="{{ route('ched.application.view') }}"
                                        class="inline-block text-sm text-blue-700 hover:text-blue-900 font-medium transition">
                                        View Details →
                                    </a>
                                </div>
                            </div>
                        @else
                            <p class="text-gray-500">Ready to start your scholarship journey?</p>
                        @endif
                    </div>

                    <!-- ✅ Required Forms Dropdown (UNCHANGED) -->
                    <div class="bg-white border border-gray-200 shadow-md rounded-2xl p-6">
                        <h2 class="font-semibold text-gray-800 mb-4">Required Forms</h2>
                        <p class="text-sm text-gray-600 mb-4 font-medium">The forms below are applicable only for DOST
                            applicants.</p>
                        <div class="relative">
                            <button id="dropdownButton"
                                class="w-full text-left border border-gray-300 px-4 py-2 rounded-lg bg-gray-50 hover:bg-gray-100 transition flex items-center justify-between">
                                <span class="font-medium">Select Required Forms</span>
                                <svg id="dropdownIcon" xmlns="http://www.w3.org/2000/svg"
                                    class="h-4 w-4 ml-2 transition-transform" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 15l6-6 6 6" />
                                </svg>
                            </button>
                            <div id="dropdownMenu"
                                class="absolute left-0 w-full mt-1 hidden bg-white border border-gray-200 rounded-lg shadow-lg z-10">
                                <div class="py-2">
                                    <a href="{{ route('applicant.pdf.score_sheet') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                                        <span class="flex-1">Score Sheet</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('applicant.pdf.recommendation_form') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                                        <span class="flex-1">Recommendation Form</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('applicant.pdf.research_plans') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                                        <span class="flex-1">Form A: Research Plans</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('applicant.pdf.career_plans') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                                        <span class="flex-1">Form B: Career Plans</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('applicant.pdf.certification_employment') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                                        <span class="flex-1">Form 2A: Certification of Employment and Permit to
                                            Study</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('applicant.pdf.certification_deped') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                                        <span class="flex-1">Form 2B: Certificate of DepEd Employment and Permit to
                                            Study</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('applicant.pdf.certification_health_status') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                                        <span class="flex-1">Form C: Certification of Health Status</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Download the required forms, fill them out, and upload
                            them during your application process.</p>
                    </div>
                </div>

                <!-- Personal Information & Quick Actions -->
                <div class="bg-white border border-gray-200 shadow-md rounded-2xl p-8">
                    <h2 class="font-semibold text-gray-800 mb-4">Quick Actions</h2>

                    <!-- Personal Information Card -->
                    <div
                        class="rounded-xl p-4 flex justify-between items-center 
                bg-gradient-to-r from-blue-200/40 via-blue-100/20 to-white-100/40 
                backdrop-blur-lg border border-white/20 shadow-xl mb-4">
                        <div>
                            <p class="font-semibold text-gray-900">Personal Information Form</p>
                            <p class="text-sm text-gray-700">Complete your profile for CHED monitoring and reports</p>
                            <p class="text-xs text-gray-600 mt-1">
                                Status:
                                @if (auth()->user()->personal_info_completed ?? false)
                                    <span class="text-green-600 font-semibold">✓ Completed</span>
                                @else
                                    <span class="text-red-600 font-semibold">● Incomplete - Action Required</span>
                                @endif
                            </p>
                        </div>
                        <a href="{{ route('ched.personal-form') }}"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2.5 rounded-lg transition shadow-sm">
                            @if (auth()->user()->personal_info_completed ?? false)
                                Update Info
                            @else
                                Complete Now
                            @endif
                        </a>
                    </div>
                </div>

                <!-- Success Message -->
                @if (session('success'))
                    <div class="mt-6 bg-green-50 border-l-4 border-green-400 p-4 rounded-lg">
                        <div class="flex">
                            <svg class="h-6 w-6 text-green-400 mr-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            <p class="text-sm text-green-700">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif
            </div>

            <script>
                document.getElementById('dropdownButton').addEventListener('click', function() {
                    const dropdownMenu = document.getElementById('dropdownMenu');
                    const dropdownIcon = document.getElementById('dropdownIcon');
                    dropdownMenu.classList.toggle('hidden');
                    dropdownIcon.classList.toggle('transform', 'rotate-180');
                });

                window.addEventListener('click', function(event) {
                    const dropdownMenu = document.getElementById('dropdownMenu');
                    const dropdownButton = document.getElementById('dropdownButton');
                    if (!event.target.closest('#dropdownButton') && !dropdownMenu.contains(event.target)) {
                        dropdownMenu.classList.add('hidden');
                        dropdownIcon.classList.remove('transform', 'rotate-180');
                    }
                });
            </script>
</x-ched-layout>
