<?php

namespace App\Services;

use App\Models\Application;
use App\Models\Company;
use App\Models\Internship;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class AdminAnalyticsService
{
    /**
     * @return array<string, mixed>
     */
    public function dashboard(): array
    {
        $totalStudents = Student::query()->count();
        $totalCompanies = Company::query()->count();
        $totalInternships = Internship::query()->count();
        $acceptedApplications = Application::query()->where('status', 'accepted')->count();

        $applicationsPerInternship = Internship::query()
            ->leftJoin('applications', 'applications.internship_id', '=', 'internships.id')
            ->select('internships.id', 'internships.title', DB::raw('COUNT(applications.id) as applications_count'))
            ->groupBy('internships.id', 'internships.title')
            ->orderByDesc('applications_count')
            ->limit(20)
            ->get();

        return [
            'total_students' => $totalStudents,
            'total_companies' => $totalCompanies,
            'total_internships' => $totalInternships,
            'accepted_applications' => $acceptedApplications,
            'applications_per_internship' => $applicationsPerInternship,
        ];
    }
}
