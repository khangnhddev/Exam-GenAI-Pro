<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('duration')->comment('Duration in minutes');
            $table->integer('passing_score');
            $table->integer('total_questions');
            $table->enum('status', ['draft', 'published', 'archived']);
            $table->integer('attempts_allowed')->default(1);
            $table->timestamps();
        });

        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained()->onDelete('cascade');
            $table->text('question_text');
            $table->text('explanation')->nullable();
            $table->integer('points')->default(1);
            $table->enum('type', ['single', 'multiple']);
            $table->timestamps();
        });

        // Schema::create('options', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('question_id')->constrained()->onDelete('cascade');
        //     $table->text('option_text');
        //     $table->boolean('is_correct');
        //     $table->timestamps();
        // });

        Schema::create('exam_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->timestamp('started_at');
            $table->timestamp('completed_at')->nullable();
            $table->integer('score')->nullable();
            $table->json('answers')->nullable();
            $table->enum('status', ['in_progress', 'completed', 'expired']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_attempts');
        Schema::dropIfExists('options');
        Schema::dropIfExists('questions');
        Schema::dropIfExists('exams');
    }
};