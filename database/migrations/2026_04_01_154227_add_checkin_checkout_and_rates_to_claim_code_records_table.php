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
        Schema::table('claim_code_records', function (Blueprint $table) {
            // Menambahkan kolom waktu check-in dan check-out
            // Nullable penting karena data lama belum memiliki nilai ini
            $table->dateTime('check_in_time')->nullable()->after('reservation_status');
            $table->dateTime('check_out_time')->nullable()->after('check_in_time');

            // Menambahkan rate untuk tamu dan profit (Decimal 10,2)
            // Digunakan untuk perhitungan komisi sesuai Pasal 3 Ayat 1
            $table->decimal('rate_for_guest', 10, 2)->nullable()->after('check_out_time');
            $table->decimal('rate_profit', 10, 2)->nullable()->after('rate_for_guest');

            // Tambahkan index jika Anda sering melakukan query berdasarkan waktu check-in
            $table->index(['check_in_time', 'check_out_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('claim_code_records', function (Blueprint $table) {
            $table->dropColumn(['check_in_time', 'check_out_time', 'rate_for_guest', 'rate_profit']);
        });
    }
};
