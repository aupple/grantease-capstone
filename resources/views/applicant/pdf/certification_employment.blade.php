<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Certification of Employment</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 13px;
            margin: 40px;
            color: #000;
        }

        h2,
        h3 {
            text-align: center;
            margin: 0;
        }

        h2 {
            font-size: 20px;
        }

        h3 {
            font-size: 16px;
            margin-bottom: 30px;
        }

        p {
            margin-bottom: 15px;
            text-align: justify;
        }

        .signature {
            margin-top: 60px;
        }
    </style>
</head>

<body>

    <h2>Republic of the Philippines</h2>
    <h3>Department of Science and Technology</h3>
    <h3>Certification of Employment</h3>

    <p>This is to certify that <strong>{{ $application->sex == 'Male' ? 'Mr.' : 'Ms.' }} {{ $application->first_name }}
            {{ $application->middle_name }} {{ $application->last_name }}</strong> is currently employed at
        <strong>{{ $application->employed_company_name ?? '_______________' }}</strong> as a
        <strong>{{ $application->employed_position ?? '_______________' }}</strong>, and has been in active service
        since <strong>{{ $application->employed_length_of_service ?? '_______________' }}</strong>.</p>

    <p>He/She is a permanent employee and is presently assigned to
        <strong>{{ $application->employed_company_address ?? '_______________' }}</strong>.</p>

    <p>This certification is issued upon the request of the employee for purposes of <strong>DOST Graduate Scholarship
            application</strong>.</p>

    <div class="signature">
        <p><strong>Issued on:</strong> {{ now()->format('F d, Y') }}</p>
        <br><br>
        <p><strong>Certified by:</strong></p>
        <p>______________________________</p>
        <p><em>School Head / HR Officer</em></p>
    </div>

</body>

</html>
