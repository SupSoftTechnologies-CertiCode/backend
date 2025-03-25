<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations. 
     */
    public function up(): void
    {
        Schema::create('seminars', function (Blueprint $table) {
            $table->id();
            $table->string('name_of_seminar');
            $table->string('topics')->nullable();
            $table->text('description')->nullable();
            $table->date('date')->nullable();
            $table->string('location')->nullable();
            $table->string('speaker_name')->nullable();
            $table->string('organization_name')->nullable();
            $table->string('speaker_image')->nullable();
            $table->string('seminar_image')->nullable();
            $table->text('about_the_speaker')->nullable();
            $table->foreignId('certificate_template_id')->nullable();
            $table->integer('price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seminars');
    }
};
