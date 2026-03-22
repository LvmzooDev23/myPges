<?php

namespace App\Repositories;

use App\Models\Application;
use App\Repositories\Contracts\ApplicationRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ApplicationRepository implements ApplicationRepositoryInterface
{
    public function find(int $id): ?Application
    {
        return Application::with(['internship.company', 'student.user'])->find($id);
    }

    public function findForStudent(int $applicationId, int $studentId): ?Application
    {
        return Application::where('id', $applicationId)->where('student_id', $studentId)->first();
    }

    public function findForInternship(int $applicationId, int $internshipId): ?Application
    {
        return Application::where('id', $applicationId)->where('internship_id', $internshipId)->first();
    }

    public function forStudent(int $studentId): Collection
    {
        return Application::with(['internship.company'])
            ->where('student_id', $studentId)
            ->orderByDesc('applied_at')
            ->get();
    }

    public function forInternship(int $internshipId): Collection
    {
        return Application::with(['student.user'])
            ->where('internship_id', $internshipId)
            ->orderByDesc('applied_at')
            ->get();
    }

    public function create(array $data): Application
    {
        return Application::create($data);
    }

    public function update(Application $application, array $data): Application
    {
        $application->update($data);

        return $application->fresh();
    }
}
