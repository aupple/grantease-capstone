<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Admin Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h1 class="text-2xl font-bold mb-2">Welcome, {{ auth()->user()->name }}!</h1>
                <p class="mb-4">You are logged in as an <strong>Admin</strong>.</p>

                <!-- ✅ Application Summary Section -->
                <div class="mt-6">
                    <h3 class="text-lg font-semibold mb-2">📊 Application Summary</h3>
                    <ul class="list-disc list-inside mb-4">
                        <li><strong>Total Applications:</strong> {{ $total }}</li>
                        <li><strong>Approved:</strong> {{ $approved }}</li>
                        <li><strong>Rejected:</strong> {{ $rejected }}</li>
                        <li><strong>Pending:</strong> {{ $pending }}</li>
                    </ul>

                    <!-- ✅ Dashboard Navigation Links -->
                    <div class="space-y-2">
                        <div>
                            <a href="{{ route('admin.reports') }}" class="text-blue-500 hover:underline">
                                📄 View Full Report
                            </a>
                        </div>
                        <div>
                            <a href="{{ route('admin.scholars') }}" class="text-blue-500 hover:underline">
                                🎓 View Scholars
                            </a>
                        </div>
                        <div>
                            <a href="{{ route('admin.applications') }}" class="text-blue-500 hover:underline">
                                📑 View Applications
                            </a>
                        </div>
                    </div>
                </div>
                <!-- ✅ End Summary -->
            </div>
        </div>
    </div>
</x-app-layout>
