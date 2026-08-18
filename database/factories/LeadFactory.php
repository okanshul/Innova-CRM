<?php

namespace Database\Factories;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LeadFactory extends Factory
{
    protected $model = Lead::class;

    public function definition(): array
    {
        return [
            'title' => fake()->catchPhrase(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'company_name' => fake()->company(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'source' => fake()->randomElement(['Website', 'Referral', 'Cold Call', 'Social Media', 'Email Campaign', 'Event']),
            'status' => fake()->randomElement(['new', 'contacted', 'qualified', 'unqualified']),
            'estimated_value' => fake()->randomFloat(2, 1000, 150000),
            'owner_id' => User::inRandomOrder()->first()?->id ?? 1,
            'created_by' => User::inRandomOrder()->first()?->id ?? 1,
        ];
    }
}
