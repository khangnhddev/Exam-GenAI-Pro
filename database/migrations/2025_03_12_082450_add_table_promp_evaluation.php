<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prompt_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attempt_id')->constrained('exam_attempts')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('questions')->onDelete('cascade');
            $table->text('user_answer');
            $table->text('ai_feedback')->nullable();
            $table->integer('ai_score')->nullable();
            $table->boolean('is_passed')->nullable();
            $table->json('evaluation_criteria')->nullable(); // Store detailed scoring breakdown
            $table->timestamp('evaluated_at')->nullable();
            $table->timestamps();

            // Add indexes for better query performance
            $table->index(['attempt_id', 'question_id']);
            $table->index('is_passed');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prompt_evaluations');
    }
};