<x-app-layout>
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

                <!-- Grid: Application Status -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

                    <!-- Application Status -->
                    <div class="bg-white border border-gray-200 shadow-md rounded-2xl p-6 col-span-2">
                        <h2 class="font-semibold text-gray-800 mb-4">Application Status</h2>

                        @php
                            $latestApplication = auth()->user()->applicationForms()->latest()->first();
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
                                    <a href="{{ route('applicant.application.view') }}"
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

                <!-- Available Scholarships -->
                <div class="bg-white border border-gray-200 shadow-md rounded-2xl p-8">
                    <h2 class="font-semibold text-gray-800 mb-4">Available Scholarships</h2>

                    <!-- ✅ DOST -->
                    <div
                        class="rounded-xl p-4 mb-4 flex justify-between items-center bg-gradient-to-r from-blue-200 via-blue-100 to-white shadow-md">
                        <div>
                            <p class="font-semibold text-gray-800">DOST Scholarship</p>
                            <p class="text-sm text-gray-700">For students pursuing Science, Tech, Engineering, Math</p>
                            <p class="text-xs text-gray-600 mt-1">Deadline: June 30, 2023</p>
                        </div>
                        <a href="{{ route('applicant.application.create', ['program' => 'DOST']) }}"
                            class="bg-white text-blue-800 font-medium px-4 py-2 rounded-md hover:bg-gray-100 transition">
                            Apply Now
                        </a>
                    </div>
                </div> <!-- End Glassmorphism Box -->
            </div>
        </div>

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
</x-app-layout>
