<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PoinLedgers extends Model
{

    protected $table = 'poin_ledgers';

    protected $fillable = [
        'poin_activity_id',
        'id_unique_code',
        'id_partner',
        'initial_amount',
        'remaining_amount',
        'earn_date',
        'expire_date'
    ];
    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partners::class, 'id_unique_code');
    }

    public function partnercode(): BelongsTo
    {
        return $this->belongsTo(PartnersCode::class, 'id_unique_code');
    }

    // point ledger belongs to partner_code 
    // point ledger belongs to partner
    // poin ledger belongs to poin activity
}
