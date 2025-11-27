<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\PoinActivity;
use App\Models\RewardRedemption;
use Illuminate\Http\Request;

class WithdrawMoneyController extends Controller
{
    public function moneyBalance(Request $request)
    {
        $partnerId = $request->user()->id;

        // get all remaining dana/poin from all kode
        $allCash = PoinActivity::where('id_partner', $partnerId)
            ->where('type_activity', 'EARN')->sum('amount');

        // get all withdraw transaction with status of COMPLETED, PENDING, PROCESSING 
        $allWithdrawNoRejected = RewardRedemption::where('id_partner', $partnerId)
            ->whereIn('redemption_status', ['COMPLETED', 'PENDING', 'PROCESSING'])->sum('cash_amount');

        $allBalance = intval($allCash) - intval($allWithdrawNoRejected);

        return response()->json([
            'success' => true,
            'data' => $allBalance,
        ], 200);
    }

    public function withdraw(Request $request)
    {
        $partnerId = $request->user()->id;
        $request->validate([
            'remaining_amount' => 'required|integer',
            'withdraw_amount' => 'required|integer',
        ]);
        // check remaining dana from history
        // if remaining dana == then OK 
        // if withdraw_dana < remaining dana then OK 


        $remainingMoneyToWithdraw = RewardRedemption::where('id_partner', $partnerId)
            ->whereIn('redemption_status', ['COMPLETED', 'PENDING', 'PROCESSING'])->sum('cash_amount');
       
        //jika dana yang tersedia dari request tidak sama dengan dana dari database maka gagal tarik
        if (intval($request->remaining_amount) !== intval($remainingMoneyToWithdraw)) {
            return response()->json([
                'success' => false,
                'message' => 'Penarikan dana gagal, data tidak valid',
            ], 403);
        }

        //jika dana yang akan ditarik kurang dari dana tersedia
        if (intval($remainingMoneyToWithdraw) < intval($request->withdraw_amount)) {
            return response()->json([
                'success' => false,
                'message' => 'Penarikan dana gagal, dana tidak cukup untuk ditarik',
            ], 403);
        }

        RewardRedemption::create([
            'id_partner' => $partnerId,
            'type_reward' => 'CASH',            //cash only for now
            'poin_to_redeem' =>  intval($request->withdraw_amount),
            'cash_amount' =>  intval($request->withdraw_amount),
            'redemption_status' => 'PENDING',
        ]);

        return response()->json(
            [
                'success' => true,
                'message' => 'Penarikan dana berhasil',
            ],
            200
        );
    }

    public function withdrawHistory(Request $request)
    {
        $partnerId = $request->user()->id;

        // get semua  withdraw baik success, pending dll 
        $allWithdraw = RewardRedemption::where('id_partner', $partnerId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $allWithdraw,
        ], 200);
    }
}
