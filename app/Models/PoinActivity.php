<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PoinActivity extends Model
{

    protected $table = 'poin_activities';

    protected $fillable = [
        'reservation_id',
        'id_unique_code',
        'id_partner',
        'type_activity',
        'amount',
        'date_transaction'
    ];

    public function claimcoderecord(): BelongsTo
    {
        return $this->belongsTo(ClaimCodeRecord::class, 'id_partner');
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partners::class, 'id_partner');
    }

    public function partnercode(): BelongsTo
    {
        return $this->belongsTo(PartnersCode::class, 'id_partner');
    }
    // poin activity belongs to partner 

    // poin activity belongs to partner_codes
    // poin activity one on one partner_codes
}
