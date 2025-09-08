<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit My Application</h2>
    </x-slot>

    <div class="py-10 bg-gray-50 min-h-screen">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 rounded-xl shadow-lg">
                <form method="POST" action="{{ route('applicant.application.update', ['id' => $application->application_form_id]) }}" enctype="multipart/form-data">
    @csrf
    @method('PATCH')


                    <!-- Program (Read-only) -->
                    <div class="mb-5">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Program</label>
                        <input type="text" value="{{ $application->program }}" readonly class="w-full border border-gray-300 rounded-lg px-4 py-2 bg-gray-100 text-gray-700 cursor-not-allowed">
                    </div>

                    <!-- School -->
                    <div class="mb-5">
                        <label for="school" class="block text-sm font-semibold text-gray-700 mb-1">School</label>
                        <input type="text" name="school" id="school" value="{{ old('school', $application->school) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        @error('school')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Year Level -->
                    <div class="mb-5">
                        <label for="year_level" class="block text-sm font-semibold text-gray-700 mb-1">Year Level</label>
                        <input type="text" name="year_level" id="year_level" value="{{ old('year_level', $application->year_level) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        @error('year_level')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Reason -->
                    <div class="mb-5">
                        <label for="reason" class="block text-sm font-semibold text-gray-700 mb-1">Reason for Applying</label>
                        <textarea name="reason" id="reason" rows="4" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>{{ old('reason', $application->reason) }}</textarea>
                        @error('reason')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Actions -->
                    <div class="mt-6 flex justify-between items-center">
                        <a href="{{ route('applicant.application.view') }}" class="text-sm text-gray-500 hover:text-blue-600 transition">← Back</a>
                        <button type="submit" class="inline-flex items-center bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 transition">
                            💾 <span class="ml-2">Save Changes</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
