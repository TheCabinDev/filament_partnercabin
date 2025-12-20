<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\ClaimCodeRecord;
use App\Models\PartnersCode;
use Illuminate\Http\Request;

class PartnerCodesController extends Controller
{

    public function allCode()
    {
        $partnerId = auth()->user()->id;

        $allcode = PartnersCode::where('id_partner', $partnerId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'data' => $allcode
        ], 200);
    }

    public function codeTransaction($code_id)
    {
        $partnerId = auth()->user()->id;

        // 1. Pastikan kode benar-benar milik Partner yang login
        $code = PartnersCode::where('id', $code_id)
            ->where('id_partner', $partnerId)
            ->first();
        // 2. Ambil semua transaksi klaim terkait kode tersebut
        $transactions = ClaimCodeRecord::where('id_code', $code_id)
            ->orderBy('created_at', 'desc')
            ->get([
                'reservation_id',
                'reservation_total_price',
                'total_poin_earned',
                'reservation_status',
                'created_at'
            ]);

        return response()->json([
            'code_name' => $code->unique_code,
            'transactions' => $transactions
        ], 200);
    }
}
