<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <!-- ✅ Normal background only -->
    <div class="py-6 px-4 sm:px-6 lg:px-8 min-h-screen bg-gray-100">

        <!-- ✅ Success Message -->
        @if(session('success'))
    <div 
        x-data="{ show: true }" 
        x-show="show" 
        x-init="setTimeout(() => show = false, 4000)" 
        x-transition 
        class="fixed top-5 right-5 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg"
    >
        {{ session('success') }}
    </div>
@endif


        <!-- 💠 Keep Glassmorphism ONLY for the Welcome Box -->
        <div class="max-w-7xl mx-auto">
            <div class="backdrop-blur-xl bg-white/30 border border-white/20 shadow-lg rounded-2xl p-8">

                <!-- Welcome Header -->
                <div class="mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">
                        Welcome, {{ auth()->user()->first_name }} {{ auth()->user()->middle_name }} {{ auth()->user()->last_name }}!
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
                                        <p class="text-sm text-gray-500">Submitted on: {{ $latestApplication->created_at->format('F d, Y') }}</p>
                                    </div>
                                    <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded">
                                        {{ ucfirst($latestApplication->status) }}
                                    </span>
                                </div>
                                <a href="{{ route('applicant.application.view') }}" class="mt-2 inline-block text-sm text-blue-700 hover:underline">View Details</a>
                            </div>
                        @else
                            <p class="text-gray-500">Ready to start your scholarship journey?</p>
                        @endif
                    </div>

                    <!-- Required Forms Dropdown -->
                    <div class="bg-white border border-gray-200 shadow-md rounded-2xl p-6">
                        <h2 class="font-semibold text-gray-800 mb-4">Required Forms</h2>
                        <div class="relative">
                            <button id="dropdownButton" class="w-full text-left border border-gray-300 px-4 py-2 rounded-lg bg-gray-50 hover:bg-gray-100 transition flex items-center justify-between">
                                <span class="font-medium">Select Required Forms</span>
                                <svg id="dropdownIcon" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 15l6-6 6 6" />
                                </svg>
                            </button>
                            <div id="dropdownMenu" class="absolute left-0 w-full mt-1 hidden bg-white border border-gray-200 rounded-lg shadow-lg z-10">
                                <div class="py-2">
                                    <a href="{{ route('applicant.pdf.score_sheet') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                                        <span class="flex-1">Score Sheet</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('applicant.pdf.recommendation_form') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                                        <span class="flex-1">Recommendation Form</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('applicant.pdf.research_plans') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                                        <span class="flex-1">Form A: Research Plans</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('applicant.pdf.career_plans') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                                        <span class="flex-1">Form B: Career Plans</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('applicant.pdf.certification_employment') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                                        <span class="flex-1">Form 2A: Certification of Employment and Permit to Study</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('applicant.pdf.certification_deped') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                                        <span class="flex-1">Form 2B: Certificate of DepEd Employment and Permit to Study</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('applicant.pdf.certification_health_status') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                                        <span class="flex-1">Form C: Certification of Health Status</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Download the required forms, fill them out, and upload them during your application process.</p>
                    </div>
                </div>

                <!-- Available Scholarships -->
                <div class="bg-white border border-gray-200 shadow-md rounded-2xl p-8">
                    <h2 class="font-semibold text-gray-800 mb-4">Available Scholarships</h2>

                    <!-- ✅ DOST -->
                    <div class="rounded-xl p-4 mb-4 flex justify-between items-center bg-gradient-to-r from-blue-200 via-blue-100 to-white shadow-md">
                        <div>
                            <p class="font-semibold text-gray-800">DOST Scholarship</p>
                            <p class="text-sm text-gray-700">For students pursuing Science, Tech, Engineering, Math</p>
                            <p class="text-xs text-gray-600 mt-1">Deadline: June 30, 2023</p>
                        </div>
                        <form method="GET" action="{{ route('applicant.application.create') }}">
                            <input type="hidden" name="program" value="DOST">
                            <button class="bg-white text-blue-800 font-medium px-4 py-2 rounded-md hover:bg-gray-100 transition">
                                Apply Now
                            </button>
                        </form>
                    </div>

                    <!-- ✅ CHED -->
                    <div class="rounded-xl p-4 flex justify-between items-center 
                                bg-gradient-to-r from-yellow-200/40 via-yellow-100/20 to-white-100/40 
                                backdrop-blur-lg border border-white/20 shadow-xl">
                        <div>
                            <p class="font-semibold text-gray-900">CHED Scholarship</p>
                            <p class="text-sm text-gray-700">For academically qualified students with financial needs</p>
                            <p class="text-xs text-gray-600 mt-1">Deadline: July 15, 2023</p>
                        </div>
                        <form method="GET" action="{{ route('applicant.application.create') }}">
                            <input type="hidden" name="program" value="CHED">
                            <button class="bg-white text-blue-800 font-medium px-4 py-2 rounded-md hover:bg-gray-100 transition">
                                Apply Now
                            </button>
                        </form>
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
            dropdownIcon.classList.toggle('transform', 'rotate-180'); // Rotate the icon when dropdown is open
        });

        // Close the dropdown if clicked outside
        window.addEventListener('click', function(event) {
            const dropdownMenu = document.getElementById('dropdownMenu');
            const dropdownButton = document.getElementById('dropdownButton');
            if (!event.target.closest('#dropdownButton') && !dropdownMenu.contains(event.target)) {
                dropdownMenu.classList.add('hidden');
                dropdownIcon.classList.remove('transform', 'rotate-180'); // Reset icon rotation
            }
        });
    </script>
</x-app-layout>
