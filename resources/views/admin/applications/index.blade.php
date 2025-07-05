<!DOCTYPE html>
<html>
<head>
    <title>Submitted Applications</title>
    <style>
        .filter-link {
            padding: 6px 12px;
            margin-right: 6px;
            border-radius: 4px;
            text-decoration: none;
            border: 1px solid #ccc;
            color: #000;
        }
        .filter-link.active {
            background-color: #2563eb;
            color: #fff;
        }
    </style>
</head>
<body>
    <h1>Submitted Applications</h1>

    <!-- ✅ Filter Buttons -->
    <div style="margin-bottom: 20px;">
        <strong>Filter by Status:</strong>
        <a href="{{ route('admin.applications') }}" class="filter-link {{ is_null($status) ? 'active' : '' }}">All</a>
        <a href="{{ route('admin.applications', ['status' => 'submitted']) }}" class="filter-link {{ $status === 'submitted' ? 'active' : '' }}">Submitted</a>
        <a href="{{ route('admin.applications', ['status' => 'under_review']) }}" class="filter-link {{ $status === 'under_review' ? 'active' : '' }}">Under Review</a>
        <a href="{{ route('admin.applications', ['status' => 'document_verification']) }}" class="filter-link {{ $status === 'document_verification' ? 'active' : '' }}">Document Verification</a>
        <a href="{{ route('admin.applications', ['status' => 'for_interview']) }}" class="filter-link {{ $status === 'for_interview' ? 'active' : '' }}">For Interview</a>
        <a href="{{ route('admin.applications', ['status' => 'approved']) }}" class="filter-link {{ $status === 'approved' ? 'active' : '' }}">Approved</a>
        <a href="{{ route('admin.applications', ['status' => 'rejected']) }}" class="filter-link {{ $status === 'rejected' ? 'active' : '' }}">Rejected</a>

    </div>

    <!-- ✅ Current Filter -->
    @if ($status)
        <p><strong>Showing:</strong> {{ ucfirst($status) }} Applications</p>
    @endif

    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th>Applicant Name</th>
                <th>Program</th>
                <th>School</th>
                <th>Year Level</th>
                <th>Status</th>
                <th>Submitted</th>
                <th>Action</th>
            </tr>
        </thead>

<!-- 🔍 Search Form -->
<form method="GET" action="{{ route('admin.applications') }}" style="margin-bottom: 20px;">
    <input type="text" name="search" placeholder="Search by name, program, or status" value="{{ request('search') }}">
    @if (request('status'))
        <input type="hidden" name="status" value="{{ request('status') }}">
    @endif
    <button type="submit">Search</button>
</form>


        <tbody>
            @forelse ($applications as $app)
                <tr>
                    <td>{{ $app->user->full_name ?? $app->user->first_name . ' ' . $app->user->last_name }}</td>
                    <td>{{ $app->program }}</td>
                    <td>{{ $app->school }}</td>
                    <td>{{ $app->year_level }}</td>
                    <td>{{ ucfirst($app->status ?? 'pending') }}</td>
                    <td>{{ $app->submitted_at ?? $app->created_at }}</td>
                    <td>
                        <a href="{{ route('admin.applications.show', $app->application_form_id) }}">
                            View
                        </a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7">No applications found for this filter.</td></tr>
            @endforelse
        </tbody>
    </table>

    <br>
    <a href="{{ route('admin.dashboard') }}">← Back to Dashboard</a>
</body>
</html>
