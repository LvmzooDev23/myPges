<?php

namespace App\Policies;

use App\Enums\Role;
use App\Models\Application;
use App\Models\User;

class ApplicationPolicy
{
    public function view(User $user, Application $application): bool
    {
        $application->loadMissing('internship');

        if ($user->hasRole(Role::Admin)) {
            return true;
        }

        if ($user->hasRole(Role::Student) && $user->student && (int) $application->student_id === (int) $user->student->id) {
            return true;
        }

        if ($user->hasRole(Role::Company) && $user->company) {
            return (int) $application->internship->company_id === (int) $user->company->id;
        }

        return false;
    }

    public function apply(User $user): bool
    {
        return $user->hasRole(Role::Student);
    }

    public function uploadReport(User $user, Application $application): bool
    {
        if (! $user->hasRole(Role::Student) || ! $user->student) {
            return false;
        }

        return (int) $application->student_id === (int) $user->student->id;
    }

    public function updateStatus(User $user, Application $application): bool
    {
        $application->loadMissing('internship');

        if ($user->hasRole(Role::Admin)) {
            return true;
        }

        if (! $user->hasRole(Role::Company) || ! $user->company) {
            return false;
        }

        return (int) $application->internship->company_id === (int) $user->company->id;
    }
}
