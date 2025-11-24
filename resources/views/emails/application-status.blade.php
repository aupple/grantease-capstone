<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 8px 8px 0 0;
            text-align: center;
        }

        .content {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 8px 8px;
        }

        .status {
            font-size: 24px;
            font-weight: bold;

            @if (in_array(strtolower($status), ['approved', 'confirmed', 'document_verified']))
                color: #10b981;
            @elseif(strtolower($status) === 'rejected')
                color: #ef4444;
            @else
                color: #3b82f6;
            @endif
            margin: 20px 0;
        }

        .remarks {
            background: white;
            padding: 15px;
            border-left: 4px solid #667eea;
            margin: 20px 0;
        }

        .button {
            display: inline-block;
            padding: 12px 24px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white !important;
            /* Added !important to ensure it stays white */
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 0;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>GrantEase Scholarship Management System</h2>
        <p>{{ $programType ?? 'Scholarship' }} Program</p>
    </div>

    <div class="content">
        <p>Hello {{ $applicantName ?? 'Scholar' }},</p>

        @if ($programType === 'CHED')
            <p>We are writing to inform you about an update on your CHED scholar registration in our system.</p>
        @else
            <p>We are writing to inform you about an update on your {{ $programType ?? 'scholarship' }} application.</p>
        @endif

        <div class="status">
            Status:
            @if ($programType === 'CHED' && strtolower($status) === 'approved')
                Confirmed
            @elseif(strtolower($status) === 'document_verification')
                Document Verified
            @else
                {{ ucfirst($status) }}
            @endif
        </div>

        @if ($remarks)
            <div class="remarks">
                <strong>Remarks:</strong>
                <p>{{ $remarks }}</p>
            </div>
        @endif

        @if ($programType === 'CHED')
            <p>You can log in to your CHED scholar dashboard to view your profile and submit required information.</p>
        @else
            <p>You can log in to your account to view more details about your application.</p>
        @endif

        @php
            // Set dashboard URL based on program type
            $dashboardUrl =
                $programType === 'CHED'
                    ? url('/ched/dashboard') // CHED scholars dashboard
                    : url('/applicant/dashboard'); // DOST applicants dashboard
        @endphp

        <center>
            <a href="{{ $dashboardUrl }}" class="button">
                @if ($programType === 'CHED')
                    View Scholar Dashboard
                @else
                    View Dashboard
                @endif
            </a>
        </center>

        <div class="footer">
            <p>Thank you for using GrantEase!</p>
            <p><small>This is an automated email. Please do not reply.</small></p>
        </div>
    </div>
</body>

</html>
