<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Contact;
use App\Models\Lead;
use App\Models\Deal;
use App\Models\Task;
use App\Models\Meeting;
use App\Models\CalendarEvent;
use App\Models\Pipeline;
use App\Models\PipelineStage;
use Illuminate\Database\Seeder;

class FakeDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ensure Pipeline & Stages exist
        $pipeline = Pipeline::firstOrCreate(
            ['is_default' => true],
            ['name' => 'Sales Pipeline', 'description' => 'Default sales process pipeline']
        );

        $defaultStages = [
            ['name' => 'Lead In', 'order' => 1, 'color' => '#6366f1'],
            ['name' => 'Contact Made', 'order' => 2, 'color' => '#3b82f6'],
            ['name' => 'Demo Scheduled', 'order' => 3, 'color' => '#06b6d4'],
            ['name' => 'Proposal Sent', 'order' => 4, 'color' => '#f59e0b'],
            ['name' => 'Negotiation', 'order' => 5, 'color' => '#ec4899'],
            ['name' => 'Won', 'order' => 6, 'color' => '#10b981'],
        ];

        foreach ($defaultStages as $stg) {
            PipelineStage::firstOrCreate(
                ['pipeline_id' => $pipeline->id, 'name' => $stg['name']],
                ['order' => $stg['order'], 'color' => $stg['color']]
            );
        }

        // 2. Create Companies
        $companies = Company::factory()->count(15)->create();

        // 3. Create Contacts
        $contacts = Contact::factory()->count(30)->create();

        // 4. Create Leads
        Lead::factory()->count(25)->create();

        // 5. Create Deals distributed over months for realistic reporting
        $stages = PipelineStage::where('pipeline_id', $pipeline->id)->get();
        $statuses = ['won', 'won', 'lost', 'open', 'open'];

        for ($i = 0; $i < 35; $i++) {
            $status = $statuses[array_rand($statuses)];
            $randomStage = $stages->random();
            if ($status === 'won') {
                $randomStage = $stages->where('name', 'Won')->first() ?? $randomStage;
            }

            // Distribute created_at across months of current year
            $month = rand(1, date('n'));
            $createdAt = \Carbon\Carbon::create(date('Y'), $month, rand(1, 28), rand(9, 18), rand(0, 59));

            Deal::factory()->create([
                'company_id' => $companies->random()->id,
                'contact_id' => $contacts->random()->id,
                'pipeline_id' => $pipeline->id,
                'stage_id' => $randomStage->id,
                'status' => $status,
                'value' => rand(8000, 150000),
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }

        // 6. Create Tasks
        Task::factory()->count(25)->create();

        // 7. Create Meetings
        Meeting::factory()->count(15)->create();

        // 8. Create Calendar Events
        CalendarEvent::factory()->count(10)->create();
    }
}
