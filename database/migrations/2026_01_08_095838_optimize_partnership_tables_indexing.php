<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * 08012026
     * created by : alvinChristianto
     * adding indexing on existing tables without dropping tables (migrate:fresh)
     */
    public function up(): void
    {
        // 1. Tabel Partners
        Schema::table('partners', function (Blueprint $table) {
            $table->index('status');
            $table->index('creator_id');
        });

        // 2. Tabel Partners Codes
        Schema::table('partners_codes', function (Blueprint $table) {

            // Compound Index untuk performa API (Validasi Kode)
            $table->index(['unique_code', 'status', 'use_expired_at'], 'idx_code_validation');
        });

        // 3. Tabel Claim Code Records
        Schema::table('claim_code_records', function (Blueprint $table) {
            // Ubah skala desimal ke 15,2 (Gunakan change())
            $table->decimal('reservation_total_price', 15, 2)->change();

            // Tambahkan Unique Constraint (Pastikan data reservation_id tidak ada yang duplikat sebelumnya)
            // Jika ragu ada duplikat, gunakan index biasa dulu: $table->index('reservation_id');
            $table->unique('reservation_id');

            $table->index('reservation_status');
            $table->index('created_at');
        });

        // 4. Tabel Reward Redemptions
        Schema::table('reward_redemptions', function (Blueprint $table) {
            // Ubah skala desimal
            $table->decimal('cash_amount', 15, 2)->change();

            // Index untuk filter admin
            $table->index(['redemption_status', 'type_reward'], 'idx_redemption_filter');
            $table->index('id_partner');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Menghapus index jika migration di-rollback
        Schema::table('partners', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['creator_id']);
        });

        Schema::table('partners_codes', function (Blueprint $table) {
            $table->dropIndex('idx_code_validation');
        });

        Schema::table('claim_code_records', function (Blueprint $table) {
            $table->dropUnique(['reservation_id']);
            $table->dropIndex(['reservation_status']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('reward_redemptions', function (Blueprint $table) {
            $table->dropIndex('idx_redemption_filter');
            $table->dropIndex(['id_partner']);
        });
    }
};
