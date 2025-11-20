<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ClaimCodeRecord extends Model
{
    use HasFactory;

    protected $table = 'claim_code_records';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'id_partner',
        'id_code',
        'reservation_id',
        'reservation_total_price',
        'total_poin_earned',
        'reservation_status',
    ];

    protected $casts = [
        'id_partner' => 'integer',
        'id_code' => 'integer',
        'reservation_total_price' => 'decimal:2',
        'total_poin_earned' => 'decimal:2',
    ];


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partners::class, 'id_partner');
    }

    public function partnercode (): BelongsTo
    {
        return $this->belongsTo(PartnersCode::class, 'id_code');
    }
    
    public function scopeSuccess($query)
    {
        return $query->where('reservation_status', 'SUCCESS');
    }

    public function scopeExpired($query)
    {
        return $query->where('reservation_status', 'EXPIRED');
    }

    public function scopeByPartner($query, $partnerId)
    {
        return $query->where('id_partner', $partnerId);
    }

    public function scopeByCode($query, $codeId)
    {
        return $query->where('id_code', $codeId);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
                     ->whereYear('created_at', now()->year);
    }
}
