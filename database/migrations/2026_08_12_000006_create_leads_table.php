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
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('company_name')->nullable();
            $table->string('email')->nullable()->index();
            $table->string('phone')->nullable();
            $table->string('source')->nullable();
            $table->enum('status', ['new', 'contacted', 'qualified', 'unqualified', 'converted'])->default('new');
            $table->decimal('estimated_value', 15, 2)->nullable();
            $table->foreignId('owner_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('converted_contact_id')->nullable()->constrained('contacts')->nullOnDelete();
            $table->foreignId('converted_deal_id')->nullable()->constrained('deals')->nullOnDelete();
            $table->timestamp('converted_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
