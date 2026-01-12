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

    public function updateFees(Request $request, string $code_id)
    {
        $validated = $request->validate([
            'fee_percentage' => ['nullable', 'numeric', 'min:0', 'max:20'],
            'reduction_percentage' => ['nullable', 'numeric', 'min:0', 'max:20'],
        ]);

        $partnerId = auth()->user()->id;
        $code = PartnersCode::where('id', $code_id)
            ->where('id_partner', $partnerId)
            ->first();

        if (!$code) {
            return response()->json(['message' => 'Partner code not found'], 404);
        }

        $existingFee = floatval($code->fee_percentage ?? 0);
        $existingReduction = floatval($code->reduction_percentage ?? 0);
        $sumExisting = $existingFee + $existingReduction;

        $newFeeValue = array_key_exists('fee_percentage', $validated) ? floatval($validated['fee_percentage']) : $existingFee;
        $newReductionValue = array_key_exists('reduction_percentage', $validated) ? floatval($validated['reduction_percentage']) : $existingReduction;
        $sumNew = $newFeeValue + $newReductionValue;

        if (abs($sumExisting - $sumNew) > 0.0001) {
            return response()->json([
                'message' => 'The sum of existing fee_percentage and reduction_percentage must remain the same as the new values.',
            ], 400);
        }

        if (array_key_exists('fee_percentage', $validated)) {
            $code->fee_percentage = $validated['fee_percentage'];
        }
        if (array_key_exists('reduction_percentage', $validated)) {
            $code->reduction_percentage = $validated['reduction_percentage'];
        }

        $code->save();

        return response()->json([
            'message' => 'Fees updated',
            'data' => $code,
        ], 200);
    }
}
