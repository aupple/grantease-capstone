@extends('layouts')

@section('content')
<div class="dashboard-container">
    <div class="welcome-section">
        <h2>Welcome, John Doe!</h2>
        <p>Manage your scholarship applications</p>
    </div>

    <div class="dashboard-grid">
        <!-- Application Status Card -->
        <div class="card application-status">
            <div class="card-header">
                <h3>Application Status</h3>
                <span class="status-badge pending">
                    <i class="icon icon-clipboard-list"></i> Under Review
                </span>
            </div>
            <div class="application-details">
                <h4>Academic Excellence Scholarship</h4>
                <p>Submitted on: May 15, 2023</p>
                <a href="/application/status" class="btn btn-secondary small">View Details</a>
            </div>
        </div>

        <!-- Quick Actions Card -->
        <div class="card">
            <h3>Quick Actions</h3>
            <a href="/application" class="btn btn-outline full-width">
                <i class="icon icon-file-text"></i> New Application
            </a>
            <a href="/application/status" class="btn btn-outline full-width">
                <i class="icon icon-clipboard-list"></i> View Status
            </a>
        </div>
    </div>

    <!-- Available Scholarships -->
    <div class="card scholarships">
        <h3>Available Scholarships</h3>

        <div class="scholarship">
            <div class="scholarship-header">
                <div>
                    <h4>DOST Scholarship</h4>
                    <p>For students pursuing Science, Technology, Engineering, and Mathematics courses</p>
                </div>
                <span class="status-open">Open</span>
            </div>
            <div class="scholarship-footer">
                <p class="deadline">Deadline: June 30, 2023</p>
                <a href="/application" class="btn small">Apply Now</a>
            </div>
        </div>

        <div class="scholarship">
            <div class="scholarship-header">
                <div>
                    <h4>CHED Scholarship</h4>
                    <p>For academically qualified students with financial needs</p>
                </div>
                <span class="status-open">Open</span>
            </div>
            <div class="scholarship-footer">
                <p class="deadline">Deadline: July 15, 2023</p>
                <a href="/application" class="btn small">Apply Now</a>
            </div>
        </div>
    </div>
</div>
@endsection
