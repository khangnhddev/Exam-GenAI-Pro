<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // First, create the pgvector extension
        DB::statement('CREATE EXTENSION IF NOT EXISTS vector');

        // Then create the table
        Schema::create('knowledge_bases', function (Blueprint $table) {
            $table->id();
            $table->text('content');
            $table->string('source');
            $table->timestamps();
        });

        // Finally, add the vector column
        DB::statement('ALTER TABLE knowledge_bases ADD COLUMN embedding vector(1536)');
    }

    public function down(): void
    {
        Schema::dropIfExists('knowledge_bases');
        DB::statement('DROP EXTENSION IF EXISTS vector');
    }
};
