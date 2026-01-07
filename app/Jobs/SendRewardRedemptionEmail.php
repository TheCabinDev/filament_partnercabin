<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\WithdrawSuccessNotification;

class SendRewardRedemptionEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $redemption;

    /**
     * Create a new job instance.
     */
    public function __construct($redemption)
    {
        $this->redemption = $redemption;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->redemption->partner->email)->send(
            new WithdrawSuccessNotification(
                $this->redemption->partner,
                $this->redemption->cash_amount,
                'Bank Transfer',
                // $this->redemption->account_number ?? '-',
                $this->redemption->created_at->format('d M Y H:i')
            )
        );
    }
}
