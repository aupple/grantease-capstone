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

        th,
        td {
            border: 1px solid #000;
            padding: 3px 5px;
            text-align: center;
            word-wrap: break-word;
        }

        th {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        h2,
        h3 {
            text-align: center;
            margin: 0;
            padding: 0;
        }

        .header {
            margin-bottom: 15px;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
            position: relative;
        }

        .header-content {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }

        .logo img {
            width: 60px;
            height: 60px;
        }

        .header-text {
            text-align: center;
            line-height: 1.4;
        }

        .header-text hr {
            border: none;
            border-top: 1px solid #555;
            width: 250px;
            margin: 5px auto;
        }

        .code {
            position: absolute;
            right: 0;
            bottom: 0;
            font-size: 10px;
            border: 1px solid #000;
            padding: 2px 5px;
        }

        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
            font-size: 10px;
        }

        .sig-section {
            width: 45%;
            text-align: center;
        }

        .sig-line {
            margin: 30px auto 3px;
            width: 80%;
            border-bottom: 1px solid #000;
        }

        .sig-label {
            font-size: 9px;
            color: #000;
        }
    </style>

</head>

@php
    if (!function_exists('formatValue')) {
        function formatValue($value)
        {
            if ($value instanceof \Illuminate\Support\Collection) {
                $value = $value->toArray();
            }
            if (is_array($value)) {
                return implode(', ', \Illuminate\Support\Arr::flatten($value)) ?: 'N/A';
            }
            if (is_string($value)) {
                $decoded = json_decode($value, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    return implode(', ', \Illuminate\Support\Arr::flatten($decoded)) ?: 'N/A';
                }
                return $value === '' ? 'N/A' : $value;
            }
            if (is_object($value)) {
                return method_exists($value, '__toString') ? (string) $value : (json_encode($value) ?: 'N/A');
            }
            return $value ?: 'N/A';
        }
    }

    if (!function_exists('getLocationName')) {
        function getLocationName($code, $type = 'city')
        {
            if (empty($code) || $code === 'N/A') {
                return 'N/A';
            }

            $jsonUrls = [
                'province' => 'https://psgc.gitlab.io/api/provinces/',
                'city' => 'https://psgc.gitlab.io/api/cities-municipalities/',
                'barangay' => 'https://psgc.gitlab.io/api/barangays/',
                'district' => 'https://psgc.gitlab.io/api/districts/',
            ];

            if (!isset($jsonUrls[$type])) {
                return 'Unknown';
            }
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

<div class="header">
    <div class="header-content">
        <div class="logo">
            <img src="{{ asset('images/Dost.png') }}" alt="DOST Logo">
        </div>
        <div class="header-text">
            <p><strong>Department of Science and Technology</strong><br>
                <span>SCIENCE EDUCATION INSTITUTE</span>
            </p>
            <hr>
            <p><strong>Graduate Scholarship Program</strong></p>
            <p><strong>REGISTRY OF POTENTIAL QUALIFIERS</strong></p>
        </div>
    </div>
    <div class="code">STD-2024A</div>
</div>

<table>
    <thead>
        <tr>
            @php
                $allColumns = [
                    'no' => 'No.',
                    'last_name' => 'Last Name',
                    'first_name' => 'First Name',
                    'middle_name' => 'Middle Name',
                    'suffix' => 'Suffix',
                    'street' => 'Street',
                    'barangay' => 'Barangay',
                    'city' => 'City/Town',
                    'province' => 'Province',
                    'zipcode' => 'Zipcode',
                    'district' => 'District',
                    'region' => 'Region',
                    'email' => 'Email Address',
                    'birthday' => 'Birthday (YYYY-MM-DD)',
                    'contact_no' => 'Contact No.',
                    'gender' => 'Gender',
                    'course_completed' => 'Course Completed',
                    'university_graduated' => 'University Graduated',
                    'entry' => 'Entry',
                    'level' => 'Level',
                    'intended_degree' => 'Intended Masters/Doctoral Degree',
                    'university' => 'University',
                    'thesis_title' => 'Thesis/Dissertation Title',
                    'units_required' => 'No. of Units Required',
                    'units_earned' => 'No. of Units Earned (Lateral)',
                    'percent_completed' => '% Load Completed (Lateral)',
                    'duration' => 'Duration of Scholarship',
                    'remarks' => 'Remarks',
                ];
            @endphp

            @foreach ($allColumns as $key => $label)
                @if (in_array($key, $selectedCols))
                    <th>{{ $label }}</th>
                @endif
            @endforeach
        </tr>
    </thead>

    <tbody>
        @foreach ($applicants as $index => $a)
            <tr>
                @foreach ($allColumns as $key => $label)
                    @if (in_array($key, $selectedCols))
                        <td>
                            @switch($key)
                                @case('no')
                                    {{ $index + 1 }}
                                @break

                                @case('last_name')
                                    {{ formatValue($a->last_name) }}
                                @break

                                @case('first_name')
                                    {{ formatValue($a->first_name) }}
                                @break

                                @case('middle_name')
                                    {{ formatValue($a->middle_name) }}
                                @break

                                @case('suffix')
                                    {{ formatValue($a->suffix) }}
                                @break

                                @case('street')
                                    {{ formatValue($a->address_street) }}
                                @break

                                @case('barangay')
                                    {{ getLocationName($a->barangay, 'barangay') }}
                                @break

                                @case('city')
                                    {{ getLocationName($a->city, 'city') }}
                                @break

                                @case('province')
                                    {{ getLocationName($a->province, 'province') }}
                                @break

                                @case('zipcode')
                                    {{ formatValue($a->zip_code) }}
                                @break

                                @case('district')
                                    {{ formatValue($a->district) }}
                                @break

                                @case('region')
                                    {{ formatValue($a->region) }}
                                @break

                                @case('email')
                                    {{ formatValue($a->email_address) }}
                                @break

                                @case('birthday')
                                    {{ formatValue($a->date_of_birth) }}
                                @break

                                @case('contact_no')
                                    {{ formatValue($a->telephone_nos) }}
                                @break

                                @case('gender')
                                    {{ strtoupper(formatValue($a->sex)) }}
                                @break

                                @case('course_completed')
                                    {{ formatValue($a->bs_degree) }}
                                @break

                                @case('university_graduated')
                                    {{ formatValue($a->bs_university) }}
                                @break

                                @case('entry')
                                    {{ ucfirst(formatValue($a->applicant_status)) }}
                                @break

                                @case('level')
                                    {{ strtoupper(formatValue($a->scholarship_type)) }}
                                @break

                                @case('intended_degree')
                                    {{ formatValue($a->intended_degree) }}
                                @break

                                @case('university')
                                    {{ formatValue($a->applicant_status === 'new' ? $a->new_applicant_university : $a->lateral_university_enrolled) }}
                                @break

                                @case('thesis_title')
                                    {{ formatValue($a->thesis_title) }}
                                @break

                                @case('units_required')
                                    {{ formatValue($a->units_required) }}
                                @break

                                @case('units_earned')
                                    {{ formatValue($a->lateral_units_earned) }}
                                @break

                                @case('percent_completed')
                                    {{ formatValue($a->percent_completed) }}
                                @break

                                @case('duration')
                                    {{ formatValue($a->duration) }}
                                @break

                                @case('remarks')
                                    {{ formatValue($a->remarks) }}
                                @break

                                @default
                                    N/A
                            @endswitch
                        </td>
                    @endif
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
<div class="signatures">
    <div class="sig-section">
        <p><strong>Prepared by:</strong></p>
        <div class="sig-line"></div>
        <p class="sig-label">Name and Signature of Project Staff</p>
    </div>
    <div class="sig-section">
        <p><strong>Endorsed by:</strong></p>
        <div class="sig-line"></div>
        <p class="sig-label">Name and Signature of Project Leader</p>
    </div>
</div>
<script>
    window.onload = () => window.print();
</script>
</body>

</html>
