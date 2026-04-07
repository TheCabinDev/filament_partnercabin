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
        Schema::table('reward_redemptions', function (Blueprint $table) {
            $table->renameColumn('poin_to_redeem', 'raw_amount_to_redeem');
            $table->decimal('admin_fee_amount', 10, 2)->nullable()->after('cash_amount');
            $table->string('pdf_reward_trx', 255)->nullable()->after('settlement_notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reward_redemptions', function (Blueprint $table) {
            $table->dropColumn(['raw_amount_to_redeem', 'admin_fee_amount', 'pdf_reward_trx']);
        });
    }
};
