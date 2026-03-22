<?php

namespace App\Policies;

use App\Enums\Role;
use App\Models\Company;
use App\Models\User;

class CompanyPolicy
{
    public function update(User $user, Company $company): bool
    {
        if ($user->hasRole(Role::Admin)) {
            return true;
        }

        return $user->hasRole(Role::Company) && $user->company && (int) $user->company->id === (int) $company->id;
    }

    public function approve(User $user, Company $company): bool
    {
        return $user->hasRole(Role::Admin);
    }
}
