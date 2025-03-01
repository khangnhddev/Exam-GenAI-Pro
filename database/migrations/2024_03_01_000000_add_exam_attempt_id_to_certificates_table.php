<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->foreignId('exam_attempt_id')->nullable()->after('exam_id')
                  ->constrained('exam_attempts')->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->dropForeign(['exam_attempt_id']);
            $table->dropColumn('exam_attempt_id');
        });
    }
};