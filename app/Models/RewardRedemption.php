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
        'redemption_status'
    ];

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partners::class, 'id_partner');
    }

}
