@extends('layouts.admin-layout')

@section('content')
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">CHED Scholar Info</h1>
                <p class="text-sm text-gray-600 mt-1">Application No: {{ $chedInfo->application_no ?? 'N/A' }}</p>
            </div>
        </div>

        <!-- Two Column Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Left Column: Personal Information (2/3 width) -->
            <div class="lg:col-span-2">
                <div class="bg-white/60 backdrop-blur-md border border-white/30 shadow-md rounded-xl p-6">
                    <h3 class="text-xl font-semibold text-purple-700 mb-6 flex items-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Personal Information
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-5">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Full Name</p>
                            <p class="text-base text-gray-900 font-semibold">
                                {{ $chedInfo->first_name }} {{ $chedInfo->middle_name }} {{ $chedInfo->last_name }}
                                {{ $chedInfo->suffix }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Email</p>
                            <p class="text-base text-gray-900 font-semibold">{{ $chedInfo->email ?? 'N/A' }}</p>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Telephone Nos.</p>
                            <p class="text-base text-gray-900 font-semibold">{{ $chedInfo->contact_no ?? 'N/A' }}</p>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Sex</p>
                            <p class="text-base text-gray-900 font-semibold">{{ ucfirst($chedInfo->sex ?? 'N/A') }}</p>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Birthdate</p>
                            <p class="text-base text-gray-900 font-semibold">
                                {{ $chedInfo->date_of_birth ? $chedInfo->date_of_birth->format('Y-m-d') : 'N/A' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Age</p>
                            <p class="text-base text-gray-900 font-semibold">{{ $chedInfo->age ?? 'N/A' }}</p>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Civil Status</p>
                            <p class="text-base text-gray-900 font-semibold">
                                {{ ucfirst($chedInfo->civil_status ?? 'N/A') }}</p>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Province</p>
                            <p class="text-base text-gray-900 font-semibold">{{ $provinceName }}</p>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">City / Municipality</p>
                            <p class="text-base text-gray-900 font-semibold">{{ $cityName }}</p>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Barangay</p>
                            <p class="text-base text-gray-900 font-semibold">{{ $barangayName }}</p>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Street</p>
                            <p class="text-base text-gray-900 font-semibold">{{ $chedInfo->street ?? 'N/A' }}</p>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">House No.</p>
                            <p class="text-base text-gray-900 font-semibold">{{ $chedInfo->house_no ?? 'N/A' }}</p>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Region</p>
                            <p class="text-base text-gray-900 font-semibold">{{ $chedInfo->region ?? 'N/A' }}</p>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">District</p>
                            <p class="text-base text-gray-900 font-semibold">{{ $chedInfo->district ?? 'N/A' }}</p>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Zip Code</p>
                            <p class="text-base text-gray-900 font-semibold">{{ $chedInfo->zip_code ?? 'N/A' }}</p>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Passport No.</p>
                            <p class="text-base text-gray-900 font-semibold">{{ $chedInfo->passport_no ?? 'N/A' }}</p>
                        </div>

                        <div class="md:col-span-2">
                            <p class="text-sm font-medium text-gray-600 mb-1">Current Mailing Address</p>
                            <p class="text-base text-gray-900 font-semibold">{{ $chedInfo->mailing_address ?? 'N/A' }}</p>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Father's Name</p>
                            <p class="text-base text-gray-900 font-semibold">{{ $chedInfo->father_name ?? 'N/A' }}</p>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Mother's Name</p>
                            <p class="text-base text-gray-900 font-semibold">{{ $chedInfo->mother_name ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Documents & Application Info (1/3 width) -->
            <div class="lg:col-span-1 space-y-6">

                <!-- Documents Section -->
                <div class="bg-white/60 backdrop-blur-md border border-white/30 shadow-md rounded-xl p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Documents</h3>

                    <div class="space-y-3">
                        @if ($chedInfo->passport_photo)
                            <div class="flex items-center justify-between py-2 border-b border-gray-200">
                                <span class="text-sm font-medium text-gray-700">Scholar Profile</span>
                                <div class="flex items-center gap-3">
                                    <a href="{{ asset('storage/' . $chedInfo->passport_photo) }}" target="_blank"
                                        class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        View File
                                    </a>
                                </div>
                            </div>
                        @endif

                        <!-- Add more document checks as needed based on your CHED model fields -->
                        {{-- Example structure for other documents:
                        @if ($chedInfo->birth_certificate)
                            <div class="flex items-center justify-between py-2 border-b border-gray-200">
                                <span class="text-sm font-medium text-gray-700">Birth Certificate</span>
                                <div class="flex items-center gap-3">
                                    <a href="{{ asset('storage/' . $chedInfo->birth_certificate) }}" target="_blank"
                                        class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        View File
                                    </a>
                                    <input type="checkbox" class="w-4 h-4 text-purple-600 rounded">
                                    <span class="text-xs text-gray-500">Verified</span>
                                </div>
                            </div>
                        @endif
                        --}}

                        @if (!$chedInfo->passport_photo)
                            <p class="text-sm text-gray-500 italic">No documents uploaded yet.</p>
                        @endif
                    </div>
                </div>

                <!-- Application Info Section -->
                <div class="bg-white/60 backdrop-blur-md border border-white/30 shadow-md rounded-xl p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Status Info</h3>

                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-600 mb-2">Status:</p>
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold
                            @if ($chedInfo->status === 'approved') bg-green-100 text-green-800
                            @elseif ($chedInfo->status === 'rejected') bg-red-100 text-red-800
                            @else bg-yellow-100 text-yellow-800 @endif">
                            {{ ucfirst($chedInfo->status) }}
                        </span>
                    </div>

                    @if ($chedInfo->status === 'pending')
                        <form method="POST" action="{{ route('admin.ched.update-status', $chedInfo->id) }}">
                            @csrf

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Update Status</label>
                                <select name="status" required
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                                    <option value="pending" selected>Pending</option>
                                    <option value="approved">Approve</option>
                                    <option value="rejected">Reject</option>
                                </select>
                            </div>
                            <button type="submit"
                                class="w-full px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                Enter
                            </button>
                        </form>
                    @else
                        @if ($chedInfo->remarks)
                            <div class="mt-4">
                                <p class="text-sm font-medium text-gray-700 mb-2">Remarks:</p>
                                <p class="text-sm text-gray-600 bg-gray-50 p-3 rounded-lg">{{ $chedInfo->remarks }}</p>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
