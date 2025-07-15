<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800 leading-tight">
            👤 My Profile
        </h2>
    </x-slot>

    <div class="py-10 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto space-y-10">

        <!-- ✏️ Update Profile Information -->
        <div class="bg-white shadow rounded-lg border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">✏️ Update Profile Information</h3>
            @include('profile.partials.update-profile-information-form')
        </div>

        <!-- 🔐 Update Password -->
        <div class="bg-white shadow rounded-lg border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">🔐 Change Password</h3>
            @include('profile.partials.update-password-form')
        </div>

        <!-- 🗑️ Delete Account -->
        <div class="bg-white shadow rounded-lg border border-red-300 p-6">
            <h3 class="text-lg font-semibold text-red-600 mb-4">🗑️ Delete Account</h3>
            @include('profile.partials.delete-user-form')
        </div>

    </div>
</x-app-layout>
