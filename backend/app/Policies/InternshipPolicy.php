<?php

namespace App\Policies;

use App\Enums\Role;
use App\Models\Internship;
use App\Models\User;

class InternshipPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Internship $internship): bool
    {
        if ($internship->status === 'published') {
            return true;
        }

        return $this->manage($user, $internship);
    }

    public function create(User $user): bool
    {
        return $user->hasRole(Role::Company);
    }

    public function update(User $user, Internship $internship): bool
    {
        return $this->manage($user, $internship);
    }

    public function delete(User $user, Internship $internship): bool
    {
        return $this->manage($user, $internship);
    }

    protected function manage(User $user, Internship $internship): bool
    {
        if ($user->hasRole(Role::Admin)) {
            return true;
        }

        if (! $user->hasRole(Role::Company) || ! $user->company) {
            return false;
        }

        return (int) $internship->company_id === (int) $user->company->id;
    }
}
