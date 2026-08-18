<?php

namespace Database\Factories;

use App\Models\Meeting;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MeetingFactory extends Factory
{
    protected $model = Meeting::class;

    public function definition(): array
    {
        $start = fake()->dateTimeBetween('-2 months', '+1 month');
        $end = (clone $start)->modify('+1 hour');

        return [
            'title' => fake()->sentence(3) . ' Review',
            'description' => fake()->paragraph(),
            'location' => fake()->randomElement(['Google Meet', 'Zoom', 'Conference Room A', 'HQ Office']),
            'meeting_link' => 'https://meet.google.com/' . fake()->slug(2),
            'start_at' => $start,
            'end_at' => $end,
            'status' => fake()->randomElement(['scheduled', 'completed', 'cancelled']),
            'outcome_summary' => fake()->optional(0.6)->sentence(),
            'host_id' => User::inRandomOrder()->first()?->id ?? 1,
            'created_by' => User::inRandomOrder()->first()?->id ?? 1,
        ];
    }
}
