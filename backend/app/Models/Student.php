<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'student_number',
        'phone',
        'bio',
        'university',
        'degree',
        'skills',
        'linkedin_url',
        'cv_path',
        'supervisor_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($student) {
            if (empty($student->student_number)) {
                $student->student_number = self::generateStudentNumber();
            }
        });
    }

    public static function generateStudentNumber(): string
    {
        $year = date('Y');
        $prefix = "STU-{$year}-";
        
        $lastNumber = DB::table('students')
            ->where('student_number', 'like', $prefix . '%')
            ->orderByRaw('CAST(SUBSTRING(student_number, LENGTH(?) + 1) AS UNSIGNED) DESC', [$prefix])
            ->value('student_number');

        if ($lastNumber) {
            $lastSequence = (int) substr($lastNumber, strlen($prefix));
            $newSequence = $lastSequence + 1;
        } else {
            $newSequence = 1;
        }

        return $prefix . str_pad($newSequence, 4, '0', STR_PAD_LEFT);
    }

    public function getProfileCompletionAttribute(): int
    {
        $fields = [
            'first_name',
            'last_name', 
            'student_number',
            'phone',
            'university',
            'degree',
            'skills',
            'linkedin_url',
            'cv_path'
        ];

        $completed = 0;
        foreach ($fields as $field) {
            if (!empty($this->$field)) {
                $completed++;
            }
        }

        return (int) round(($completed / count($fields)) * 100);
    }

    public function getMissingProfileFieldsAttribute(): array
    {
        $fields = [
            'first_name' => 'Prénom',
            'last_name' => 'Nom',
            'student_number' => 'Numéro étudiant',
            'phone' => 'Téléphone',
            'university' => 'Université',
            'degree' => 'Diplôme',
            'skills' => 'Compétences',
            'linkedin_url' => 'Profil LinkedIn',
            'cv_path' => 'CV'
        ];

        $missing = [];
        foreach ($fields as $field => $label) {
            if (empty($this->$field)) {
                $missing[] = $label;
            }
        }

        return $missing;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(Supervisor::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }
}
