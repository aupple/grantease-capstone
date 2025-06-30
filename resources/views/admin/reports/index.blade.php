<!DOCTYPE html>
<html>
<head>
    <title>Admin Reports</title>
</head>
<body>
    <h2>Application Report Summary</h2>

    <ul>
        <li><strong>Total Applications:</strong> {{ $total }}</li>
        <li><strong>Approved:</strong> {{ $approved }}</li>
        <li><strong>Rejected:</strong> {{ $rejected }}</li>
        <li><strong>Pending:</strong> {{ $pending }}</li>
    </ul>

    <!-- 📥 PDF Download Button -->
    <p>
        <a href="{{ route('admin.reports.pdf') }}" target="_blank">📥 Download PDF Report</a>
    </p>

    <br><a href="{{ route('admin.dashboard') }}">← Back to Dashboard</a>
</body>
</html>
