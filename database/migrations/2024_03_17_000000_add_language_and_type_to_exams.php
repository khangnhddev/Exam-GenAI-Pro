<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->string('language')->default('en');
            $table->index('language');
        });

        Schema::table('questions', function (Blueprint $table) {
            $table->string('language')->default('en');
            $table->index('language');
        });
    }

    public function down()
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->dropColumn('language');
        });

        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn('language');
        });
    }
};