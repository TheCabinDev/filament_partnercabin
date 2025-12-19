<?php

namespace App\Traits;

use App\Models\PartnersCode;
use Carbon\Carbon;

trait CodeCheck
{
    public function isCodeValid($codeToCheck)
    {
        $codeDetail = PartnersCode::where('unique_code', $codeToCheck)
            ->first();

        //if code is null or not exist
        if (!$codeDetail) {
            return response('', 404);
        }

        $codeStatus = $codeDetail['status'];
        $maxClaimQuota = $codeDetail['claim_quota'];
        $useCodeStarted = Carbon::parse($codeDetail['use_started_at']);
        $useCodeExpired = Carbon::parse($codeDetail['use_expired_at']);

        $nowdatetime = Carbon::now();

        if ($codeStatus === 'INACTIVE') {
            return response()->json([
                'message' => 'code is inactive'
            ], 403);
        }

        if ($maxClaimQuota <= 0) {
            return response()->json([
                'message' => 'max claim code is 0'
            ], 403);
        }
        // dd($useCodeStarted);
        if ($nowdatetime->lessThan($useCodeStarted)) {
            return response()->json([
                'message' => 'The code cannot be accessed because the time has not started yet.'
            ], 403);
        }

        if ($nowdatetime->greaterThan($useCodeExpired)) {
            return response()->json([
                'message' => 'The code has expired.'
            ], 403);
        }

        return response()->json([
            'data' => [
                'unique_code' => $codeDetail->unique_code,
                'status' => $codeDetail->status,
                'reduction_percentage' => $codeDetail->reduction_percentage,
                'amount_fee' => $codeDetail->fee_percentage
            ]
        ], 200);
    }
}
