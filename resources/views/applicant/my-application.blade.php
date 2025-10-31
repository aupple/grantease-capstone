<x-app-layout :headerTitle="'Application Status'">


    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-2xl font-bold mb-6 text-gray-800">📄 My Scholarship Applications</h1>

            @if ($applications->isEmpty())
                <p class="text-gray-600">You haven't submitted any applications yet.</p>
            @else
                <div class="space-y-6">
                    @foreach ($applications as $application)
                        <div class="bg-white rounded-lg shadow p-6 border border-gray-200">
                            <h2 class="text-xl font-semibold text-gray-800 mb-2">
                                {{ $application->program }} Scholarship
                            </h2>
                            <div class="text-sm text-gray-700 space-y-1">
                                <p><strong>School:</strong> {{ $application->school }}</p>
                                <p><strong>Year Level:</strong> {{ $application->year_level }}</p>
                                <p><strong>Reason:</strong> {{ $application->reason }}</p>
                                <p><strong>Status:</strong>
                                    <span class="inline-block bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs">
                                        {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                                    </span>
                                </p>
                                <p><strong>Remarks:</strong> {{ $application->remarks ?? 'None' }}</p>
                                <p><strong>Submitted At:</strong>
                                    {{ $application->submitted_at ?? $application->created_at }}</p>
                            </div>

                            @if ($application->status === 'pending')
                                <div class="mt-4">
                                    <a href="{{ route('applicant.application.edit', ['id' => $application->application_form_id]) }}"
                                        class="inline-block bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded text-sm">
                                        ✏️ Edit Application
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="mt-6">
                <a href="{{ route('applicant.dashboard') }}" class="text-blue-600 hover:underline">
                    ← Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
