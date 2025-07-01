@extends('layouts.admin')

@section('title', 'Dashboard')
@section('header-title', 'Admin Dashboard')

@push('styles')
    <style>
       .welcome-card,
    .card {
        margin: 1rem 2rem 2rem;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        background-color: white;
        position: relative;
    }

    .welcome-card-header {
        background: linear-gradient(to right, #0f2c7d, #2563eb);
        color: white;
        padding: 1rem 1.5rem;
        font-size: 1.2rem;
        font-weight: 600;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .welcome-card-body,
    .card-body {
        padding: 1rem 1.5rem;
        color: #111827;
        font-size: 1rem;
    }

    .welcome-card .close-btn {
        background: none;
        border: none;
        color: #ddd;
        font-size: 1rem;
        cursor: pointer;
    }

    .welcome-card .close-btn:hover {
        color: white;
    }
    </style>
@endpush

@section('content')
    <div class="card welcome-card" id="welcomeCard">
    <div class="welcome-card-header">
        <span>Welcome, {{ ucwords(auth()->user()->name) }}!</span>
        <button class="close-btn" onclick="document.getElementById('welcomeCard').style.display='none';">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <div class="welcome-card-body">
        You are logged in as an <strong>Applicant</strong>.
    </div>
</div>
@endsection
