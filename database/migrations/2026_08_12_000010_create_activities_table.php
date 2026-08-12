<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['call', 'meeting', 'email', 'note', 'stage_change', 'task', 'system'])->default('note');
            $table->string('subject');
            $table->text('description')->nullable();
            $table->nullableMorphs('activityable');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('scheduled_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
