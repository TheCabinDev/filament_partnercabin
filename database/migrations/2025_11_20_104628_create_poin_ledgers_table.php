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

            $table->foreignId('poin_activity_id')->constrained('poin_activities')->onDelete('cascade');

            $table->foreignUuid('id_unique_code')->constrained('partners_codes')->onDelete('cascade');

            $table->foreignUuid('id_partner')
                ->constrained('partners')
                ->onDelete('cascade');

            $table->decimal('initial_amount', 10, 2);
            $table->decimal('remaining_amount', 10, 2);
            $table->date('earn_date')->nullable();
            $table->date('expire_date')->nullable();
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
