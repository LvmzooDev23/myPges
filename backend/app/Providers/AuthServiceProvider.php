<?php

namespace App\Providers;

use App\Models\Application;
use App\Models\Company;
use App\Models\Evaluation;
use App\Models\Internship;
use App\Models\InternshipReport;
use App\Models\Student;
use App\Policies\ApplicationPolicy;
use App\Policies\CompanyPolicy;
use App\Policies\EvaluationPolicy;
use App\Policies\InternshipPolicy;
use App\Policies\InternshipReportPolicy;
use App\Policies\StudentPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Internship::class => InternshipPolicy::class,
        Application::class => ApplicationPolicy::class,
        Company::class => CompanyPolicy::class,
        Student::class => StudentPolicy::class,
        InternshipReport::class => InternshipReportPolicy::class,
        Evaluation::class => EvaluationPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
