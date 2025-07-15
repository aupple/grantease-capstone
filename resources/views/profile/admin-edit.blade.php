@extends('layouts.admin-layout')

@section('content')
    <h2 class="text-2xl font-bold mb-4">Admin Profile</h2>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- ✅ Reuse applicant partials -->
    <div class="space-y-6">
        @include('profile.partials.update-profile-information-form', ['action' => route('admin.profile.update')])
        @include('profile.partials.update-password-form')
        @include('profile.partials.delete-user-form')
    </div>
@endsection
