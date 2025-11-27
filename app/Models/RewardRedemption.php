<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RewardRedemption extends Model
{
    protected $table = 'reward_redemptions';

    protected $fillable = [
        'id_partner',
        'type_reward',
        'poin_to_redeem',
        'cash_amount',
        'redeption_status',
        'settlement_proof_image',
        'settlement_notes',
        'request_date',
        'settlement_date',
    ];

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partners::class, 'id_partner');
    }

    public function partnercode(): BelongsTo
    {
        return $this->belongsTo(PartnersCode::class, 'id_unique_code');
    }
}
