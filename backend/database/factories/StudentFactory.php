<?php

namespace Database\Factories;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Student>
 */
class StudentFactory extends Factory
{
    protected $model = Student::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'student_number' => fake()->unique()->numerify('STU####'),
            'phone' => fake()->phoneNumber(),
            'bio' => fake()->paragraph(),
            'cv_path' => null,
            'supervisor_id' => null,
        ];
    }
}
