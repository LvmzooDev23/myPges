<?php

namespace App\Repositories\Contracts;

use App\Models\Internship;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface InternshipRepositoryInterface
{
    public function paginatePublished(array $filters, int $perPage = 15): LengthAwarePaginator;

    public function find(int $id): ?Internship;

    public function create(array $data): Internship;

    public function update(Internship $internship, array $data): Internship;

    public function delete(Internship $internship): bool;

    /** @return Collection<int, Internship> */
    public function forCompany(int $companyId): Collection;
}
