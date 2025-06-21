@extends('layouts.home')

@section('content')
<section class="grid-container">

    <!-- Application Status Card -->
    <div class="card application-status">
        <h3>Application Status</h3>
        <div class="status-box">
            <div>
                <strong>Academic Excellence Scholarship</strong><br>
                <small>Submitted on: May 15, 2023</small>
            </div>
            <span class="badge">Under Review</span>
            <button class="view-btn">View Details</button>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="card quick-actions">
        <h3>Quick Actions</h3>
        <button class="action-btn">
            <i class="fa fa-file-alt"></i> New Application
        </button>
        <button class="action-btn">
            <i class="fa fa-tasks"></i> View Status
        </button>
    </div>

    <!-- Available Scholarships -->
    <div class="card available-scholarships">
        <h3>Available Scholarships</h3>

        <div class="scholarship">
            <div>
                <strong>DOST Scholarship</strong><br>
                <span>For students pursuing Science, Technology, Engineering, and Mathematics courses</span><br>
                <small>Deadline: June 30, 2023</small>
            </div>
            <div class="right">
                <span class="open">Open</span>
                <button class="apply-btn">Apply Now</button>
            </div>
        </div>

        <div class="scholarship">
            <div>
                <strong>CHED Scholarship</strong><br>
                <span>For academically qualified students with financial needs</span><br>
                <small>Deadline: July 15, 2023</small>
            </div>
            <div class="right">
                <span class="open">Open</span>
                <button class="apply-btn">Apply Now</button>
            </div>
        </div>
    </div>
    
</section>
@endsection
