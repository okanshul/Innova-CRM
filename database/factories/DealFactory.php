<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Contact;
use App\Models\Deal;
use App\Models\Pipeline;
use App\Models\PipelineStage;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DealFactory extends Factory
{
    protected $model = Deal::class;

    public function definition(): array
    {
        $status = fake()->randomElement(['won', 'won', 'lost', 'open', 'open']);
        $pipeline = Pipeline::first() ?? Pipeline::create(['name' => 'Sales Pipeline', 'is_default' => true]);
        $stage = PipelineStage::where('pipeline_id', $pipeline->id)->inRandomOrder()->first();

        return [
            'title' => fake()->bs() . ' Contract',
            'value' => fake()->randomFloat(2, 5000, 250000),
            'currency' => 'USD',
            'company_id' => Company::inRandomOrder()->first()?->id,
            'contact_id' => Contact::inRandomOrder()->first()?->id,
            'pipeline_id' => $pipeline->id,
            'stage_id' => $stage?->id,
            'owner_id' => User::inRandomOrder()->first()?->id ?? 1,
            'expected_close_date' => fake()->dateTimeBetween('now', '+6 months')->format('Y-m-d'),
            'status' => $status,
            'lost_reason' => $status === 'lost' ? fake()->sentence() : null,
            'closed_at' => in_array($status, ['won', 'lost']) ? fake()->dateTimeBetween('-6 months', 'now') : null,
            'created_by' => User::inRandomOrder()->first()?->id ?? 1,
        ];
    }
}
