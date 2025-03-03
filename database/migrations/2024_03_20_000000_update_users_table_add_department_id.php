<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Add department_id column
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            
            // Remove old department column
            $table->dropColumn('department');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Add back the old department column
            $table->string('department')->nullable();
            
            // Remove the foreign key and department_id column
            $table->dropForeign(['department_id']);
            $table->dropColumn('department_id');
        });
    }
}; 