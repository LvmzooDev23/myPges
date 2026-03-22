<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\InternshipReportResource;
use App\Http\Resources\StudentResource;
use Illuminate\Http\JsonResponse;

class SupervisorController extends Controller
{
    public function students(): JsonResponse
    {
        $supervisor = auth('api')->user()->supervisor;
        $students = $supervisor->students()->with('user')->get();

        return response()->json(StudentResource::collection($students));
    }

    public function pendingReports(): JsonResponse
    {
        $supervisor = auth('api')->user()->supervisor;
        $reports = $supervisor->internshipReports()
            ->where('status', 'submitted')
            ->with(['student.user', 'application.internship', 'supervisor'])
            ->get();

        return response()->json(InternshipReportResource::collection($reports));
    }
}
