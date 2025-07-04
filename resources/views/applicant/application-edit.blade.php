<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit My Application</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('applicant.application.update') }}" class="bg-white p-6 rounded shadow">
                @csrf
                @method('PATCH') <!-- ✅ Required for PATCH route -->

                <div class="mb-4">
                    <label class="block font-bold">Program</label>
                    <input type="text" value="{{ $application->program }}" readonly class="w-full border rounded px-3 py-2 bg-gray-100">
                </div>

                <div class="mb-4">
                    <label class="block font-bold">School</label>
                    <input type="text" name="school" value="{{ $application->school }}" class="w-full border rounded px-3 py-2" required>
                </div>

                <div class="mb-4">
                    <label class="block font-bold">Year Level</label>
                    <input type="text" name="year_level" value="{{ $application->year_level }}" class="w-full border rounded px-3 py-2" required>
                </div>

                <div class="mb-4">
                    <label class="block font-bold">Reason for Applying</label>
                    <textarea name="reason" class="w-full border rounded px-3 py-2" required>{{ $application->reason }}</textarea>
                </div>

                <div class="mt-6">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
