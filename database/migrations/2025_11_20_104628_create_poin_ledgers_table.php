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
        Schema::create('poin_ledgers', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('poin_activity_id')->unsigned();
            $table->foreign('poin_activity_id')->references('id')->on('poin_activities')->onDelete('cascade');

            $table->bigInteger('id_unique_code')->unsigned();
            $table->foreign('id_unique_code')->references('id')->on('partners_codes')->onDelete('cascade');


            $table->bigInteger('id_partner')->unsigned();
            $table->foreign('id_partner')->references('id')->on('partners')->onDelete('cascade');

            $table->decimal('initial_amount', 10, 2);
            $table->decimal('remaining_amount', 10, 2);
            $table->datetime('earn_date')->nullable();
            $table->datetime('expire_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('poin_ledgers');
    }
};
