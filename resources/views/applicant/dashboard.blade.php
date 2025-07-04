<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Applicant Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <!-- ✅ Welcome message with full name -->
                <h1 class="text-2xl font-bold mb-4">
                    Welcome, {{ auth()->user()->first_name }} {{ auth()->user()->middle_name }} {{ auth()->user()->last_name }}!
                </h1>
                <p class="mb-6">You are logged in as an <strong>Applicant</strong>.</p>

                <!-- ✅ Success flash message -->
                @if (session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-800 border border-green-400 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <h2 class="text-lg font-semibold mb-4">Apply for a Scholarship Program:</h2>

                <div class="space-y-4 mt-4">
                    <!-- DOST Button -->
                    <form action="{{ route('applicant.application.create') }}" method="GET">
                        <input type="hidden" name="program" value="DOST">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 w-full">
                            Apply for DOST Scholarship
                        </button>
                    </form>

                    <!-- CHED Button -->
                    <form action="{{ route('applicant.application.create') }}" method="GET">
                        <input type="hidden" name="program" value="CHED">
                        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 w-full">
                            Apply for CHED Scholarship
                        </button>
                    </form>
                    <div class="mt-6">
    <a href="{{ route('applicant.application.view') }}" class="text-blue-600 hover:underline">
        📄 View My Application
    </a>
</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
