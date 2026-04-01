<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApplicationResource;
use App\Http\Resources\InternshipResource;
use App\Models\Application;
use App\Models\Internship;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class StudentDashboardController extends Controller
{
    public function dashboardStats(): JsonResponse
    {
        $student = Auth::user()->student;
        $this->authorize('viewDashboardStats', $student);
        
        $applications = Application::where('student_id', $student->id)->get();
        
        $stats = [
            'number_of_applications' => $applications->count(),
            'accepted_applications' => $applications->where('status', 'accepted')->count(),
            'pending_applications' => $applications->where('status', 'pending')->count(),
            'rejected_applications' => $applications->where('status', 'rejected')->count(),
        ];

        return response()->json($stats);
    }

    public function recommendedInternships(): JsonResponse
    {
        $student = Auth::user()->student;
        
        if (!$student->skills && !$student->degree) {
            return response()->json([]);
        }

        $studentSkills = $student->skills ? explode(',', $student->skills) : [];
        $studentDegree = $student->degree;

        $internships = Internship::with('company')
            ->where('status', 'published')
            ->where('deadline', '>=', now())
            ->get();

        $recommended = $internships->map(function ($internship) use ($studentSkills, $studentDegree) {
            $score = 0;
            $internshipSkills = $internship->required_skills ? explode(',', $internship->required_skills) : [];

            // Skills matching (70% weight)
            if (!empty($studentSkills) && !empty($internshipSkills)) {
                $matchingSkills = array_intersect($studentSkills, $internshipSkills);
                $score += (count($matchingSkills) / max(count($internshipSkills), 1)) * 70;
            }

            // Degree matching (30% weight)
            if ($studentDegree && $internship->requirements) {
                $requirements = strtolower($internship->requirements);
                $degree = strtolower($studentDegree);
                if (strpos($requirements, $degree) !== false) {
                    $score += 30;
                }
            }

            return [
                'internship' => new InternshipResource($internship),
                'relevance_score' => round($score, 2),
            ];
        })
        ->filter(function ($item) {
            return $item['relevance_score'] > 20; // Only show relevant internships
        })
        ->sortByDesc('relevance_score')
        ->take(10)
        ->values();

        return response()->json($recommended);
    }

    public function applicationTracking(): JsonResponse
    {
        $student = Auth::user()->student;
        $this->authorize('viewApplications', $student);
        
        $applications = Application::with(['internship.company'])
            ->where('student_id', $student->id)
            ->orderBy('applied_at', 'desc')
            ->get();

        return response()->json(ApplicationResource::collection($applications));
    }
}
