<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->string('category')->nullable()->after('status');
            $table->enum('difficulty', ['beginner', 'intermediate', 'advanced'])
                  ->default('intermediate')
                  ->after('category');
        });
    }

    public function down(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->dropColumn(['category', 'difficulty']);
        });
    }
};