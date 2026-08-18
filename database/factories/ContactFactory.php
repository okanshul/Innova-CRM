<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactFactory extends Factory
{
    protected $model = Contact::class;

    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'mobile' => fake()->phoneNumber(),
            'job_title' => fake()->jobTitle(),
            'company_id' => Company::inRandomOrder()->first()?->id,
            'owner_id' => User::inRandomOrder()->first()?->id ?? 1,
            'status' => fake()->randomElement(['lead', 'prospect', 'customer', 'inactive']),
            'source' => fake()->randomElement(['Website', 'Referral', 'Cold Call', 'Social Media', 'Email Campaign', 'Event']),
            'address' => fake()->streetAddress(),
            'city' => fake()->city(),
            'state' => fake()->state(),
            'zip_code' => fake()->postcode(),
            'country' => 'United States',
            'last_contacted_at' => fake()->optional(0.7)->dateTimeBetween('-6 months', 'now'),
            'created_by' => User::inRandomOrder()->first()?->id ?? 1,
        ];
    }
}
