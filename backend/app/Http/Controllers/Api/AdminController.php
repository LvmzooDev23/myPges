<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ApproveCompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Http\Resources\InternshipResource;
use App\Http\Resources\StudentResource;
use App\Models\Company;
use App\Models\Internship;
use App\Models\Student;
use App\Services\AdminAnalyticsService;
use Illuminate\Http\JsonResponse;

class AdminController extends Controller
{
    public function __construct(
        protected AdminAnalyticsService $analytics
    ) {}

    public function dashboard(): JsonResponse
    {
        return response()->json($this->analytics->dashboard());
    }

    public function students(): JsonResponse
    {
        $students = Student::with('user')->orderByDesc('id')->paginate(20);

        return StudentResource::collection($students)->response();
    }

    public function companies(): JsonResponse
    {
        $companies = Company::with('user')->orderByDesc('id')->paginate(20);

        return CompanyResource::collection($companies)->response();
    }

    public function internships(): JsonResponse
    {
        $internships = Internship::with('company')->orderByDesc('id')->paginate(20);

        return InternshipResource::collection($internships)->response();
    }

    public function approveCompany(ApproveCompanyRequest $request, Company $company): JsonResponse
    {
        $this->authorize('approve', $company);

        $company->update([
            'approval_status' => $request->input('approval_status'),
            'approved_at' => $request->input('approval_status') === 'approved' ? now() : null,
        ]);

        return response()->json(new CompanyResource($company->fresh()->load('user')));
    }
}
