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
        Schema::create('poin_activities', function (Blueprint $table) {
            $table->id();

            $table->string('reservation_id', 255);
            
            $table->foreignUuid('id_unique_code')->references('id')->on('partners_codes')->onDelete('cascade');

            $table->foreignUuid('id_partner')->constrained('partners')->onDelete('cascade');

            $table->enum('type_activity', ['EARN', 'USE', 'EXPIRE']);
            $table->decimal('amount', 10, 2);
            $table->datetime('date_transaction')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('poin_activities');
    }
};
