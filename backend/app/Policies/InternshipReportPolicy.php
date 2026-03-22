<?php

namespace App\Policies;

use App\Enums\Role;
use App\Models\InternshipReport;
use App\Models\User;

class InternshipReportPolicy
{
    public function validateReport(User $user, InternshipReport $report): bool
    {
        if ($user->hasRole(Role::Admin)) {
            return true;
        }

        if ($user->hasRole(Role::Supervisor) && $user->supervisor) {
            return (int) $report->supervisor_id === (int) $user->supervisor->id;
        }

        return false;
    }

    public function view(User $user, InternshipReport $report): bool
    {
        if ($user->hasRole(Role::Admin)) {
            return true;
        }

        if ($user->hasRole(Role::Student) && $user->student && (int) $report->student_id === (int) $user->student->id) {
            return true;
        }

        if ($user->hasRole(Role::Supervisor) && $user->supervisor && (int) $report->supervisor_id === (int) $user->supervisor->id) {
            return true;
        }

        return false;
    }
}
