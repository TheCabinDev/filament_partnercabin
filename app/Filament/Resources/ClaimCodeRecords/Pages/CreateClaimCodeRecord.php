<?php

namespace App\Filament\Resources\ClaimCodeRecords\Pages;

use App\Filament\Resources\ClaimCodeRecords\ClaimCodeRecordResource;
use App\Models\PoinActivity;
use App\Models\PoinLedgers;
use Carbon\Carbon;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use function Illuminate\Log\log;

class CreateClaimCodeRecord extends CreateRecord
{
    protected static string $resource = ClaimCodeRecordResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $data;
    }
    protected function afterCreate(): void
    {
        $expDateMonth = intval(env('EXPIRED_POIN_IN_MONTH'));

        $res = $this->record;
        $claimCodeId = $res->id;
        $resvStatus = $res->reservation_status;
        
        Log::info('1st query ' . json_encode($res));
        $chk = Carbon::parse($res["created_at"])->format('Ymd');
        
        if ($resvStatus === 'SUCCESS') {
            try {
                DB::beginTransaction();

                $resPoinCreated = PoinActivity::create([
                    'reservation_id' => $res["reservation_id"],
                    'id_unique_code' => $res["id_code"],
                    'id_partner' => $res["id_partner"],
                    'type_activity' => 'EARN',          //EARN -> partner mendapat poin
                    'amount' => $res["total_poin_earned"],
                    'date_transaction' => Carbon::parse($res["created_at"])->format('Ymd')
                ]);

                Log::info('2nd query ' . json_encode($resPoinCreated));
                $resLedgerCreated = PoinLedgers::create([
                    'poin_activity_id' => $resPoinCreated->id,
                    'id_unique_code' => $res["id_code"],
                    'id_partner' => $res["id_partner"],
                    'initial_amount' => $res["total_poin_earned"],
                    'remaining_amount' => 0,
                    'earn_date' => Carbon::parse($res["created_at"])->format('Ymd'),
                    'expire_date' => Carbon::parse($res["created_at"])->addMonth($expDateMonth)->format('Ymd'),
                ]);
                Log::info('3rd query ' . json_encode($resLedgerCreated));
                DB::commit();
            } catch (\Throwable $th) {
                Log::warning('fail query ' . $th);

                // If any exception occurs during the operations, roll back the transaction
                DB::rollBack();
            }
        }



        // if status success : 
        //     insert to poin activity EARN, poin ledger 

    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
