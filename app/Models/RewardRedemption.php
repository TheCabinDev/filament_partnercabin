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
        'poin_to_redeem',
        'cash_amount',
        'redemption_status',
    ];

    protected $casts = [
        'poin_to_redeem' => 'integer',
        'cash_amount' => 'decimal:2',
    ];

    /**
     * Relationship ke Partner
     */
    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partners::class, 'id_partner');
    }
}
