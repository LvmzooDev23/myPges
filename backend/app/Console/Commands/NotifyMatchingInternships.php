<?php

namespace App\Console\Commands;

use App\Models\Student;
use App\Models\Internship;
use App\Services\NotificationService;
use Illuminate\Console\Command;

class NotifyMatchingInternships extends Command
{
    protected $signature = 'internships:notify-matching';
    protected $description = 'Notify students about new matching internships';

    public function __construct(
        private NotificationService $notifications
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->info('Checking for new matching internships...');

        $students = Student::where(function ($query) {
            $query->whereNotNull('skills')
                  ->orWhereNotNull('degree');
        })->get();

        $notifiedCount = 0;

        foreach ($students as $student) {
            $studentSkills = $student->skills ? explode(',', $student->skills) : [];
            $studentDegree = $student->degree;

            $matchingInternships = Internship::where('status', 'published')
                ->where('deadline', '>=', now())
                ->where('created_at', '>', now()->subDays(3)) // Only very recent internships
                ->get()
                ->filter(function ($internship) use ($studentSkills, $studentDegree) {
                    $score = 0;
                    $internshipSkills = $internship->required_skills ? explode(',', $internship->required_skills) : [];

                    // Skills matching (70% weight)
                    if (!empty($studentSkills) && !empty($internshipSkills)) {
                        $matchingSkills = array_intersect($studentSkills, $internshipSkills);
                        $score += (count($matchingSkills) / max(count($internshipSkills), 1)) * 70;
                    }

                    // Degree matching (30% weight)
                    if ($studentDegree && $internship->requirements) {
                        $requirements = strtolower($internship->requirements);
                        $degree = strtolower($studentDegree);
                        if (strpos($requirements, $degree) !== false) {
                            $score += 30;
                        }
                    }

                    return $score > 50; // High relevance threshold for notifications
                });

            if ($matchingInternships->count() > 0) {
                $this->notifications->notify(
                    $student->user,
                    'new_internship',
                    'Nouveaux stages recommandés',
                    $matchingInternships->count() . ' nouveau' . ($matchingInternships->count() > 1 ? 'x' : '') . ' stage' . ($matchingInternships->count() > 1 ? 's' : '') . ' correspondant' . ($matchingInternships->count() > 1 ? 's' : '') . ' à votre profil',
                    ['count' => $matchingInternships->count()]
                );

                $notifiedCount++;
                $this->line("Notified student {$student->user->email} about {$matchingInternships->count()} matching internships");
            }
        }

        $this->info("Notification process completed. Notified {$notifiedCount} students.");
        return Command::SUCCESS;
    }
}
