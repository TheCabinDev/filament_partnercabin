<?php

namespace App\Http\Controllers\Api\V1\Reservation;

use App\Http\Controllers\Controller;
use App\Models\ClaimCodeRecord;
use App\Models\PartnersCode;
use App\Models\PoinActivity;
use App\Models\PoinLedgers;
use App\Traits\CodeCheck;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReservationSuccessNotification;
use App\Jobs\SendReservationSuccessEmail;


class ReservationCodeController extends Controller
{
    use CodeCheck;

    public function codeDetail(Request $request)
    {
        $codeToCheck = $request->code;

        //trait to check code
        $res = $this->isCodeValid($codeToCheck);

        return $res;
    }

    public function useCodeAfterFinalStatus(Request $request)
    {
        $expDateMonth = intval(env('EXPIRED_POIN_IN_MONTH'));

        $codeToCheck = $request->code;
        $reservation_id = $request->reservation_id;
        $total_price = $request->total_price;
        $date_transaction = $request->date_transaction;
        $reservation_status = $request->reservation_status;

        //trait to check code
        $res = $this->isCodeValid($codeToCheck);

        if ($res->getStatusCode() === 200) {
            $codeDetail = PartnersCode::where('unique_code', $codeToCheck)
                ->first();
            try {
                DB::beginTransaction();

                //if a reservation is SUCCESS, then write to claimcoderecord, poinactivity and poinledger
                if ($reservation_status === 'PAID') {
                    $resStatus = 'SUCCESS';
                    //hitung poin earned
                    $earnedPoinCash = intval(floor($codeDetail->fee_percentage / 100 * $total_price));

                    $resClaimCodeCreated = ClaimCodeRecord::create([
                        'reservation_id' => $reservation_id,
                        'reservation_total_price' => $total_price,
                        'id_code' => $codeDetail->id,
                        'id_partner' => $codeDetail->id_partner,
                        'total_poin_earned' => $earnedPoinCash,
                        'reservation_status' => $resStatus
                    ]);

                    Log::info('CORERESERVATION|USECODE-1|' . json_encode($resClaimCodeCreated));
                    $resPoinCreated = PoinActivity::create([
                        'reservation_id' => $reservation_id,
                        'id_unique_code' => $codeDetail->id,
                        'id_partner' => $codeDetail->id_partner,
                        'type_activity' => 'EARN',          //EARN -> partner mendapat poin
                        'amount' => $earnedPoinCash,
                        'date_transaction' => Carbon::parse($date_transaction)->format('Ymd')
                    ]);

                    Log::info('CORERESERVATION|USECODE-2|' . json_encode($resPoinCreated));
                    $resLedgerCreated = PoinLedgers::create([
                        'poin_activity_id' => $resPoinCreated->id,
                        'id_unique_code' => $codeDetail->id,
                        'id_partner' => $codeDetail->id_partner,
                        'initial_amount' => $earnedPoinCash,
                        'remaining_amount' => 0,
                        'earn_date' => Carbon::parse($date_transaction)->format('Ymd'),
                        'expire_date' => Carbon::parse($date_transaction)->addMonth($expDateMonth)->format('Ymd'),
                    ]);
                    Log::info('CORERESERVATION|USECODE-3|' . json_encode($resLedgerCreated));

                    try {

                        /**
                         * Pengiriman email langsung dinonaktifkan
                         * Diganti dengan Queue Job agar proses email berjalan async
                         *
                         * Di edit oleh: Muhammad Bill Fedro Saputra
                         * Tanggal: 02-01-2026
                         */

                        // Mail::to($codeDetail->partner->email)->send(
                        //     new ReservationSuccessNotification(
                        //         $codeDetail->partner,
                        //         $codeDetail,
                        //         $reservation_id,
                        //         $total_price,
                        //         $earnedPoinCash,
                        //         $date_transaction
                        //     )

                        SendReservationSuccessEmail::dispatch(
                            $codeDetail->partner,
                            $codeDetail,
                            $reservation_id,
                            $total_price,
                            $earnedPoinCash,
                            $date_transaction
                        );
                        // Log::info('Reservation success email sent to partner: ' . $codeDetail->partner->email);
                        Log::info('Reservation success email queued for partner: ' . $codeDetail->partner->email);
                    } catch (\Exception $e) {
                        Log::error('Notification failed: ' . $e->getMessage());
                    }
                } else {

                    //if a reservation is EXPIRED, then write only for table claimcoderecord
                    $resClaimCodeCreated = ClaimCodeRecord::create([
                        'reservation_id' => $reservation_id,
                        'reservation_total_price' => $total_price,
                        'id_code' => $codeDetail->id,
                        'id_partner' => $codeDetail->id_partner,
                        'total_poin_earned' => 0, //hitung,
                        'reservation_status' => 'EXPIRED'

                    ]);
                    Log::info('CORERESERVATION|USECODE-FAILED|' . json_encode($resClaimCodeCreated));
                }

                DB::commit();
            } catch (\Throwable $th) {
                Log::warning('fail query ' . $th);

                // If any exception occurs during the operations, roll back the transaction
                DB::rollBack();
            }

            /**
             * Pengiriman notifikasi ke partner dinonaktifkan
             * Sudah tidak perlu karena notifikasi email sudah ada
             * Notifikasi akan di kirim melalui Queue Job
             * Di edit oleh: Muhammad Bill Fedro Saputra
             * Tanggal: 02-01-2026
             */

            // try {
            //     $partnerCode->partner->notify(new PartnerNotification(
            //         'Kode Mitra Digunakan',
            //         'Kode mitra "' . $codeDetail->unique_code . '" telah digunakan pada reservasi ID: ' . $reservation_id,
            //         'success'
            //     ));
            // } catch (\Exception $e) {
            //     Log::error('Notification failed: ' . $e->getMessage());
            // }
            return response()->json([
                'message' => 'success'
            ], 200);
        }

        return response('', 404);
    }

    public function useCodeFromHomepage(Request $request)
    {
        $codeToCheck = $request->code;
        Log::info('CORERESERVATION|USECODEFROMHOMEPAGE|' . $codeToCheck);
        return response()->noContent();
    }
}
