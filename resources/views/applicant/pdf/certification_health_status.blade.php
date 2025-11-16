<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Certification of Health Status</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 13px;
            margin: 40px;
            line-height: 1.6;
        }

        h2 {
            text-align: center;
            text-transform: uppercase;
            font-size: 16px;
            margin-bottom: 10px;
        }

        .section {
            margin-top: 30px;
        }

        .field {
            margin-bottom: 8px;
        }

        .label {
            font-weight: bold;
        }

        .signature {
            margin-top: 60px;
            text-align: right;
        }
    </style>
</head>

<body>

    <h2>Certification of Health Status</h2>

    <p>This is to certify that:</p>

    <div class="section">
        <div class="field"><span class="label">Full Name:</span> {{ $application->last_name }},
            {{ $application->first_name }} {{ $application->middle_name }}</div>
        <div class="field"><span class="label">Birthday:</span>
            {{ $application->date_of_birth ? \Carbon\Carbon::parse($application->date_of_birth)->format('F d, Y') : 'N/A' }}
        </div>
        <div class="field"><span class="label">Gender:</span> {{ $application->sex ?? 'N/A' }}</div>
        <div class="field"><span class="label">Address:</span>
            {{ $application->address_street }},
            {{ $application->barangay }},
            {{ $application->city }},
            {{ $application->province }},
            {{ $application->zip_code }}
        </div>
    </div>

    <div class="section">
        <p>has undergone a health check-up and is found to be:</p>
        <p style="margin-left: 20px;">[ &nbsp;&nbsp; ] Physically Fit</p>
        <p style="margin-left: 20px;">[ &nbsp;&nbsp; ] With Health Concerns: ____________________________________</p>
    </div>

    <div class="section signature">
        ___________________________<br>
        Physician's Signature
    </div>

    <div class="section" style="margin-top: 40px;">
        <p>I hereby certify that the information above is true and correct to the best of my knowledge.</p>
        <div class="signature">
            ___________________________<br>
            {{ $application->first_name }} {{ $application->last_name }}<br>
            Applicant
        </div>
    </div>

</body>

</html>
