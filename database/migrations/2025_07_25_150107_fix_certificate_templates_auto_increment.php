<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if table exists and fix the id column to have proper auto-increment
        if (Schema::hasTable('certificate_templates')) {
            DB::statement('ALTER TABLE certificate_templates MODIFY id BIGINT UNSIGNED AUTO_INCREMENT');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove auto-increment if rollback is needed
        DB::statement('ALTER TABLE certificate_templates MODIFY id BIGINT UNSIGNED');
    }
};
