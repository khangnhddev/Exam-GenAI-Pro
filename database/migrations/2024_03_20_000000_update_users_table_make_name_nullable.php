<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // PostgreSQL requires this syntax for modifying columns
            DB::statement('ALTER TABLE users ALTER COLUMN name DROP NOT NULL');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            DB::statement('ALTER TABLE users ALTER COLUMN name SET NOT NULL');
        });
    }
}; 