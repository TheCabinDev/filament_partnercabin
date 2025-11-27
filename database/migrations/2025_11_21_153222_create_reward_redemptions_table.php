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
        Schema::create('reward_redemptions', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('id_partner')->unsigned();
            $table->foreign('id_partner')->references('id')->on('partners')->onDelete('cascade');

            $table->bigInteger('id_unique_code')->unsigned();
            $table->foreign('id_unique_code')->references('id')->on('partners_codes')->onDelete('cascade');

            $table->enum('type_reward', ['CASH', 'VOUCHER_STAY', 'MERCHANDISE']);
            $table->decimal('poin_to_redeem', 10, 2);
            $table->decimal('cash_amount', 10, 2)->nullable();
            $table->string('destination_bank', 100);        // probably move to partners
            $table->string('account_number', 100);          // probably move to partners
            $table->enum('redemption_status', ['PENDING', 'PROCESSING', 'COMPLETED', 'REJECTED']);
            $table->string('settlement_proof_image', 255)->nullable();
            $table->text('settlement_notes')->nullabe();
            $table->dateTime('request_date')->nullable();
            $table->dateTime('settlement_date')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reward_redemptions');
    }
};
