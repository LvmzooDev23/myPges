<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Internship;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Internship>
 */
class InternshipFactory extends Factory
{
    protected $model = Internship::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'title' => fake()->jobTitle(),
            'description' => fake()->paragraphs(3, true),
            'location' => fake()->city(),
            'type' => fake()->randomElement(['remote', 'onsite', 'hybrid']),
            'start_date' => now()->addMonth(),
            'end_date' => now()->addMonths(4),
            'slots' => fake()->numberBetween(1, 5),
            'status' => 'published',
            'requirements' => fake()->sentence(),
        ];
    }
}
