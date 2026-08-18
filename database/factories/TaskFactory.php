<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        $status = fake()->randomElement(['pending', 'in_progress', 'completed', 'completed']);
        return [
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'due_date' => fake()->dateTimeBetween('-1 month', '+1 month'),
            'priority' => fake()->randomElement(['low', 'medium', 'high']),
            'status' => $status,
            'assigned_to' => User::inRandomOrder()->first()?->id ?? 1,
            'created_by' => User::inRandomOrder()->first()?->id ?? 1,
            'completed_at' => $status === 'completed' ? fake()->dateTimeBetween('-1 month', 'now') : null,
        ];
    }
}
