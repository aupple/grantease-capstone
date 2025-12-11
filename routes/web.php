<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ApplicationFormController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\ChedController;
use App\Services\IProgSmsService;


Route::redirect('/', '/login');

Route::middleware(['auth'])->group(function () {
    Route::get('/reports/dost/export', [ReportController::class, 'exportDOSTApplicants'])
        ->name('reports.dost.export');
    
    Route::get('/reports/ched/export', [ReportController::class, 'exportCHEDScholars'])
        ->name('reports.ched.export');
});

// ✅ Handle form submission (POST)
Route::post('/register', [RegisteredUserController::class, 'store'])->name('register');

// ✅ Routes that require BOTH auth AND verified
Route::middleware(['auth', 'verified'])->group(function () {
    
    /**
     * CHED Scholar Routes (role_id = 2, program_type = 'CHED')
     */
    Route::prefix('ched')->name('ched.')->middleware(['role:2:CHED'])->group(function () {
        Route::get('/dashboard', [ChedController::class, 'dashboard'])->name('dashboard');
        
        Route::get('/personal-form', [ChedController::class, 'personalForm'])->name('personal-form');
        Route::post('/personal-form', [ChedController::class, 'storePersonalInformation'])->name('personal-form.store');
        
        Route::get('/personal-information', [ChedController::class, 'personalInformation'])->name('personal-information');
        Route::get('/reports', [ChedController::class, 'reports'])->name('reports');

        // CHED Reports
        Route::get('/report/grade', [ChedController::class, 'generateGradeReport'])->name('report.grade');
        Route::get('/report/enrollment', [ChedController::class, 'generateEnrollmentReport'])->name('report.enrollment');
        Route::get('/report/eligibility', [ChedController::class, 'generateContinuingEligibilityReport'])->name('report.eligibility');
    });

    /**
     * DOST Applicant Routes (role_id = 2, program_type = 'DOST')
     */
    Route::prefix('applicant')->name('applicant.')->middleware(['role:2:DOST'])->group(function () {
        Route::get('/application/{program}', [ApplicationFormController::class, 'create'])->name('application.create');
        Route::post('/applicant/application', [ApplicationFormController::class, 'store'])->name('application.store');
        Route::get('/dashboard', fn() => view('applicant.dashboard'))->name('dashboard');
        Route::get('/my-application', [ApplicationFormController::class, 'viewMyApplication'])->name('application.view');
        Route::get('/application/{id}/edit', [ApplicationFormController::class, 'edit'])->name('application.edit');
        Route::patch('/application/{id}', [ApplicationFormController::class, 'update'])->name('application.update');
        Route::put('/application/update-document/{documentType}', [ApplicationFormController::class, 'updateDocument'])->name('application.update-document');

        // Applicant PDF Routes
        Route::prefix('pdf')->name('pdf.')->group(function () {
            Route::get('/health-certificate', [PdfController::class, 'healthCertificate'])->name('health_certificate');
            Route::get('/score-sheet', [PdfController::class, 'scoreSheet'])->name('score_sheet');
            Route::get('/recommendation-form', [PdfController::class, 'recommendationForm'])->name('recommendation_form');
            Route::get('/research-plans', [PdfController::class, 'researchPlans'])->name('research_plans');
            Route::get('/career-plans', [PdfController::class, 'careerPlans'])->name('career_plans');
            Route::get('/certification-employment', [PdfController::class, 'certificationEmployment'])->name('certification_employment');
            Route::get('/certification-deped', [PdfController::class, 'certificationDepEd'])->name('certification_deped');
            Route::get('/certification-health-status', [PdfController::class, 'certificationHealthStatus'])->name('certification_health_status');
        });
    });

    /**
     * Role-based redirect
     */
    Route::get('/dashboard', function () {
        $user = auth()->user();

        if ($user->role_id == 1) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role_id == 2) {
            if ($user->program_type === 'DOST') {
                return redirect()->route('applicant.dashboard');
            } elseif ($user->program_type === 'CHED') {
                return redirect()->route('ched.dashboard');
            }
        }

        abort(403);
    })->name('dashboard');

    /**
     * Profile Management for verified users (CHED & DOST)
     */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware(['auth'])->group(function () {

    Route::prefix('admin')->name('admin.')->middleware(['role:1'])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/applications', [AdminController::class, 'viewApplications'])->name('applications');
        Route::get('/applications/{id}', [AdminController::class, 'showApplication'])->name('applications.show');
        Route::get('/applications/{id}/print', [AdminController::class, 'printApplicationForm'])->name('applications.print');
        Route::post('/applications/{id}/approve', [AdminController::class, 'approveApplication'])->name('applications.approve');
        Route::post('/applications/{id}/reject', [AdminController::class, 'rejectApplication'])->name('applications.reject');
        Route::post('/applications/{id}/status', [AdminController::class, 'updateStatus'])->name('applications.update-status');
        Route::post('/applications/{applicationId}/verify-document', [AdminController::class, 'verifyDocument'])->name('applications.verify-document');
        Route::post('/applications/{applicationId}/save-remark', [AdminController::class, 'saveDocumentRemark'])->name('applications.save-remark');
        Route::patch('/scholars/{id}/update-status', [AdminController::class, 'updateScholarStatus'])->name('scholars.update-status');
        
        Route::prefix('rejected')->name('rejected.')->group(function () {
            Route::get('/', [AdminController::class, 'rejectedApplications'])->name('index');
            Route::get('/{id}', [AdminController::class, 'showRejected'])->name('show');
        });

        Route::prefix('ched-scholars')->name('ched.')->group(function () {
            Route::get('/', [AdminController::class, 'viewChedScholars'])->name('index');
            Route::get('/{id}', [AdminController::class, 'showChedScholar'])->name('show');
            Route::post('/{id}/update-status', [AdminController::class, 'updateChedStatus'])->name('update-status');
        });

        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/pdf', [AdminController::class, 'downloadReportPdf'])->name('reports.pdf');
        Route::post('/reports/export-selected', [ReportController::class, 'exportSelected'])->name('reports.export-selected');
        Route::get('/reports/applicants', [ReportController::class, 'applicants'])->name('reports.applicants');
        Route::post('/reports/applicants/save', [ReportController::class, 'saveApplicants'])->name('reports.applicants.save');
        Route::get('/reports/applicants/print', [ReportController::class, 'printAllApplicants'])->name('reports.applicants.print');
        Route::get('/reports/monitoring', [ReportController::class, 'monitoring'])->name('reports.monitoring');
        Route::post('/reports/monitoring/update-field', [ReportController::class, 'updateMonitoringField'])->name('reports.monitoring.update-field');
        Route::post('/reports/monitoring/save', [ReportController::class, 'saveMonitoring'])->name('reports.monitoring.save');
        Route::post('/reports/applicants/update-field', [ReportController::class, 'updateField'])->name('reports.applicants.update-field');
        Route::get('/reports/monitoring/download', [ReportController::class, 'downloadMonitoring'])->name('reports.monitoring.download');
        Route::get('/reports/monitoring/print', [ReportController::class, 'printMonitoring'])->name('reports.monitoring.print');
        
        Route::get('/reports/ched-monitoring/print-personal', [ChedController::class, 'printPersonalInformation'])->name('reports.ched-monitoring.print-personal');
        Route::post('/reports/ched-monitoring/export-excel', [ChedController::class, 'exportToExcel'])->name('reports.ched-monitoring.export-excel');
        Route::post('/reports/ched-monitoring/add-to-enrollment/{id}', [ChedController::class, 'addToEnrollment'])->name('reports.ched-monitoring.add-to-enrollment');
        Route::post('/reports/ched-monitoring/update-enrollment/{id}', [ChedController::class, 'updateEnrollmentReport'])->name('reports.ched-monitoring.update-enrollment');
        Route::post('/reports/ched-monitoring/update-grade/{id}', [ChedController::class, 'updateGradeReport'])->name('reports.ched-monitoring.update-grade');
        Route::get('/reports/ched-monitoring', [ChedController::class, 'reports'])->name('reports.ched-monitoring');
        Route::get('/reports/monitoring/{id}', [ReportController::class, 'showMonitoring'])->name('reports.monitoring.show');
        Route::get('/reports/monitoring-print-info', [ReportController::class, 'printPersonalInfo'])->name('reports.monitoring-print-info');
        Route::post('/reports/ched-monitoring/add-to-continuing/{id}', [ChedController::class, 'addToContinuing'])->name('reports.ched-monitoring.add-to-continuing');
        Route::post('/reports/ched-monitoring/update-continuing/{id}', [ChedController::class, 'updateContinuingReport'])->name('reports.ched-monitoring.update-continuing');

        Route::get('/scholars', [AdminController::class, 'viewScholars'])->name('scholars');
        Route::get('/scholars/{id}', [AdminController::class, 'showScholar'])->name('scholars.show');

        Route::prefix('monitoring')->name('monitoring.')->group(function () {
            Route::get('/', [App\Http\Controllers\ScholarMonitoringController::class, 'index'])->name('index');
            Route::get('/{scholar}/create', [App\Http\Controllers\ScholarMonitoringController::class, 'create'])->name('create');
            Route::post('/{scholar}', [App\Http\Controllers\ScholarMonitoringController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [App\Http\Controllers\ScholarMonitoringController::class, 'edit'])->name('edit');
            Route::put('/{id}', [App\Http\Controllers\ScholarMonitoringController::class, 'update'])->name('update');
            Route::delete('/{id}', [App\Http\Controllers\ScholarMonitoringController::class, 'destroy'])->name('destroy');
        });

        /**
         * Admin Profile - Separate from regular users
         */
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
});

Route::get('/test-sms', function () {
    $smsService = new IProgSmsService();
    
    $testType = request('type', 'dost');
    
    if ($testType === 'ched') {
        // ✅ Test CHED confirmation
        $chedInfo = \App\Models\ChedInfo::whereNotNull('contact_no')->first();
        
        if (!$chedInfo) {
            return "❌ No CHED scholar found with contact number. <br><br><a href='/test-sms?type=dost'>Test DOST instead</a>";
        }
        
        $applicantName = $chedInfo->first_name . ' ' . $chedInfo->last_name;
        
        $result = $smsService->sendChedStatus(
            $chedInfo->contact_no,
            $applicantName,
            'confirmed'
        );
        
        if ($result['success']) {
            return "✅ CHED SMS Sent Successfully to {$chedInfo->contact_no}!<br><br><strong>Recipient:</strong> {$applicantName}<br><br><strong>Message:</strong><br>" . nl2br($result['sms_content']) . "<br><br><a href='/test-sms?type=dost'>Test DOST</a> | <a href='/test-sms?type=ched-reject'>Test CHED Rejection</a>";
        } else {
            return "❌ CHED SMS Failed<br><br><strong>Error:</strong><br>" . $result['message'] . "<br><br>Check storage/logs/laravel.log for details<br><br><a href='/test-sms?type=dost'>Test DOST instead</a>";
        }
    } elseif ($testType === 'ched-reject') {
        // ✅ Test CHED rejection with default reason
        $chedInfo = \App\Models\ChedInfo::whereNotNull('contact_no')->first();
        
        if (!$chedInfo) {
            return "❌ No CHED scholar found with contact number. <br><br><a href='/test-sms?type=dost'>Test DOST instead</a>";
        }
        
        $applicantName = $chedInfo->first_name . ' ' . $chedInfo->last_name;
        
        // ✅ No custom reason = uses default CHED rejection message
        $result = $smsService->sendChedStatus(
            $chedInfo->contact_no,
            $applicantName,
            'rejected'
        );
        
        if ($result['success']) {
            return "✅ CHED REJECTION SMS Sent Successfully to {$chedInfo->contact_no}!<br><br><strong>Recipient:</strong> {$applicantName}<br><br><strong>Message:</strong><br>" . nl2br($result['sms_content']) . "<br><br><a href='/test-sms?type=dost'>Test DOST</a> | <a href='/test-sms?type=ched'>Test CHED Approval</a>";
        } else {
            return "❌ CHED SMS Failed<br><br><strong>Error:</strong><br>" . $result['message'] . "<br><br>Check storage/logs/laravel.log for details";
        }
    } elseif ($testType === 'dost-approve') {
        // ✅ Test DOST approval
        $application = \App\Models\ApplicationForm::whereNotNull('telephone_nos')->first();
        
        if (!$application) {
            return "❌ No DOST application found with phone number. <br><br><a href='/test-sms?type=ched'>Test CHED instead</a>";
        }
        
        $applicantName = $application->first_name . ' ' . $application->last_name;
        
        $result = $smsService->sendDostStatus(
            $application->telephone_nos,
            $applicantName,
            'approved'
        );
        
        if ($result['success']) {
            return "✅ DOST APPROVAL SMS Sent Successfully to {$application->telephone_nos}!<br><br><strong>Recipient:</strong> {$applicantName}<br><br><strong>Message:</strong><br>" . nl2br($result['sms_content']) . "<br><br><a href='/test-sms'>Test Document Verified</a> | <a href='/test-sms?type=dost-reject'>Test DOST Rejection</a>";
        } else {
            return "❌ DOST SMS Failed<br><br><strong>Error:</strong><br>" . $result['message'];
        }
    } elseif ($testType === 'dost-reject') {
        // ✅ Test DOST rejection with default reason
        $application = \App\Models\ApplicationForm::whereNotNull('telephone_nos')->first();
        
        if (!$application) {
            return "❌ No DOST application found with phone number. <br><br><a href='/test-sms?type=ched'>Test CHED instead</a>";
        }
        
        $applicantName = $application->first_name . ' ' . $application->last_name;
        
        // ✅ No custom reason = uses default DOST rejection message
        $result = $smsService->sendDostStatus(
            $application->telephone_nos,
            $applicantName,
            'rejected'
        );
        
        if ($result['success']) {
            return "✅ DOST REJECTION SMS Sent Successfully to {$application->telephone_nos}!<br><br><strong>Recipient:</strong> {$applicantName}<br><br><strong>Message:</strong><br>" . nl2br($result['sms_content']) . "<br><br><a href='/test-sms'>Test Document Verified</a> | <a href='/test-sms?type=dost-approve'>Test DOST Approval</a>";
        } else {
            return "❌ DOST SMS Failed<br><br><strong>Error:</strong><br>" . $result['message'];
        }
    } else {
        // ✅ Test DOST document verified (default)
        $application = \App\Models\ApplicationForm::whereNotNull('telephone_nos')->first();
        
        if (!$application) {
            return "❌ No DOST application found with phone number. <br><br><a href='/test-sms?type=ched'>Test CHED instead</a>";
        }
        
        $applicantName = $application->first_name . ' ' . $application->last_name;
        
        $result = $smsService->sendDostStatus(
            $application->telephone_nos,
            $applicantName,
            'document_verified'
        );
        
        if ($result['success']) {
            return "✅ DOST SMS Sent Successfully to {$application->telephone_nos}!<br><br><strong>Recipient:</strong> {$applicantName}<br><br><strong>Message:</strong><br>" . nl2br($result['sms_content']) . "<br><br><a href='/test-sms?type=ched'>Test CHED</a> | <a href='/test-sms?type=dost-approve'>Test DOST Approval</a> | <a href='/test-sms?type=dost-reject'>Test DOST Rejection</a>";
        } else {
            return "❌ DOST SMS Failed<br><br><strong>Error:</strong><br>" . $result['message'] . "<br><br>Check storage/logs/laravel.log for details<br><br><a href='/test-sms?type=ched'>Test CHED instead</a>";
        }
    }
});


require __DIR__ . '/auth.php';