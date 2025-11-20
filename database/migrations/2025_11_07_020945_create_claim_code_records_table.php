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
        Schema::create('claim_code_records', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('id_partner')->constrained('partners')->onDelete('cascade');
            $table->foreignId('id_code')->constrained('partners_codes')->onDelete('cascade');
            $table->string('reservation_id', 255);
            $table->decimal('reservation_total_price', 10, 2);
            $table->decimal('total_poin_earned', 10, 2);
            $table->enum('reservation_status', ['EXPIRED', 'SUCCESS'])->default('EXPIRED');
            $table->timestamps();

            // Indexes
            // $table->index('id_partner');
            // $table->index('id_code');
            // $table->index('reservation_id');
            // $table->index('reservation_status');
            // $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('claim_code_records');
    }
};
