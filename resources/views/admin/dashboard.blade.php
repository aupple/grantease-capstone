@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('header-title', 'Admin Dashboard')

@push('styles')
<style>
    .card {
        margin-bottom: 2rem;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        background-color: white;
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

    .close-btn {
        background: none;
        border: none;
        color: #ddd;
        font-size: 1rem;
        cursor: pointer;
    }

    .close-btn:hover {
        color: white;
    }

    .summary-bar {
        display: flex;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .summary-box {
        flex: 1;
        background-color: #f9fafb;
        padding: 1.5rem;
        border-radius: 8px;
        text-align: center;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        min-width: 180px;
    }

    .summary-box.bg-gray {
        background-color: #f3f4f6;
    }

    .summary-box.bg-green {
        background-color: #d1fae5;
    }

    .summary-box.bg-red {
        background-color: #fee2e2;
    }

    .summary-box.bg-yellow {
        background-color: #fef9c3;
    }

    .summary-title {
        font-size: 0.9rem;
        color: #6b7280;
        margin-bottom: 0.5rem;
    }

    .summary-value {
        font-size: 1.75rem;
        font-weight: bold;
        color: #111827;
    }

    @media (max-width: 768px) {
        .summary-bar {
            flex-direction: column;
        }
    }
</style>
@endpush

@section('content')

<!-- ✅ Welcome Card -->
<div class="card" id="welcomeCard">
    <div class="welcome-card-header">
        <span>Welcome, {{ ucwords(auth()->user()->name) }}!</span>
        <button class="close-btn" onclick="document.getElementById('welcomeCard').style.display='none';">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <div class="welcome-card-body">
        You are logged in as an <strong>Admin</strong>.
    </div>
</div>

<!-- ✅ Application Summary (Horizontal) -->
<div class="card">
    <div class="card-body">
        <h3 class="text-lg font-semibold mb-2 text-gray-800">📊 Application Summary</h3>
        <div class="summary-bar">
            <div class="summary-box bg-gray">
                <div class="summary-title">Total Applications</div>
                <div class="summary-value">{{ $total ?? 0 }}</div>
            </div>
            <div class="summary-box bg-green">
                <div class="summary-title">Approved</div>
                <div class="summary-value">{{ $approved ?? 0 }}</div>
            </div>
            <div class="summary-box bg-red">
                <div class="summary-title">Rejected</div>
                <div class="summary-value">{{ $rejected ?? 0 }}</div>
            </div>
            <div class="summary-box bg-yellow">
                <div class="summary-title">Pending</div>
                <div class="summary-value">{{ $pending ?? 0 }}</div>
            </div>
        </div>
    </div>
</div>

@endsection
