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
        // Fix the guests table id column to have proper auto-increment
        if (Schema::hasTable('guests')) {
            DB::statement('ALTER TABLE guests MODIFY id BIGINT UNSIGNED AUTO_INCREMENT');
        }
        
        // Fix the participants table id column to have proper auto-increment
        if (Schema::hasTable('participants')) {
            DB::statement('ALTER TABLE participants MODIFY id BIGINT UNSIGNED AUTO_INCREMENT');
        }
        
        // Fix the seminars table id column to have proper auto-increment
        if (Schema::hasTable('seminars')) {
            DB::statement('ALTER TABLE seminars MODIFY id BIGINT UNSIGNED AUTO_INCREMENT');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove auto-increment if rollback is needed
        if (Schema::hasTable('guests')) {
            DB::statement('ALTER TABLE guests MODIFY id BIGINT UNSIGNED');
        }
        
        if (Schema::hasTable('participants')) {
            DB::statement('ALTER TABLE participants MODIFY id BIGINT UNSIGNED');
        }
        
        if (Schema::hasTable('seminars')) {
            DB::statement('ALTER TABLE seminars MODIFY id BIGINT UNSIGNED');
        }
    }
};