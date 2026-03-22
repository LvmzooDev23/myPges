<?php

namespace App\Policies;

use App\Enums\Role;
use App\Models\Evaluation;
use App\Models\User;

class EvaluationPolicy
{
    public function create(User $user): bool
    {
        return $user->hasRole(Role::Supervisor);
    }

    public function view(User $user, Evaluation $evaluation): bool
    {
        if ($user->hasRole(Role::Admin)) {
            return true;
        }

        if ($user->hasRole(Role::Student) && $user->student && (int) $evaluation->student_id === (int) $user->student->id) {
            return true;
        }

        if ($user->hasRole(Role::Supervisor) && $user->supervisor && (int) $evaluation->supervisor_id === (int) $user->supervisor->id) {
            return true;
        }

        return false;
    }
}
