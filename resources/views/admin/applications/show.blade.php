@extends('layouts.admin-layout')

@section('content')
    <div class="mb-6 relative">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-semibold text-gray-800 leading-tight">
                    Application #{{ $application->application_form_id }}
                </h2>
                <p class="text-sm text-gray-500">Submitted on {{ $application->created_at->format('F d, Y') }}</p>
            </div>

            <!-- ✅ Approve/Reject buttons (initially hidden) -->
            <div id="actionButtons" class="hidden flex gap-2">
                <form action="{{ route('admin.applications.update-status', $application->application_form_id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="approved">
                    <button type="submit" class="bg-green-600 text-white text-sm px-4 py-1.5 rounded-md hover:bg-green-700 transition font-semibold">Approve</button>
                </form>
                <form action="{{ route('admin.applications.update-status', $application->application_form_id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="rejected">
                    <button type="submit" class="bg-red-600 text-white text-sm px-4 py-1.5 rounded-md hover:bg-red-700 transition font-semibold">Reject</button>
                </form>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- LEFT SIDE: Personal, Academic, Financial Info -->
        <div class="col-span-2 space-y-6">
            <!-- Personal Info -->
            <div class="bg-white p-6 rounded shadow">
                <h3 class="text-lg font-bold mb-4">Personal Information</h3>
                <p><strong>Name:</strong> {{ $application->user->full_name ?? $application->user->first_name . ' ' . $application->user->last_name }}</p>
                <p><strong>Email:</strong> {{ $application->user->email }}</p>
                <p><strong>Phone:</strong> {{ $application->user->phone ?? 'N/A' }}</p>
                <p><strong>Address:</strong> {{ $application->user->address ?? 'N/A' }}</p>
            </div>

            <!-- Academic Info -->
            <div class="bg-white p-6 rounded shadow">
                <h3 class="text-lg font-bold mb-4">🎓 Academic Background</h3>
                <p><strong>Program:</strong> {{ $application->program }}</p>
                <p><strong>School:</strong> {{ $application->school }}</p>
                <p><strong>Year Level:</strong> {{ $application->year_level }}</p>
                <p><strong>Reason:</strong> {{ $application->reason ?? 'N/A' }}</p>
            </div>

            <!-- Financial Info -->
            <div class="bg-white p-6 rounded shadow">
                <h3 class="text-lg font-bold mb-4">💸 Financial Status</h3>
                <p><strong>Monthly Income:</strong> {{ $application->monthly_income ?? 'N/A' }}</p>
                <p><strong>Family Members:</strong> {{ $application->family_size ?? 'N/A' }}</p>
                <p><strong>Other Scholarships:</strong> {{ $application->other_scholarships ?? 'N/A' }}</p>
            </div>
        </div>

        <!-- RIGHT SIDE -->
        <div class="space-y-6">
            <!-- 📎 Documents -->
            <div class="bg-white p-6 rounded shadow">
                <h3 class="text-lg font-bold mb-4">📎 Documents</h3>
                @php
                    $documents = [
                        'Application Form' => $application->application_form_path ?? '#',
                        'Recommendation Form' => $application->recommendation_form_path ?? '#',
                    ];
                @endphp
                @foreach ($documents as $label => $link)
                    <div class="mb-4">
                        <div class="flex justify-between items-center">
                            <p class="text-sm font-medium">{{ $label }}</p>
                            <div class="flex items-center gap-3">
                                @if ($link && $link !== '#')
                                    <a href="{{ $link }}" target="_blank" class="text-blue-600 hover:underline text-sm">View</a>
                                @else
                                    <span class="text-sm text-gray-400">No file</span>
                                @endif
                                <label class="inline-flex items-center text-sm cursor-pointer">
                                    <input type="checkbox" id="checkbox-{{ Str::slug($label, '-') }}" class="peer hidden checkbox-tracker">
                                    <div class="w-2.5 h-2.5 rounded-full border border-gray-400 peer-checked:bg-green-500 peer-checked:border-green-500 transition duration-200"></div>
                                    <span class="ml-2 text-xs text-gray-600 peer-checked:text-green-600 font-semibold">Verified</span>
                                </label>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Application Info -->
            <div class="bg-white p-3 rounded shadow">
                <h3 class="text-lg font-bold mb-3">Application Info</h3>

                <!-- Status Display -->
                <div class="mb-3 flex items-center gap-3">
                    <strong class="text-sm">Status:</strong>
                    <span class="px-3 py-1 rounded-full text-sm font-bold
                        @if ($application->status === 'approved') bg-green-100 text-green-800
                        @elseif ($application->status === 'rejected') bg-red-100 text-red-800
                        @elseif ($application->status === 'pending') bg-yellow-100 text-yellow-800
                        @elseif ($application->status === 'document_verification') bg-purple-100 text-purple-800
                        @elseif ($application->status === 'for_interview') bg-blue-100 text-blue-800
                        @else bg-gray-100 text-gray-800
                        @endif">
                        {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                    </span>
                </div>

                <!-- Message Form -->
                <form action="{{ route('admin.applications.update-status', $application->application_form_id) }}" method="POST" class="flex items-center gap-2">
                    @csrf
                    <strong class="text-sm">Remarks:</strong>
                    <input type="text" name="remarks" class="text-xs border px-3 py-1 rounded w-64" placeholder="Type your message here..." value="{{ $application->remarks }}">
                    <button type="submit" class="text-xs text-white bg-blue-500 hover:bg-blue-600 px-3 py-1 rounded transition">Send</button>
                </form>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white p-6 rounded shadow">
                <h3 class="text-lg font-bold mb-4">Quick Actions</h3>
                <div class="space-y-2">
                    <a href="#" class="block w-full text-center bg-gray-50 border border-gray-200 text-sm text-gray-800 rounded-md px-4 py-2 hover:bg-gray-100 transition">📄 Print Application</a>
                    <a href="#" class="block w-full text-center bg-gray-50 border border-gray-200 text-sm text-gray-800 rounded-md px-4 py-2 hover:bg-gray-100 transition">📥 Download Documents</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkboxes = document.querySelectorAll('.checkbox-tracker');
        const actionButtons = document.getElementById('actionButtons');

        function toggleActionButtons() {
            let allChecked = true;
            checkboxes.forEach(cb => {
                if (!cb.checked) {
                    allChecked = false;
                }
            });
            if (allChecked) {
                actionButtons.classList.remove('hidden');
            } else {
                actionButtons.classList.add('hidden');
            }
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', toggleActionButtons);
        });
    });
</script>
@endpush
