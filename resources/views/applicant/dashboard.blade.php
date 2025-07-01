@extends('layouts.applicant')

@section('title', 'Dashboard')

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

    .quick-actions {
        display: flex;
        gap: 1rem;
        margin: 2rem;
    }

    .quick-actions a {
        display: inline-block;
        padding: 0.75rem 1.5rem;
        background-color: #2563eb;
        color: white;
        text-decoration: none;
        border-radius: 8px;
        font-weight: bold;
        transition: background-color 0.3s;
    }

    .quick-actions a:hover {
        background-color: #1e40af;
    }

    .train-card {
        margin: 2rem;
        padding: 1rem 1.5rem;
        border-radius: 10px;
        background: white;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .train-status {
        display: flex;
        justify-content: space-around;
        align-items: center;
    }

    .train-step {
        text-align: center;
    }

    .train-step span {
        display: block;
        width: 40px;
        height: 40px;
        margin: 0 auto 0.5rem;
        border-radius: 50%;
        background-color: #2563eb;
        color: white;
        line-height: 40px;
        font-weight: bold;
    }

    .scholarship-section {
        margin: 2rem;
    }

    .scholarship-card {
        display: flex;
        gap: 1rem;
        background: white;
        padding: 1.5rem;
        border-radius: 10px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        margin-bottom: 1.5rem;
        align-items: center;
    }

    .scholarship-card img {
        width: 60px;
        height: 60px;
        object-fit: contain;
    }

    .scholarship-info {
        flex: 1;
    }

    .scholarship-info h4 {
        margin: 0 0 0.5rem;
        font-size: 1.2rem;
    }

    .scholarship-info p {
        margin: 0 0 0.5rem;
    }

    .scholarship-card a {
        padding: 0.5rem 1rem;
        background-color: #2563eb;
        color: white;
        text-decoration: none;
        border-radius: 6px;
        font-weight: bold;
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

<div class="card">
    <div class="card-body">
        <div class="quick-actions">
            <a href="{{ route('applicant.apply') }}">Apply Now</a>
            <a href="{{ route('applicant.status') }}">View Status</a>
        </div>
    </div>
</div>

<div class="card train-card">
    <div class="card-body">
        <div class="train-status">
            <div class="train-step">
                <span>1</span>
                Submitted
            </div>
            <div class="train-step">
                <span>2</span>
                Under Review
            </div>
            <div class="train-step">
                <span>3</span>
                Approved
            </div>
            <div class="train-step">
                <span>4</span>
                Awarded
            </div>
        </div>
    </div>
</div>

<div class="card scholarship-section">
    <div class="scholarship-card">
        <img src="{{ asset('images/ched.png') }}" alt="CHED Logo">
        <div class="scholarship-info">
            <h4>CHED Scholarship</h4>
            <p>The Commission on Higher Education (CHED) offers financial assistance to deserving students in higher education.</p>
        </div>
        <a href="{{ route('applicant.apply') }}">Apply</a>
    </div>

    <div class="scholarship-card">
        <img src="{{ asset('images/dost.png') }}" alt="DOST Logo">
        <div class="scholarship-info">
            <h4>DOST-SEI Scholarship</h4>
            <p>The Department of Science and Technology - Science Education Institute provides scholarships to students in science and technology fields.</p>
        </div>
        <a href="{{ route('applicant.apply') }}">Apply</a>
    </div>
</div>
@endsection
