<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Company>
 */
class CompanyFactory extends Factory
{
    protected $model = Company::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->state(['role' => \App\Enums\Role::Company]),
            'name' => fake()->company(),
            'legal_name' => fake()->company(),
            'siret' => fake()->numerify('##########'),
            'description' => fake()->paragraph(),
            'address' => fake()->streetAddress(),
            'city' => fake()->city(),
            'country' => 'FR',
            'logo_path' => null,
            'approval_status' => 'approved',
            'approved_at' => now(),
        ];
    }
}
