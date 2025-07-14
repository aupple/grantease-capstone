<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8 relative min-h-screen bg-cover bg-center bg-no-repeat" style="background-image: url('/images/rem.jpg')">
    <div class="backdrop-blur-xl bg-white/20 border border-white/20 shadow-xl → shadow-md or shadow-lg
 rounded-2xl p-10 max-w-7xl mx-auto">

        
        <!-- 💠 Glassmorphism Container Start -->
        <div class="backdrop-blur-xl bg-white/30 border border-white/20 shadow-[0_8px_32px_rgba(0,0,0,0.2)] rounded-2xl p-8">
            
            <!-- Welcome Header -->
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900">
                    Welcome, {{ auth()->user()->first_name }} {{ auth()->user()->middle_name }} {{ auth()->user()->last_name }}!
                </h1>
                <p class="text-sm text-gray-700">Manage your scholarship applications</p>
            </div>

            <!-- Grid: Application Status + Quick Actions -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                
                <!-- ✅ Application Status -->
                <div class="bg-white/10 backdrop-blur-lg border border-white/20 shadow-xl rounded-2xl p-6 col-span-2">
                    <h2 class="font-semibold text-gray-800 mb-4">Application Status</h2>
                    @php
                        $latestApplication = auth()->user()->applicationForms()->latest()->first();
                    @endphp
                    @if ($latestApplication)
                        <div class="border rounded-xl p-4 bg-white/20 backdrop-blur-md">
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

                <!-- ✅ Quick Actions -->
                <div class="bg-white/10 backdrop-blur-lg border border-white/20 shadow-xl rounded-2xl p-6">
                    <h2 class="font-semibold text-gray-800 mb-4">Quick Actions</h2>
                    <div class="space-y-3">
                        <a href="{{ route('applicant.application.view') }}" class="block w-full text-center border border-white/30 px-4 py-2 rounded-xl bg-white/20 hover:bg-white/30 backdrop-blur-sm text-gray-800 transition">
                            📄 View Status
                        </a>
                    </div>
                </div>
            </div>

            <!-- ✅ Available Scholarships -->
            <div class="bg-white/10 backdrop-blur-lg border border-white/20 shadow-xl rounded-2xl p-8">
                <h2 class="font-semibold text-gray-800 mb-4">Available Scholarships</h2>

                <!-- DOST -->
                <div class="border border-white/30 bg-white/20 backdrop-blur-md rounded-xl p-4 mb-4 flex justify-between items-center">
                    <div>
                        <p class="font-semibold text-gray-800">DOST Scholarship</p>
                        <p class="text-sm text-gray-600">For students pursuing Science, Tech, Engineering, Math</p>
                        <p class="text-xs text-gray-500 mt-1">Deadline: June 30, 2023</p>
                    </div>
                    <form method="GET" action="{{ route('applicant.application.create') }}">
                        <input type="hidden" name="program" value="DOST">
                        <button class="bg-white/10 backdrop-blur-md border border-white/30 text-blue-800 font-medium px-4 py-2 rounded-md hover:bg-white/20 transition">
    Apply Now
</button>

                    </form>
                </div>

                <!-- CHED -->
                <div class="border border-white/30 bg-white/20 backdrop-blur-md rounded-xl p-4 flex justify-between items-center">
                    <div>
                        <p class="font-semibold text-gray-800">CHED Scholarship</p>
                        <p class="text-sm text-gray-600">For academically qualified students with financial needs</p>
                        <p class="text-xs text-gray-500 mt-1">Deadline: July 15, 2023</p>
                    </div>
                    <form method="GET" action="{{ route('applicant.application.create') }}">
                        <input type="hidden" name="program" value="CHED">
                        <button class="bg-blue-500/30 text-blue-900 border border-blue-500/30 backdrop-blur-sm px-4 py-2 rounded-xl hover:bg-blue-500/40 transition text-sm">
                            Apply Now
                        </button>
                    </form>
                </div>
            </div>

        </div> <!-- 💠 End Glassmorphism Container -->
    </div>
</x-app-layout>
