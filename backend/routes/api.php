<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\ApplicationController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Company\InternshipManageController;
use App\Http\Controllers\Api\CompanyProfileController;
use App\Http\Controllers\Api\EvaluationController;
use App\Http\Controllers\Api\InternshipController;
use App\Http\Controllers\Api\InternshipReportController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\SupervisorController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('password/forgot', [AuthController::class, 'forgotPassword']);
    Route::post('password/reset', [AuthController::class, 'resetPassword']);
});

Route::get('internships', [InternshipController::class, 'index']);
Route::get('internships/{id}', [InternshipController::class, 'show']);

Route::middleware('auth:api')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::get('me', [AuthController::class, 'me']);
    });

    Route::get('notifications', [NotificationController::class, 'index']);
    Route::patch('notifications/{id}/read', [NotificationController::class, 'markRead']);

    Route::middleware('role:student')->prefix('student')->group(function () {
        Route::get('profile', [StudentController::class, 'profile']);
        Route::put('profile', [StudentController::class, 'update']);
        Route::post('cv', [StudentController::class, 'uploadCv']);
        Route::get('cv/download', [StudentController::class, 'downloadCv']);
        Route::get('applications', [ApplicationController::class, 'studentIndex']);
        Route::post('internships/{internship}/apply', [ApplicationController::class, 'apply']);
        Route::post('applications/{application}/report', [InternshipReportController::class, 'store']);
    });

    Route::middleware('role:company')->prefix('company')->group(function () {
        Route::get('profile', [CompanyProfileController::class, 'show']);
        Route::put('profile', [CompanyProfileController::class, 'update']);

        Route::middleware('company.approved')->group(function () {
            Route::get('internships', [InternshipManageController::class, 'index']);
            Route::post('internships', [InternshipManageController::class, 'store']);
            Route::put('internships/{internship}', [InternshipManageController::class, 'update']);
            Route::delete('internships/{internship}', [InternshipManageController::class, 'destroy']);
            Route::get('internships/{internship}/applications', [ApplicationController::class, 'companyInternshipApplications']);
        });

        Route::patch('applications/{application}', [ApplicationController::class, 'updateStatus']);
    });

    Route::middleware('role:supervisor')->prefix('supervisor')->group(function () {
        Route::get('students', [SupervisorController::class, 'students']);
        Route::get('reports/pending', [SupervisorController::class, 'pendingReports']);
        Route::patch('reports/{report}/validate', [InternshipReportController::class, 'validateReport']);
        Route::post('evaluations', [EvaluationController::class, 'store']);
        Route::get('evaluations/{evaluation}', [EvaluationController::class, 'show']);
    });

    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('dashboard', [AdminController::class, 'dashboard']);
        Route::get('students', [AdminController::class, 'students']);
        Route::get('companies', [AdminController::class, 'companies']);
        Route::get('internships', [AdminController::class, 'internships']);
        Route::patch('companies/{company}/approval', [AdminController::class, 'approveCompany']);
    });

    Route::get('reports/{report}/download', [InternshipReportController::class, 'download']);
});
