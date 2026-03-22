<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\Company;
use App\Models\Internship;
use App\Models\Student;
use App\Models\Supervisor;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->create([
            'name' => 'Administrateur',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => Role::Admin,
        ]);

        $studentUser = User::query()->create([
            'name' => 'Étudiant Démo',
            'email' => 'student@example.com',
            'password' => Hash::make('password'),
            'role' => Role::Student,
        ]);
        Student::query()->create([
            'user_id' => $studentUser->id,
            'student_number' => 'STU001',
        ]);

        $companyUser = User::query()->create([
            'name' => 'Contact Entreprise',
            'email' => 'company@example.com',
            'password' => Hash::make('password'),
            'role' => Role::Company,
        ]);
        $company = Company::query()->create([
            'user_id' => $companyUser->id,
            'name' => 'TechCorp',
            'approval_status' => 'approved',
            'approved_at' => now(),
        ]);

        $supervisorUser = User::query()->create([
            'name' => 'Superviseur Démo',
            'email' => 'supervisor@example.com',
            'password' => Hash::make('password'),
            'role' => Role::Supervisor,
        ]);
        Supervisor::query()->create([
            'user_id' => $supervisorUser->id,
            'department' => 'Informatique',
            'title' => 'Professeur',
        ]);

        Internship::query()->create([
            'company_id' => $company->id,
            'title' => 'Stage développement web',
            'description' => 'Développement d\'une plateforme Laravel + Vue.',
            'location' => 'Paris',
            'type' => 'hybrid',
            'start_date' => now()->addMonth(),
            'end_date' => now()->addMonths(5),
            'slots' => 3,
            'status' => 'published',
            'requirements' => 'PHP, JavaScript',
        ]);
    }
}
