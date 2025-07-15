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
                        Welcome, {{ auth()->user()->first_name }} {{ auth()->user()->middle_name }} {{ auth()->user()->last_name }}!
                    </h1>
                    <p class="text-sm text-gray-700">Manage your scholarship applications</p>
                </div>

                <!-- Grid: Application Status + Quick Actions -->
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

                    <!-- Quick Actions -->
                    <div class="bg-white border border-gray-200 shadow-md rounded-2xl p-6">
                        <h2 class="font-semibold text-gray-800 mb-4">Quick Actions</h2>
                        <div class="space-y-3">
                            <a href="{{ route('applicant.application.view') }}" class="block w-full text-center border border-gray-300 px-4 py-2 rounded-xl bg-gray-50 hover:bg-gray-100 text-gray-800 transition">
                                📄 View Status
                            </a>
                        </div>
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
            bg-gradient-to-r from-yellow-200/40 via-ywllow/20 to-white-100/40 
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
</x-app-layout>
