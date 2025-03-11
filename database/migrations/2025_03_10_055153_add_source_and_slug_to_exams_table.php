<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Models\Exam;

return new class extends Migration
{
    public function up(): void
    {
        // First add columns as nullable
        Schema::table('exams', function (Blueprint $table) {
            $table->string('source')->default('manual')->after('status');
            $table->string('slug')->nullable()->after('title');
        });

        // Update existing records with slugs
        $exams = Exam::all();
        foreach ($exams as $exam) {
            $slug = Str::slug($exam->title);
            $count = 1;
            
            // Ensure unique slug
            while (Exam::where('slug', $slug)->where('id', '!=', $exam->id)->exists()) {
                $slug = Str::slug($exam->title) . '-' . $count;
                $count++;
            }
            
            $exam->slug = $slug;
            $exam->save();
        }

        // Now make slug required and unique
        Schema::table('exams', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->unique()->change();
        });
    }

    public function down(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->dropColumn(['source', 'slug']);
        });
    }
};
