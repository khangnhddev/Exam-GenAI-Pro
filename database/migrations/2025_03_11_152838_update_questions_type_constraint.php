<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop existing check constraint
        DB::statement('ALTER TABLE questions DROP CONSTRAINT IF EXISTS questions_type_check');

        // Update existing records to match new types
        DB::statement("
            UPDATE questions 
            SET type = CASE 
                WHEN type = 'single' THEN 'single_choice'
                WHEN type = 'multiple' THEN 'multiple_choice'
                ELSE type 
            END
            WHERE type NOT IN ('single_choice', 'multiple_choice', 'prompt')
        ");
        
        // Add new constraint with 'prompt' type
        DB::statement("ALTER TABLE questions ADD CONSTRAINT questions_type_check CHECK (type::text = ANY (ARRAY['single_choice'::text, 'multiple_choice'::text, 'prompt'::text]))");
    }

    public function down(): void
    {
        // Drop new constraint
        DB::statement('ALTER TABLE questions DROP CONSTRAINT IF EXISTS questions_type_check');
        
        // Update records back to original format
        DB::statement("
            UPDATE questions 
            SET type = CASE 
                WHEN type = 'single_choice' THEN 'single'
                WHEN type = 'multiple_choice' THEN 'multiple'
                ELSE type 
            END
        ");
        
        // Restore original constraint
        DB::statement("ALTER TABLE questions ADD CONSTRAINT questions_type_check CHECK (type::text = ANY (ARRAY['single'::text, 'multiple'::text]))");
    }
};