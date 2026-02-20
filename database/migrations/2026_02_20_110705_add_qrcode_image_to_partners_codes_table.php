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
        Schema::table('partners_codes', function (Blueprint $table) {
            // Menambahkan kolom qrcode_image setelah unique_code
            $table->string('qrcode_image', 255)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('partners_codes', function (Blueprint $table) {
            $table->dropColumn('qrcode_image');
        });
    }
};
