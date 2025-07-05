<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit My Application</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('applicant.application.update', ['id' => $application->id]) }}" class="bg-white p-6 rounded shadow">
                @csrf
                @method('PATCH') <!-- ✅ Required for PATCH -->

                <!-- Program (Read-only) -->
                <div class="mb-4">
                    <label class="block font-bold text-gray-700">Program</label>
                    <input type="text" value="{{ $application->program }}" readonly class="w-full border rounded px-3 py-2 bg-gray-100 text-gray-800">
                </div>

                <!-- School -->
                <div class="mb-4">
                    <label for="school" class="block font-bold text-gray-700">School</label>
                    <input type="text" name="school" id="school" value="{{ old('school', $application->school) }}" class="w-full border rounded px-3 py-2" required>
                    @error('school')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Year Level -->
                <div class="mb-4">
                    <label for="year_level" class="block font-bold text-gray-700">Year Level</label>
                    <input type="text" name="year_level" id="year_level" value="{{ old('year_level', $application->year_level) }}" class="w-full border rounded px-3 py-2" required>
                    @error('year_level')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Reason -->
                <div class="mb-4">
                    <label for="reason" class="block font-bold text-gray-700">Reason for Applying</label>
                    <textarea name="reason" id="reason" class="w-full border rounded px-3 py-2" rows="4" required>{{ old('reason', $application->reason) }}</textarea>
                    @error('reason')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit -->
                <div class="mt-6 flex justify-between items-center">
                    <a href="{{ route('applicant.application.view') }}" class="text-gray-600 hover:underline">← Back</a>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        💾 Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
