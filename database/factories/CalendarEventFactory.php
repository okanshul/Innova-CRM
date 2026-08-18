<?php

namespace Database\Factories;

use App\Models\CalendarEvent;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CalendarEventFactory extends Factory
{
    protected $model = CalendarEvent::class;

    public function definition(): array
    {
        $start = fake()->dateTimeBetween('-1 month', '+1 month');
        $end = (clone $start)->modify('+2 hours');

        return [
            'title' => fake()->sentence(3),
            'description' => fake()->sentence(6),
            'location' => fake()->randomElement(['Google Meet', 'Zoom', 'Conference Room', 'Office']),
            'start_at' => $start,
            'end_at' => $end,
            'is_all_day' => fake()->boolean(15),
            'user_id' => User::inRandomOrder()->first()?->id ?? 1,
            'created_by' => User::inRandomOrder()->first()?->id ?? 1,
        ];
    }
}
