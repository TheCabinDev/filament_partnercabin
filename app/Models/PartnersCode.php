<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;



class PartnersCode extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'partners_codes';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_partner',
        'id_creator',
        'unique_code',
        'fee_percentage',
        'reduction_percentage',
        'claim_quota',
        'max_claim_per_account',
        'use_started_at',
        'use_expired_at',
        'status',
    ];

    protected $casts = [
        'id_partner' => 'string',
        'id_creator' => 'string',
        'fee_percentage' => 'decimal:2',
        'reduction_percentage' => 'decimal:2',
        'claim_quota' => 'integer',
        'max_claim_per_account' => 'integer',
        'use_started_at' => 'datetime',
        'use_expired_at' => 'datetime',
    ];

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partners::class, 'id_partner');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_creator');
    }

    public function claimRecords(): HasMany

    {
        return $this->hasMany(ClaimCodeRecord::class, 'id');
    }


    public function scopeActive($query)
    {
        return $query->where('status', 'ACTIVE');
    }

    public function scopeValid($query)
    {
        return $query->where('use_started_at', '<=', now())
            ->where('use_expired_at', '>=', now());
    }

    public function poinactivity(): HasMany
    {
        return $this->hasMany(PoinActivity::class, 'id_unique_code');
    }

    public function poinledger(): HasMany
    {
        return $this->hasMany(PoinLedgers::class, 'id_unique_code');
    }
    // partner_code has many poin_activity
    // partner code  has many poin ledger
}
