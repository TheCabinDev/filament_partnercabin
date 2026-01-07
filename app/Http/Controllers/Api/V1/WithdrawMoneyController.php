<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\PoinActivity;
use App\Models\RewardRedemption;
use App\Notifications\PartnerNotification;
use App\Jobs\SendRewardRedemptionEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class WithdrawMoneyController extends Controller
{
    private function getValidWithdrawableBalance($partnerId)
    {
        // get all remaining dana/poin from all kode
        $allCash = PoinActivity::where('id_partner', $partnerId)
            ->where('type_activity', 'EARN')->sum('amount');

        // get all withdraw transaction with status of COMPLETED, PENDING, PROCESSING
        $allWithdrawNoRejected = RewardRedemption::where('id_partner', $partnerId)
            ->whereIn('redemption_status', ['COMPLETED', 'PENDING', 'PROCESSING'])->sum('cash_amount');

        $res = intval($allCash) - intval($allWithdrawNoRejected);

        return $res;
    }

    public function moneyBalance(Request $request)
    {
        $partnerId = $request->user()->id;

        // get all remaining dana/poin from all kode
        $allCash = PoinActivity::where('id_partner', $partnerId)
            ->where('type_activity', 'EARN')->sum('amount');

        $allWithdrawableBalance = $this->getValidWithdrawableBalance($partnerId);

        return response()->json([
            'success' => true,
            'data' => ([
                'all_money' => intval($allCash),
                'all_withdrawable_money' => $allWithdrawableBalance
            ])
        ], 200);
    }

    public function withdraw(Request $request)
    {
        $partner = $request->user();
        $partnerId = $request->user()->id;

        $request->validate([
            'remaining_amount' => 'required|integer',
            'withdraw_amount' => 'required|integer',
            // 'account_number' => 'nullable|string',
        ]);

        // Log::info('Account number from request: ' . ($request->account_number ?? 'NULL'));
        // Log::info('Partner bank account: ' . ($partner->bank_account ?? 'NULL'));

        Log::info('API|withdraw|parameter-' . $partnerId . '|' . $request->remaining_amount . "|" . $request->withdraw_amount);
        $allWithdrawableBalance = $this->getValidWithdrawableBalance($partnerId);

        //jika dana yang tersedia dari request tidak sama dengan dana dari database maka gagal tarik
        if (intval($request->remaining_amount) !== intval($allWithdrawableBalance)) {
            Log::info('API|withdraw|failed1-' . $partnerId . '|' . intval($request->remaining_amount) . "|" . intval($allWithdrawableBalance));
            return response()->json([
                'success' => false,
                'message' => 'Penarikan dana gagal, data tidak valid',
            ], 403);
        }

        //jika dana yang akan ditarik kurang dari dana tersedia
        if (intval($allWithdrawableBalance) < intval($request->withdraw_amount)) {
            Log::info('API|withdraw|failed2-' . $partnerId . '|' . intval($allWithdrawableBalance) . "|" . intval($request->withdraw_amount));

            return response()->json([
                'success' => false,
                'message' => 'Penarikan dana gagal, dana tidak cukup untuk ditarik',
            ], 403);
        }

        $accountNumber = $request->account_number
            ?? $partner->bank_account
            ?? 'Belum diisi';

        $redemption = RewardRedemption::create([
            'id_partner' => $partnerId,
            'type_reward' => 'CASH',            //cash only for now
            'poin_to_redeem' =>  intval($request->withdraw_amount),
            'cash_amount' =>  intval($request->withdraw_amount),
            'redemption_status' => 'PENDING',
            // 'account_number' => $accountNumber,
        ]);

        // Log::info('Saved account number: ' . $redemption->account_number);

        Log::info('API|withdraw|success-' . $partnerId);

        try {
            $partner->notify(new PartnerNotification(
                'Withdrawal Request Submitted',
                'Your withdrawal request of Rp ' . number_format($request->withdraw_amount, 0, ',', '.') . ' is being processed. Please wait for admin approval.',
                '/withdraw-history',
                'info'
            ));

            Log::info('Withdraw notification sent successfully');
        } catch (\Exception $e) {
            Log::error('Withdraw notification failed: ' . $e->getMessage());
        }

        try {
            SendRewardRedemptionEmail::dispatch($redemption);
            Log::info('Withdraw email queued for partner: ' . $partner->email);
        } catch (\Exception $e) {
            Log::error('Withdraw email failed: ' . $e->getMessage());
        }

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

        $allWithdraw = RewardRedemption::where('id_partner', $partnerId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $allWithdraw,
        ], 200);
    }
}
