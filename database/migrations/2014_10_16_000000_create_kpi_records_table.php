<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kpi_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('task_id')->nullable()->constrained('tasks')->onDelete('cascade');
            $table->decimal('task_completion_rate', 5, 2)->default(0); // Percentage
            $table->decimal('quality_score', 5, 2)->default(0); // 0-100
            $table->decimal('adherence_to_deadlines', 5, 2)->default(0); // Percentage
            $table->integer('tasks_completed')->default(0);
            $table->integer('tasks_on_time')->default(0);
            $table->integer('tasks_overdue')->default(0);
            $table->decimal('overall_score', 5, 2)->default(0); // Calculated overall KPI
            $table->date('record_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kpi_records');
    }
};