<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null'); // Team Member
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['pending', 'in_progress', 'completed', 'overdue'])->default('pending');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->date('start_date')->nullable();
            $table->date('due_date')->nullable();
            $table->date('completed_date')->nullable();
            $table->integer('estimated_hours')->nullable();
            $table->integer('actual_hours')->nullable();
            $table->decimal('quality_score', 5, 2)->nullable(); // 0-100 for quality rating
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};