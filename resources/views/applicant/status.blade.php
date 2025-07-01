@extends('layouts.applicant')

@section('title', 'Application Status')

@section('content')
    <div class="p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Application Status</h2>
        <p>Your current status: 
            <strong class="text-blue-700">
                {{ $status ?? 'Not yet submitted' }}
            </strong>
        </p>
    </div>
@endsection
