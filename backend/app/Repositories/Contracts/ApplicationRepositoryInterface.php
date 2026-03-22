<?php

namespace App\Repositories\Contracts;

use App\Models\Application;
use Illuminate\Database\Eloquent\Collection;

interface ApplicationRepositoryInterface
{
    public function find(int $id): ?Application;

    public function findForStudent(int $applicationId, int $studentId): ?Application;

    public function findForInternship(int $applicationId, int $internshipId): ?Application;

    /** @return Collection<int, Application> */
    public function forStudent(int $studentId): Collection;

    /** @return Collection<int, Application> */
    public function forInternship(int $internshipId): Collection;

    public function create(array $data): Application;

    public function update(Application $application, array $data): Application;
}
