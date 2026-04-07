<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RewardRedemption extends Model
{
    use HasFactory;

    protected $table = 'reward_redemptions';

    protected $fillable = [
        'id_partner',
        'type_reward',
        'raw_amount_to_redeem',
        'cash_amount',
        'redemption_status',
        'admin_fee_amount',
        'settlement_notes',
        'settlement_proof_image',
        'pdf_reward_trx',
        'request_date',
        'settlement_date',
    ];

    protected $casts = [
        'raw_amount_to_redeem' => 'integer',
        'cash_amount' => 'decimal:2',
        'admin_fee_amount' => 'decimal:2',
        'settlement_notes' => 'string',
        'settlement_proof_image' => 'string',

    ];

    /**
     * Relationship ke Partner
     */
    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partners::class, 'id_partner');
    }
}
