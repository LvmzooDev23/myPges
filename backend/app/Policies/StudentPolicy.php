<?php

namespace App\Policies;

use App\Enums\Role;
use App\Models\Student;
use App\Models\User;

class StudentPolicy
{
    public function view(User $user, Student $student): bool
    {
        if ($user->hasRole(Role::Admin)) {
            return true;
        }

        if ($user->hasRole(Role::Supervisor)) {
            return $user->supervisor && (int) $user->supervisor->id === (int) $student->supervisor_id;
        }

        return $user->hasRole(Role::Student) && $user->student && (int) $user->student->id === (int) $student->id;
    }

    public function update(User $user, Student $student): bool
    {
        if ($user->hasRole(Role::Admin)) {
            return true;
        }

        return $user->hasRole(Role::Student) && $user->student && (int) $user->student->id === (int) $student->id;
    }

    public function viewCv(User $user, Student $student): bool
    {
        if ($user->hasRole(Role::Admin)) {
            return true;
        }

        if ($user->hasRole(Role::Company)) {
            // Companies can view CV only for students who have applied to their internships
            return $student->applications()
                ->whereHas('internship', function ($query) use ($user) {
                    $query->where('company_id', $user->company->id);
                })
                ->exists();
        }

        return $user->hasRole(Role::Student) && $user->student && (int) $user->student->id === (int) $student->id;
    }

    public function downloadCv(User $user, Student $student): bool
    {
        return $this->viewCv($user, $student);
    }

    public function uploadCv(User $user, Student $student): bool
    {
        return $this->update($user, $student);
    }

    public function viewApplications(User $user, Student $student): bool
    {
        if ($user->hasRole(Role::Admin)) {
            return true;
        }

        return $user->hasRole(Role::Student) && $user->student && (int) $user->student->id === (int) $student->id;
    }

    public function viewDashboardStats(User $user, Student $student): bool
    {
        return $this->viewApplications($user, $student);
    }
}
