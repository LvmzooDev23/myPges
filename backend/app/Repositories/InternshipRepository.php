<?php

namespace App\Repositories;

use App\Models\Internship;
use App\Repositories\Contracts\InternshipRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class InternshipRepository implements InternshipRepositoryInterface
{
    public function paginatePublished(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $q = Internship::query()->with('company')->where('status', 'published');

        if (! empty($filters['q'])) {
            $term = '%'.$filters['q'].'%';
            $q->where(function ($w) use ($term) {
                $w->where('title', 'like', $term)->orWhere('description', 'like', $term);
            });
        }
        if (! empty($filters['location'])) {
            $q->where('location', 'like', '%'.$filters['location'].'%');
        }
        if (! empty($filters['type'])) {
            $q->where('type', $filters['type']);
        }
        if (! empty($filters['from'])) {
            $q->whereDate('start_date', '>=', $filters['from']);
        }
        if (! empty($filters['to'])) {
            $q->whereDate('start_date', '<=', $filters['to']);
        }

        return $q->orderByDesc('created_at')->paginate($perPage);
    }

    public function find(int $id): ?Internship
    {
        return Internship::with('company')->find($id);
    }

    public function create(array $data): Internship
    {
        return Internship::create($data);
    }

    public function update(Internship $internship, array $data): Internship
    {
        $internship->update($data);

        return $internship->fresh();
    }

    public function delete(Internship $internship): bool
    {
        return (bool) $internship->delete();
    }

    public function forCompany(int $companyId): Collection
    {
        return Internship::where('company_id', $companyId)->orderByDesc('created_at')->get();
    }
}
