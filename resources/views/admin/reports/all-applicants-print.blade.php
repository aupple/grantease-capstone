<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>List of Applicants</title>
    <style>
        @page {
            size: Legal landscape;
            margin: 20px;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        th, td {
            border: 1px solid #000;
            padding: 3px 5px;
            text-align: center;
            word-wrap: break-word;
        }
        th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        h2, h3 {
            text-align: center;
            margin: 0;
            padding: 0;
        }
        .header {
            margin-bottom: 15px;
        }
    </style>
</head>

@php
if (! function_exists('formatValue')) {
    function formatValue($value) {
        if ($value instanceof \Illuminate\Support\Collection) $value = $value->toArray();
        if (is_array($value)) return implode(', ', \Illuminate\Support\Arr::flatten($value)) ?: 'N/A';
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return implode(', ', \Illuminate\Support\Arr::flatten($decoded)) ?: 'N/A';
            }
            return $value === '' ? 'N/A' : $value;
        }
        if (is_object($value)) return method_exists($value, '__toString') ? (string)$value : (json_encode($value) ?: 'N/A');
        return $value ?: 'N/A';
    }
}

if (! function_exists('getLocationName')) {
    function getLocationName($code, $type = 'city') {
        if (empty($code) || $code === 'N/A') return 'N/A';

        $jsonUrls = [
            'province' => 'https://psgc.gitlab.io/api/provinces/',
            'city' => 'https://psgc.gitlab.io/api/cities-municipalities/',
            'barangay' => 'https://psgc.gitlab.io/api/barangays/',
            'district' => 'https://psgc.gitlab.io/api/districts/',
        ];

        if (!isset($jsonUrls[$type])) return 'Unknown';
        $cacheFile = storage_path("app/psgc_$type.json");
        $data = file_exists($cacheFile) ? json_decode(file_get_contents($cacheFile), true) : null;

        if (empty($data)) {
            try {
                $json = @file_get_contents($jsonUrls[$type]);
                $data = json_decode($json, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {
                    file_put_contents($cacheFile, json_encode($data));
                }
            } catch (\Exception $e) {
                return 'Unknown';
            }
        }

        if (is_array($data)) {
            foreach ($data as $item) {
                if (isset($item['code']) && $item['code'] == $code) {
                    return $item['name'];
                }
            }
        }

        return 'Unknown';
    }
}
@endphp

<body>
    <div class="header">
        <h2>DOST-SEI STRAND PROGRAM</h2>
        <h3>List of Applicants</h3>
    </div>

    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Last Name</th>
                <th>First Name</th>
                <th>Middle Name</th>
                <th>Suffix</th>
                <th>Street</th>
                <th>Barangay</th>
                <th>City/Town</th>
                <th>Province</th>
                <th>Zipcode</th>
                <th>District</th>
                <th>Region</th>
                <th>Email Address</th>
                <th>Birthday (YYYY-MM-DD)</th>
                <th>Contact No.</th>
                <th>Gender</th>
                <th>Course Completed</th>
                <th>University Graduated</th>
                <th>Entry</th>
                <th>Field</th>
                <th>Intended Master’s/Doctoral Degree</th>
                <th>University</th>
                <th>Thesis/Dissertation Title</th>
                <th>No. of Units Required</th>
                <th>No. of Units Earned (Lateral)</th>
                <th>% Load Completed (Lateral)</th>
                <th>Duration of Scholarship</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach($applicants as $app)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ formatValue($app->last_name) }}</td>
                    <td>{{ formatValue($app->first_name) }}</td>
                    <td>{{ formatValue($app->middle_name) }}</td>
                    <td>{{ formatValue($app->suffix) }}</td>
                    <td>{{ formatValue($app->address_street) }}</td>
                    <td>{{ getLocationName($app->barangay, 'barangay') }}</td>
                    <td>{{ getLocationName($app->city, 'city') }}</td>
                    <td>{{ getLocationName($app->province, 'province') }}</td>
                    <td>{{ formatValue($app->zip_code) }}</td>
                    <td>{{ getLocationName($app->district, 'district') }}</td>
                    <td>{{ formatValue($app->region) }}</td>
                    <td>{{ formatValue($app->email_address) }}</td>
                    <td>{{ formatValue($app->date_of_birth) }}</td>
                    <td>{{ formatValue($app->telephone_nos) }}</td>
                    <td>{{ strtoupper(formatValue($app->sex)) }}</td>
                    <td>{{ formatValue($app->bs_degree) }}</td> 
                    <td>{{ formatValue($app->bs_university) }}</td> 
                    <td>{{ ucfirst(formatValue($app->entry)) }}</td>
                    <td>{{ strtoupper(formatValue($app->bs_field)) }}</td> 
                    <td>{{ formatValue($app->intended_degree) }}</td> 
                    <td>{{ formatValue($app->university) }}</td>
                    <td>{{ formatValue($app->research_title) }}</td>
                    <td>{{ formatValue($app->units_required) }}</td>
                    <td>{{ formatValue($app->units_earned) }}</td>
                    <td>{{ formatValue($app->percent_load) }}</td>
                    <td>{{ formatValue($app->duration) }}</td>
                    <td>{{ formatValue($app->remarks) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
