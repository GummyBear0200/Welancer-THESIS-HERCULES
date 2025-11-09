<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leaderboards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('rank')->default(0);
            $table->decimal('total_score', 8, 2)->default(0);
            $table->integer('total_tasks_completed')->default(0);
            $table->decimal('average_quality_score', 5, 2)->default(0);
            $table->decimal('on_time_percentage', 5, 2)->default(0);
            $table->string('period')->default('monthly'); // daily, weekly, monthly, yearly
            $table->date('period_start');
            $table->date('period_end');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leaderboards');
    }
};