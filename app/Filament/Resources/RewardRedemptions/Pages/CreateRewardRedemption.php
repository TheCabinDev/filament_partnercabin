<?php

namespace App\Filament\Resources\RewardRedemptions\Pages;

use App\Filament\Resources\RewardRedemptions\RewardRedemptionResource;
use App\Jobs\SendRewardRedemptionEmail;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Mail;
use App\Mail\WithdrawSuccessNotification;

class CreateRewardRedemption extends CreateRecord
{
    protected static string $resource = RewardRedemptionResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // dd($data);
        return $data;
    }

    protected function afterCreate(): void
    {
        $record = $this->record;

        sendRewardRedemptionEmail::dispatch($record);

        \Filament\Notifications\Notification::make()
            ->title('Email Terkirim')
            ->success()
            ->send();
    }
}
