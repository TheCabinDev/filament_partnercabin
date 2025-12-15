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
        Schema::create('partners_codes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_partner');
            $table->unsignedBigInteger('id_creator');
            $table->string('unique_code', 50)->unique();
            $table->decimal('fee_percentage', 5, 2)->nullable();
            $table->decimal('reduction_percentage', 5, 2)->nullable();     //change to reduction_percentage
            $table->integer('claim_quota')->nullable();     //set to 999
            $table->integer('max_claim_per_account')->nullable();   //set to 999
            $table->dateTime('use_started_at')->nullable();
            $table->dateTime('use_expired_at')->nullable();
            $table->enum('status', ['ACTIVE', 'INACTIVE'])->default('INACTIVE');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('id_partner')
                ->references('id')
                ->on('partners')
                ->onDelete('cascade');

            $table->foreign('id_creator')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            // Indexes
            $table->index('id_partner');
            $table->index('id_creator');
            $table->index('status');
            $table->index('use_started_at');
            $table->index('use_expired_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partner_codes');
    }
};
