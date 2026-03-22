<?php

namespace App\Policies;

use App\Enums\Role;
use App\Models\Student;
use App\Models\User;

class StudentPolicy
{
    public function update(User $user, Student $student): bool
    {
        if ($user->hasRole(Role::Admin)) {
            return true;
        }

        return $user->hasRole(Role::Student) && $user->student && (int) $user->student->id === (int) $student->id;
    }
}
