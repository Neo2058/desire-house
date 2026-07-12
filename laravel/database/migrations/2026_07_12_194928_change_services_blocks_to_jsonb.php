<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            ALTER TABLE services
            ALTER COLUMN blocks
            TYPE jsonb
            USING blocks::jsonb
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("
            ALTER TABLE services
            ALTER COLUMN blocks
            TYPE json
            USING blocks::json
        ");
    }
};
