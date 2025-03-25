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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seminar_id');
            $table->foreignId('participant_id')->nullable()->constrained('participants')->cascadeOnDelete();
            $table->enum('payment_method', ['gcash', 'bpi', 'pay_later'])->nullable();
            $table->string('account_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('reference_number')->nullable();
            $table->string('screenshot')->nullable();
            $table->enum('payment_status', ['pending', 'completed', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
