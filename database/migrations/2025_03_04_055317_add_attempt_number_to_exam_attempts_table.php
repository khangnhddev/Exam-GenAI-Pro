<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('exam_attempts', function (Blueprint $table) {
            $table->integer('attempt_number')->after('exam_id')->default(1);
        });

        // Populate existing records with attempt numbers
        DB::statement('
            UPDATE exam_attempts a
            SET attempt_number = (
                SELECT COUNT(*)
                FROM exam_attempts b
                WHERE b.user_id = a.user_id
                AND b.exam_id = a.exam_id
                AND b.created_at <= a.created_at
            )
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exam_attempts', function (Blueprint $table) {
            $table->dropColumn('attempt_number');
        });
    }
};