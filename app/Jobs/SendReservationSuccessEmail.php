<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReservationSuccessNotification;
use App\Models\Partners;
use App\Models\PartnersCode;

class SendReservationSuccessEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Partners $partner;
    protected PartnersCode $partnerCode;
    protected string $reservationId;
    protected float|int $totalPrice;
    protected int $earnedPoint;
    protected string $dateTransaction;

    /**
     * Create a new job instance.
     */
    public function __construct(
        Partners $partner,
        PartnersCode $partnerCode,
        string $reservationId,
        float|int $totalPrice,
        int $earnedPoint,
        string $dateTransaction
    ) {
        $this->partner = $partner;
        $this->partnerCode = $partnerCode;
        $this->reservationId = $reservationId;
        $this->totalPrice = $totalPrice;
        $this->earnedPoint = $earnedPoint;
        $this->dateTransaction = $dateTransaction;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // sleep(10);
        // \Log::info('Send Reservation Email Job Started' , [
        //     'email' =>$this->partner->email,
        // ]);
        Mail::to($this->partner->email)->send(
            new ReservationSuccessNotification(
                $this->partner,
                $this->partnerCode,
                $this->reservationId,
                $this->totalPrice,
                $this->earnedPoint,
                $this->dateTransaction
            )
        );
    }
}
