<?php

namespace App\Observers;

use App\Models\RewardRedemption;
use App\Notifications\PartnerNotification;
use Illuminate\Support\Facades\Log;

class RewardRedemptionObserver
{
    /**
     * Handle the RewardRedemption "updated" event.
     */
    public function updated(RewardRedemption $rewardRedemption): void
    {
        // Cek apakah redemption_status berubah
        if ($rewardRedemption->isDirty('redemption_status')) {
            $oldStatus = $rewardRedemption->getOriginal('redemption_status');
            $newStatus = $rewardRedemption->redemption_status;

            Log::info('Withdrawal status changed', [
                'redemption_id' => $rewardRedemption->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus
            ]);

            try {
                if ($newStatus === 'PROCESSING') {
                    $rewardRedemption->partner->notify(new PartnerNotification(
                        'Withdrawal Being Processed',
                        'Your withdrawal request of Rp ' . number_format($rewardRedemption->cash_amount, 0, ',', '.') . ' is being processed by our team.',
                        '/withdraw-history',
                        'info'
                    ));
                } elseif ($newStatus === 'COMPLETED') {
                    $rewardRedemption->partner->notify(new PartnerNotification(
                        'Withdrawal Completed',
                        'Your withdrawal of Rp ' . number_format($rewardRedemption->cash_amount, 0, ',', '.') . ' has been transferred to your account.',
                        '/withdraw-history',
                        'success'
                    ));
                } elseif ($newStatus === 'REJECTED') {
                    $rewardRedemption->partner->notify(new PartnerNotification(
                        'Withdrawal Rejected',
                        'Your withdrawal request has been rejected. Please contact support for more information.',
                        '/withdraw-history',
                        'error'
                    ));
                }

                Log::info('Withdrawal notification sent successfully');
            } catch (\Exception $e) {
                Log::error('Withdrawal notification failed: ' . $e->getMessage());
            }
        }
    }
}
