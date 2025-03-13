<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->text('challenge_description')->nullable();
            $table->json('requirements')->nullable();
            $table->string('challenge_title')->nullable();
            $table->integer('time_limit')->nullable(); 
            $table->string('difficulty')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn([
                'challenge_description',
                'requirements',
                'evaluation_criteria',
                'challenge_title',
                'time_limit',
                'difficulty'
            ]);
        });
    }
};