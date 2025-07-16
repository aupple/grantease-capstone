<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Scholarship Application Form - STRAND
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
           

                <!-- Application Header -->
                <div class="mb-6 text-center">
                    <h3 class="text-xl font-bold mt-4">APPLICATION FORM</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="academic_year" class="block text-sm font-medium text-gray-700">Academic Year</label>
                        <input type="text" name="academic_year" id="academic_year" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="e.g., 2024-2025">
                    </div>
                    <div>
                        <label for="school_term" class="block text-sm font-medium text-gray-700">School Term</label>
                        <select name="school_term" id="school_term" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">Select Term</option>
                            <option value="First">First Semester/Term</option>
                            <option value="Second">Second Semester/Term</option>
                            <option value="Third">Third Semester/Term</option>
                        </select>
                    </div>
                </div>

                <div class="mb-6">
                    <label for="application_no" class="block text-sm font-medium text-gray-700">Application No.</label>
                    <input type="text" name="application_no" id="application_no" value="{{ 'STRAND-' . uniqid() }}" readonly class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-100 sm:text-sm">
                </div>

                <div class="mb-6">
                    <label for="passport_picture" class="block text-sm font-medium text-gray-700">Attach 1 latest passport size picture</label>
                    <input type="file" name="passport_picture" id="passport_picture" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>

                <!-- Form 1. Information Sheet -->
                <h3 class="text-xl font-bold mb-4 border-b pb-2">Form 1. Information Sheet</h3>

                <!-- I. PERSONAL INFORMATION -->
                <h4 class="text-lg font-semibold mb-3">I. PERSONAL INFORMATION</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                        <input type="text" name="last_name" id="last_name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                    </div>
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                        <input type="text" name="first_name" id="first_name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                    </div>
                    <div>
                        <label for="middle_name" class="block text-sm font-medium text-gray-700">Middle Name</label>
                        <input type="text" name="middle_name" id="middle_name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                    </div>
                </div>

                <div class="mb-4">
                    <label for="permanent_address" class="block text-sm font-medium text-gray-700">Permanent Address</label>
                    <input type="text" name="permanent_address_no" id="permanent_address_no" placeholder="No." class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm mb-2">
                    <input type="text" name="permanent_address_street" id="permanent_address_street" placeholder="Street" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm mb-2">
                    <input type="text" name="permanent_address_barangay" id="permanent_address_barangay" placeholder="Barangay" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm mb-2">
                    <input type="text" name="permanent_address_city" id="permanent_address_city" placeholder="City/Municipality" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm mb-2">
                    <input type="text" name="permanent_address_province" id="permanent_address_province" placeholder="Province" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                    <div>
                        <label for="zip_code" class="block text-sm font-medium text-gray-700">Zip Code</label>
                        <input type="text" name="zip_code" id="zip_code" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                    </div>
                    <div>
                        <label for="region" class="block text-sm font-medium text-gray-700">Region</label>
                        <input type="text" name="region" id="region" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                    </div>
                    <div>
                        <label for="district" class="block text-sm font-medium text-gray-700">District</label>
                        <input type="text" name="district" id="district" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                    </div>
                    <div>
                        <label for="passport_no" class="block text-sm font-medium text-gray-700">Passport No.</label>
                        <input type="text" name="passport_no" id="passport_no" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                    </div>
                </div>

                <div class="mb-4">
                    <label for="email_address" class="block text-sm font-medium text-gray-700">E-mail Address</label>
                    <input type="email" name="email_address" id="email_address" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                </div>

                <div class="mb-4">
                    <label for="current_mailing_address" class="block text-sm font-medium text-gray-700">Current Mailing Address</label>
                    <input type="text" name="current_mailing_address" id="current_mailing_address" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                </div>

                <div class="mb-4">
                    <label for="telephone_nos" class="block text-sm font-medium text-gray-700">Telephone Nos. (Landline/Mobile)</label>
                    <input type="text" name="telephone_nos" id="telephone_nos" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                    <div>
                        <label for="civil_status" class="block text-sm font-medium text-gray-700">Civil Status</label>
                        <select name="civil_status" id="civil_status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                            <option value="">Select</option>
                            <option value="Single">Single</option>
                            <option value="Married">Married</option>
                            <option value="Widowed">Widowed</option>
                            <option value="Divorced">Divorced</option>
                        </select>
                    </div>
                    <div>
                        <label for="date_of_birth" class="block text-sm font-medium text-gray-700">Date of Birth</label>
                        <input type="date" name="date_of_birth" id="date_of_birth" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                    </div>
                    <div>
                        <label for="age" class="block text-sm font-medium text-gray-700">Age</label>
                        <input type="number" name="age" id="age" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                    </div>
                    <div>
                        <label for="sex" class="block text-sm font-medium text-gray-700">Sex</label>
                        <select name="sex" id="sex" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                            <option value="">Select</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label for="father_name" class="block text-sm font-medium text-gray-700">Father’s Name</label>
                        <input type="text" name="father_name" id="father_name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                    </div>
                    <div>
                        <label for="mother_name" class="block text-sm font-medium text-gray-700">Mother’s Name</label>
                        <input type="text" name="mother_name" id="mother_name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                    </div>
                </div>

                <<!-- II. EDUCATIONAL BACKGROUND -->
<h4 class="text-lg font-semibold mb-3">II. EDUCATIONAL BACKGROUND</h4>

<!-- BS Degree -->
<div class="mb-6">
    <p class="block text-sm font-medium text-gray-700 mb-2">BS Degree</p>
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-2">
        <input type="text" name="bs_period" placeholder="PERIOD (Year Started – Year Ended)" class="block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
        <input type="text" name="bs_field" placeholder="FIELD" class="block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
        <input type="text" name="bs_university" placeholder="UNIVERSITY/SCHOOL" class="block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">

        <!-- Scholarship Section with Checkboxes -->
        <div class="block w-full border-gray-300 rounded-md shadow-sm sm:text-sm p-2 border">
            <span class="text-sm text-gray-700 font-medium">SCHOLARSHIP (if applicable)</span>
            <div class="mt-1 flex flex-col gap-1">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="bs_scholarship_type[]" value="PSHS" class="form-checkbox">
                    <span class="ml-2 text-sm text-gray-700">PSHS</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="checkbox" name="bs_scholarship_type[]" value="RA 7687" class="form-checkbox">
                    <span class="ml-2 text-sm text-gray-700">RA 7687</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="checkbox" name="bs_scholarship_type[]" value="MERIT" class="form-checkbox">
                    <span class="ml-2 text-sm text-gray-700">MERIT</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="checkbox" name="bs_scholarship_type[]" value="RA 10612" class="form-checkbox">
                    <span class="ml-2 text-sm text-gray-700">RA 10612</span>
                </label>
                <label class="inline-flex items-center mt-1">
                    <span class="text-sm text-gray-700">OTHERS:</span>
                    <input type="text" name="bs_scholarship_others" class="ml-2 border-gray-300 rounded-md shadow-sm sm:text-sm w-32">
                </label>
            </div>
        </div>

        <input type="text" name="bs_remarks" placeholder="REMARKS" class="block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
    </div>
</div>

<!-- MS Degree -->
<div class="mb-6">
    <p class="block text-sm font-medium text-gray-700 mb-2">MS Degree</p>
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-2">
        <input type="text" name="ms_period" placeholder="PERIOD (Year Started – Year Ended)" class="block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
        <input type="text" name="ms_field" placeholder="FIELD" class="block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
        <input type="text" name="ms_university" placeholder="UNIVERSITY/SCHOOL" class="block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">

        <!-- Scholarship Section -->
        <div class="block w-full border-gray-300 rounded-md shadow-sm sm:text-sm p-2 border">
            <span class="text-sm text-gray-700 font-medium">SCHOLARSHIP (if applicable)</span>
            <div class="mt-1 flex flex-col gap-1">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="ms_scholarship_type[]" value="NSDB/NSTA" class="form-checkbox">
                    <span class="ml-2 text-sm text-gray-700">NSDB/NSTA</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="checkbox" name="ms_scholarship_type[]" value="ASTHRDP" class="form-checkbox">
                    <span class="ml-2 text-sm text-gray-700">ASTHRDP</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="checkbox" name="ms_scholarship_type[]" value="ERDT" class="form-checkbox">
                    <span class="ml-2 text-sm text-gray-700">ERDT</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="checkbox" name="ms_scholarship_type[]" value="COUNCIL/SEI" class="form-checkbox">
                    <span class="ml-2 text-sm text-gray-700">COUNCIL/SEI</span>
                </label>
                <label class="inline-flex items-center mt-1">
                    <span class="text-sm text-gray-700">OTHERS:</span>
                    <input type="text" name="ms_scholarship_others" class="ml-2 border-gray-300 rounded-md shadow-sm sm:text-sm w-32">
                </label>
            </div>
        </div>

        <input type="text" name="ms_remarks" placeholder="REMARKS" class="block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
    </div>
</div>

<!-- PHD Degree -->
<div class="mb-6">
    <p class="block text-sm font-medium text-gray-700 mb-2">PHD Degree</p>
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-2">
        <input type="text" name="phd_period" placeholder="PERIOD (Year Started – Year Ended)" class="block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
        <input type="text" name="phd_field" placeholder="FIELD" class="block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
        <input type="text" name="phd_university" placeholder="UNIVERSITY/SCHOOL" class="block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">

        <!-- Scholarship Section -->
        <div class="block w-full border-gray-300 rounded-md shadow-sm sm:text-sm p-2 border">
            <span class="text-sm text-gray-700 font-medium">SCHOLARSHIP (if applicable)</span>
            <div class="mt-1 flex flex-col gap-1">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="phd_scholarship_type[]" value="NSDB/NSTA" class="form-checkbox">
                    <span class="ml-2 text-sm text-gray-700">NSDB/NSTA</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="checkbox" name="phd_scholarship_type[]" value="ASTHRDP" class="form-checkbox">
                    <span class="ml-2 text-sm text-gray-700">ASTHRDP</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="checkbox" name="phd_scholarship_type[]" value="ERDT" class="form-checkbox">
                    <span class="ml-2 text-sm text-gray-700">ERDT</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="checkbox" name="phd_scholarship_type[]" value="COUNCIL/SEI" class="form-checkbox">
                    <span class="ml-2 text-sm text-gray-700">COUNCIL/SEI</span>
                </label>
                <label class="inline-flex items-center mt-1">
                    <span class="text-sm text-gray-700">OTHERS:</span>
                    <input type="text" name="phd_scholarship_others" class="ml-2 border-gray-300 rounded-md shadow-sm sm:text-sm w-32">
                </label>
            </div>
        </div>

        <input type="text" name="phd_remarks" placeholder="REMARKS" class="block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
    </div>
</div>

                <!-- IV. CAREER/EMPLOYMENT INFORMATION -->
                <h4 class="text-lg font-semibold mb-3">IV. CAREER/EMPLOYMENT INFORMATION</h4>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">a. Present Employment Status</label>
                    <div class="mt-1 flex flex-wrap gap-4">
                        <label class="inline-flex items-center">
                            <input type="radio" name="employment_status" value="Permanent" class="form-radio">
                            <span class="ml-2 text-sm text-gray-700">Permanent</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="employment_status" value="Contractual" class="form-radio">
                            <span class="ml-2 text-sm text-gray-700">Contractual</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="employment_status" value="Probationary" class="form-radio">
                            <span class="ml-2 text-sm text-gray-700">Probationary</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="employment_status" value="Self-employed" class="form-radio">
                            <span class="ml-2 text-sm text-gray-700">Self-employed</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="employment_status" value="Unemployed" class="form-radio">
                            <span class="ml-2 text-sm text-gray-700">Unemployed</span>
                        </label>
                    </div>
                </div>

                <div id="employed_fields" class="mb-6 border p-4 rounded-md hidden">
                    <p class="font-semibold mb-2">a.1 For those who are presently employed*</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-2">
                        <div>
                            <label for="employed_position" class="block text-sm font-medium text-gray-700">Position</label>
                            <input type="text" name="employed_position" id="employed_position" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                        </div>
                        <div>
                            <label for="employed_length_of_service" class="block text-sm font-medium text-gray-700">Length of Service</label>
                            <input type="text" name="employed_length_of_service" id="employed_length_of_service" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                        </div>
                    </div>
                    <div class="mb-2">
                        <label for="employed_company_name" class="block text-sm font-medium text-gray-700">Name of Company/Office</label>
                        <input type="text" name="employed_company_name" id="employed_company_name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                    </div>
                    <div class="mb-2">
                        <label for="employed_company_address" class="block text-sm font-medium text-gray-700">Address of Company/Office</label>
                        <input type="text" name="employed_company_address" id="employed_company_address" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-2">
                        <div>
                            <label for="employed_email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="employed_email" id="employed_email" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                        </div>
                        <div>
                            <label for="employed_website" class="block text-sm font-medium text-gray-700">Website</label>
                            <input type="url" name="employed_website" id="employed_website" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                        </div>
                        <div>
                            <label for="employed_telephone" class="block text-sm font-medium text-gray-700">Telephone No.</label>
                            <input type="text" name="employed_telephone" id="employed_telephone" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                        </div>
                    </div>
                    <div>
                        <label for="employed_fax" class="block text-sm font-medium text-gray-700">Fax No.</label>
                        <input type="text" name="employed_fax" id="employed_fax" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                    </div>
                    <p class="text-sm text-gray-600 mt-2">*Once accepted in the scholarship program, the scholar must obtain permission to go on a Leave of Absence (LOA) from his/her employer and become a full-time student. The scholar must submit a letter from his/her employer approving the LOA.</p>
                </div>

                <div id="self_employed_fields" class="mb-6 border p-4 rounded-md hidden">
                    <p class="font-semibold mb-2">a.2 For those who are self-employed</p>
                    <div class="mb-2">
                        <label for="self_employed_business_name" class="block text-sm font-medium text-gray-700">Business Name</label>
                        <input type="text" name="self_employed_business_name" id="self_employed_business_name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                    </div>
                    <div class="mb-2">
                        <label for="self_employed_address" class="block text-sm font-medium text-gray-700">Address</label>
                        <input type="text" name="self_employed_address" id="self_employed_address" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-2">
                        <div>
                            <label for="self_employed_email_website" class="block text-sm font-medium text-gray-700">Email/Website</label>
                            <input type="text" name="self_employed_email_website" id="self_employed_email_website" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                        </div>
                        <div>
                            <label for="self_employed_telephone" class="block text-sm font-medium text-gray-700">Telephone No.</label>
                            <input type="text" name="self_employed_telephone" id="self_employed_telephone" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="self_employed_fax" class="block text-sm font-medium text-gray-700">Fax No.</label>
                            <input type="text" name="self_employed_fax" id="self_employed_fax" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                        </div>
                        <div>
                            <label for="self_employed_type_of_business" class="block text-sm font-medium text-gray-700">Type of Business</label>
                            <input type="text" name="self_employed_type_of_business" id="self_employed_type_of_business" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                        </div>
                    </div>
                    <div>
                        <label for="self_employed_years_of_operation" class="block text-sm font-medium text-gray-700">Years of Operation</label>
                        <input type="text" name="self_employed_years_of_operation" id="self_employed_years_of_operation" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                    </div>
                </div>

                <div class="mb-4">
                    <label for="research_plans" class="block text-sm font-medium text-gray-700">b. RESEARCH PLANS (Please use Form A)</label>
                </div>

                <div class="mb-6">
                    <label for="career_plans" class="block text-sm font-medium text-gray-700">c. CAREER PLANS (Please use Form B)</label>
                </div>

                <!-- V. RESEARCH AND DEVELOPMENT INVOLVEMENT (last five years) -->
                <h4 class="text-lg font-semibold mb-3">V. RESEARCH AND DEVELOPMENT INVOLVEMENT (last five years)</h4>
                <p class="text-sm text-gray-600 mb-2">Use additional sheet if necessary.</p>
                <div id="rd_involvement_container" class="space-y-4 mb-6">
                    <div class="rd_involvement_item grid grid-cols-1 md:grid-cols-4 gap-4 border p-3 rounded-md">
                        <div>
                            <label for="rd_field_title_1" class="block text-sm font-medium text-gray-700">FIELD AND TITLE OF RESEARCH</label>
                            <input type="text" name="rd_involvement[0][field_title]" id="rd_field_title_1" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                        </div>
                        <div>
                            <label for="rd_location_duration_1" class="block text-sm font-medium text-gray-700">LOCATION/DURATION</label>
                            <input type="text" name="rd_involvement[0][location_duration]" id="rd_location_duration_1" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                        </div>
                        <div>
                            <label for="rd_fund_source_1" class="block text-sm font-medium text-gray-700">FUND SOURCE</label>
                            <input type="text" name="rd_involvement[0][fund_source]" id="rd_fund_source_1" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                        </div>
                        <div>
                            <label for="rd_nature_of_involvement_1" class="block text-sm font-medium text-gray-700">NATURE OF INVOLVEMENT</label>
                            <input type="text" name="rd_involvement[0][nature_of_involvement]" id="rd_nature_of_involvement_1" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                        </div>
                    </div>
                </div>
                <button type="button" id="add_rd_involvement" class="bg-green-500 text-white px-3 py-1 rounded text-sm hover:bg-green-600">Add More R&D Involvement</button>

                <!-- VI. PUBLICATIONS (last five years) -->
                <h4 class="text-lg font-semibold mb-3 mt-6">VI. PUBLICATIONS (last five years)</h4>
                <p class="text-sm text-gray-600 mb-2">Use additional sheet if necessary.</p>
                <div id="publications_container" class="space-y-4 mb-6">
                    <div class="publication_item grid grid-cols-1 md:grid-cols-3 gap-4 border p-3 rounded-md">
                        <div>
                            <label for="pub_title_1" class="block text-sm font-medium text-gray-700">TITLE OF ARTICLE</label>
                            <input type="text" name="publications[0][title]" id="pub_title_1" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                        </div>
                        <div>
                            <label for="pub_name_year_1" class="block text-sm font-medium text-gray-700">NAME /YEAR OF PUBLICATION</label>
                            <input type="text" name="publications[0][name_year]" id="pub_name_year_1" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                        </div>
                        <div>
                            <label for="pub_nature_of_involvement_1" class="block text-sm font-medium text-gray-700">NATURE OF INVOLVEMENT</label>
                            <input type="text" name="publications[0][nature_of_involvement]" id="pub_nature_of_involvement_1" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                        </div>
                    </div>
                </div>
                <button type="button" id="add_publication" class="bg-green-500 text-white px-3 py-1 rounded text-sm hover:bg-green-600">Add More Publication</button>

                <!-- VII. AWARDS RECEIVED -->
                <h4 class="text-lg font-semibold mb-3 mt-6">VII. AWARDS RECEIVED</h4>
                <div id="awards_container" class="space-y-4 mb-6">
                    <div class="award_item grid grid-cols-1 md:grid-cols-3 gap-4 border p-3 rounded-md">
                        <div>
                            <label for="award_title_1" class="block text-sm font-medium text-gray-700">TITLE OF AWARD</label>
                            <input type="text" name="awards[0][title]" id="award_title_1" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                        </div>
                        <div>
                            <label for="award_giving_body_1" class="block text-sm font-medium text-gray-700">AWARD GIVING BODY</label>
                            <input type="text" name="awards[0][giving_body]" id="award_giving_body_1" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                        </div>
                        <div>
                            <label for="award_year_1" class="block text-sm font-medium text-gray-700">YEAR OF AWARD</label>
                            <input type="text" name="awards[0][year]" id="award_year_1" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                        </div>
                    </div>
                </div>
                <button type="button" id="add_award" class="bg-green-500 text-white px-3 py-1 rounded text-sm hover:bg-green-600">Add More Award</button>

                <!-- VIII. TRUTHFULNESS OF DATA AND DATA PRIVACY -->
                <h4 class="text-lg font-semibold mb-3 mt-6">VIII. TRUTHFULNESS OF DATA AND DATA PRIVACY</h4>
                <div class="mb-6 text-sm text-gray-700 border p-4 rounded-md bg-gray-50">
                    <p class="mb-2">I hereby certify that all information given above are true and correct to the best of my knowledge. Any misinformation or withholding of information will automatically disqualify me from the program, Project Science and Technology Regional Alliance of Universities for National Development (STRAND). I am willing to refund all the financial benefits received plus appropriate interest if such misinformation is discovered.</p>
                    <p class="mb-2">Moreover, I hereby authorize the Science Education Institute of the Department of Science and Technology (SEI-DOST) to collect, record, organize, update or modify, retrieve, consult, use, consolidate, block, erase or destruct my personal data that I have provided in relation to my application to this scholarship. I hereby affirm my right to be informed, object to processing, access and rectify, suspend or withdraw my personal data, and be indemnified in case of damages pursuant to the provisions of the Republic Act No. 10173 of the Philippines, Data Privacy Act of 2012 and its corresponding Implementing Rules and Regulations.</p>

                    <div class="mt-4">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="terms_and_conditions_agreed" value="1" required class="form-checkbox h-5 w-5 text-blue-600">
                            <span class="ml-2 text-sm font-medium text-gray-900">I understand and agree to the terms and conditions stated above.</span>
                        </label>
                    </div>

                    <div class="mt-4">
                        <label for="applicant_signature" class="block text-sm font-medium text-gray-700">Printed Name and Signature of Applicant</label>
                        <input type="text" name="applicant_signature" id="applicant_signature" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm" required>
                    </div>
                    <div class="mt-2">
                        <label for="declaration_date" class="block text-sm font-medium text-gray-700">Date:</label>
                        <input type="date" name="declaration_date" id="declaration_date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm" required>
                    </div>
                </div>

                <!-- Requirements Upload Section -->
                <h4 class="text-lg font-semibold mb-3 mt-6">Upload Required Documents (PDF only)</h4>
                <div class="mb-6 space-y-4 border p-4 rounded-md bg-gray-50">
                    <div>
                        <label for="birth_certificate_pdf" class="block text-sm font-medium text-gray-700">Birth Certificate (Photocopy)</label>
                        <input type="file" name="birth_certificate_pdf" id="birth_certificate_pdf" accept="application/pdf" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>
                    <div>
                        <label for="transcript_of_record_pdf" class="block text-sm font-medium text-gray-700">Certified True Copy of the Official Transcript of Record</label>
                        <input type="file" name="transcript_of_record_pdf" id="transcript_of_record_pdf" accept="application/pdf" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>
                    <div>
                        <label for="endorsement_1_pdf" class="block text-sm font-medium text-gray-700">Endorsement 1 (former professor in college for MS applicant/former professor in the MS program for PhD applicant)</label>
                        <input type="file" name="endorsement_1_pdf" id="endorsement_1_pdf" accept="application/pdf" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>
                    <div>
                        <label for="endorsement_2_pdf" class="block text-sm font-medium text-gray-700">Endorsement 2 (former professor in college for MS applicant/former professor in the MS program for PhD applicant)</label>
                        <input type="file" name="endorsement_2_pdf" id="endorsement_2_pdf" accept="application/pdf" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>

                    <p class="font-semibold mt-4">If Employed:</p>
                    <div>
                        <label for="recommendation_head_agency_pdf" class="block text-sm font-medium text-gray-700">Recommendation from Head of Agency</label>
                        <input type="file" name="recommendation_head_agency_pdf" id="recommendation_head_agency_pdf" accept="application/pdf" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>
                    <div>
                        <label for="form_2a_pdf" class="block text-sm font-medium text-gray-700">Form 2A – Certificate of Employment and Permit to Study</label>
                        <input type="file" name="form_2a_pdf" id="form_2a_pdf" accept="application/pdf" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>
                    <div>
                        <label for="form_2b_pdf" class="block text-sm font-medium text-gray-700">Form 2B – Certificate of DepEd Employment and Permit to Study (for DepEd employees only)</label>
                        <input type="file" name="form_2b_pdf" id="form_2b_pdf" accept="application/pdf" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>

                    <div>
                        <label for="form_a_research_plans_pdf" class="block text-sm font-medium text-gray-700">Form A – Research Plans</label>
                        <input type="file" name="form_a_research_plans_pdf" id="form_a_research_plans_pdf" accept="application/pdf" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>
                    <div>
                        <label for="form_b_career_plans_pdf" class="block text-sm font-medium text-gray-700">Form B – Career Plans</label>
                        <input type="file" name="form_b_career_plans_pdf" id="form_b_career_plans_pdf" accept="application/pdf" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>
                    <div>
                        <label for="form_c_health_status_pdf" class="block text-sm font-medium text-gray-700">Form C – Certification of Health Status</label>
                        <input type="file" name="form_c_health_status_pdf" id="form_c_health_status_pdf" accept="application/pdf" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>
                    <div>
                        <label for="nbi_clearance_pdf" class="block text-sm font-medium text-gray-700">Valid NBI Clearance</label>
                        <input type="file" name="nbi_clearance_pdf" id="nbi_clearance_pdf" accept="application/pdf" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>
                    <div>
                        <label for="letter_of_admission_pdf" class="block text-sm font-medium text-gray-700">Letter of Admission with Regular status from the Program Head of the accepting institution; should include the evaluation sheet</label>
                        <input type="file" name="letter_of_admission_pdf" id="letter_of_admission_pdf" accept="application/pdf" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>
                    <div>
                        <label for="approved_program_of_study_pdf" class="block text-sm font-medium text-gray-700">Approved Program of Study</label>
                        <input type="file" name="approved_program_of_study_pdf" id="approved_program_of_study_pdf" accept="application/pdf" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>

                    <p class="font-semibold mt-4">Additional Requirements for Lateral Applicants:</p>
                    <div>
                        <label for="lateral_certification_pdf" class="block text-sm font-medium text-gray-700">Certification from the university indicating the following: number of graduate units required in the program, number of graduate units already earned with corresponding grades</label>
                        <input type="file" name="lateral_certification_pdf" id="lateral_certification_pdf" accept="application/pdf" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>
                </div>


                <div class="mt-6">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Submit Application
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Dynamic fields for R&D Involvement
            let rdInvolvementCount = 1;
            document.getElementById('add_rd_involvement').addEventListener('click', function() {
                rdInvolvementCount++;
                const container = document.getElementById('rd_involvement_container');
                const newItem = document.createElement('div');
                newItem.classList.add('rd_involvement_item', 'grid', 'grid-cols-1', 'md:grid-cols-4', 'gap-4', 'border', 'p-3', 'rounded-md');
                newItem.innerHTML = `
                    <div>
                        <label for="rd_field_title_${rdInvolvementCount}" class="block text-sm font-medium text-gray-700">FIELD AND TITLE OF RESEARCH</label>
                        <input type="text" name="rd_involvement[${rdInvolvementCount - 1}][field_title]" id="rd_field_title_${rdInvolvementCount}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                    </div>
                    <div>
                        <label for="rd_location_duration_${rdInvolvementCount}" class="block text-sm font-medium text-gray-700">LOCATION/DURATION</label>
                        <input type="text" name="rd_involvement[${rdInvolvementCount - 1}][location_duration]" id="rd_location_duration_${rdInvolvementCount}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                    </div>
                    <div>
                        <label for="rd_fund_source_${rdInvolvementCount}" class="block text-sm font-medium text-gray-700">FUND SOURCE</label>
                        <input type="text" name="rd_involvement[${rdInvolvementCount - 1}][fund_source]" id="rd_fund_source_${rdInvolvementCount}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                    </div>
                    <div>
                        <label for="rd_nature_of_involvement_${rdInvolvementCount}" class="block text-sm font-medium text-gray-700">NATURE OF INVOLVEMENT</label>
                        <input type="text" name="rd_involvement[${rdInvolvementCount - 1}][nature_of_involvement]" id="rd_nature_of_involvement_${rdInvolvementCount}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                    </div>
                `;
                container.appendChild(newItem);
            });

            // Dynamic fields for Publications
            let publicationCount = 1;
            document.getElementById('add_publication').addEventListener('click', function() {
                publicationCount++;
                const container = document.getElementById('publications_container');
                const newItem = document.createElement('div');
                newItem.classList.add('publication_item', 'grid', 'grid-cols-1', 'md:grid-cols-3', 'gap-4', 'border', 'p-3', 'rounded-md');
                newItem.innerHTML = `
                    <div>
                        <label for="pub_title_${publicationCount}" class="block text-sm font-medium text-gray-700">TITLE OF ARTICLE</label>
                        <input type="text" name="publications[${publicationCount - 1}][title]" id="pub_title_${publicationCount}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                    </div>
                    <div>
                        <label for="pub_name_year_${publicationCount}" class="block text-sm font-medium text-gray-700">NAME /YEAR OF PUBLICATION</label>
                        <input type="text" name="publications[${publicationCount - 1}][name_year]" id="pub_name_year_${publicationCount}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                    </div>
                    <div>
                        <label for="pub_nature_of_involvement_${publicationCount}" class="block text-sm font-medium text-gray-700">NATURE OF INVOLVEMENT</label>
                        <input type="text" name="publications[${publicationCount - 1}][nature_of_involvement]" id="pub_nature_of_involvement_${publicationCount}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                    </div>
                `;
                container.appendChild(newItem);
            });

            // Dynamic fields for Awards
            let awardCount = 1;
            document.getElementById('add_award').addEventListener('click', function() {
                awardCount++;
                const container = document.getElementById('awards_container');
                const newItem = document.createElement('div');
                newItem.classList.add('award_item', 'grid', 'grid-cols-1', 'md:grid-cols-3', 'gap-4', 'border', 'p-3', 'rounded-md');
                newItem.innerHTML = `
                    <div>
                        <label for="award_title_${awardCount}" class="block text-sm font-medium text-gray-700">TITLE OF AWARD</label>
                        <input type="text" name="awards[${awardCount - 1}][title]" id="award_title_${awardCount}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                    </div>
                    <div>
                        <label for="award_giving_body_${awardCount}" class="block text-sm font-medium text-gray-700">AWARD GIVING BODY</label>
                        <input type="text" name="awards[${awardCount - 1}][giving_body]" id="award_giving_body_${awardCount}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                    </div>
                    <div>
                        <label for="award_year_${awardCount}" class="block text-sm font-medium text-gray-700">YEAR OF AWARD</label>
                        <input type="text" name="awards[${awardCount - 1}][year]" id="award_year_${awardCount}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                    </div>
                `;
                container.appendChild(newItem);
            });

            // Toggle employment status fields
            const employmentStatusRadios = document.querySelectorAll('input[name="employment_status"]');
            const employedFields = document.getElementById('employed_fields');
            const selfEmployedFields = document.getElementById('self_employed_fields');

            employmentStatusRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    employedFields.classList.add('hidden');
                    selfEmployedFields.classList.add('hidden');

                    if (this.value === 'Permanent' || this.value === 'Contractual' || this.value === 'Probationary') {
                        employedFields.classList.remove('hidden');
                    } else if (this.value === 'Self-employed') {
                        selfEmployedFields.classList.remove('hidden');
                    }
                });
            });
        });
    </script>
</x-app-layout>
