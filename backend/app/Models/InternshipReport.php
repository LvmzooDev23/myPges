<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InternshipReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'student_id',
        'supervisor_id',
        'file_path',
        'title',
        'status',
        'feedback',
        'validated_at',
        'submitted_at',
    ];

    protected $casts = [
        'validated_at' => 'datetime',
        'submitted_at' => 'datetime',
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(Supervisor::class);
    }
}
