<?php

namespace App\Filament\Resources\PartnersCodes\Pages;

use App\Filament\Resources\PartnersCodes\PartnersCodeResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Mail;
use App\Mail\PartnerCodeAssigned;

class CreatePartnersCode extends CreateRecord
{
    protected static string $resource = PartnersCodeResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // dd($data);
        return $data;
    }

     protected function afterCreate(): void
    {
        Mail::to($this->record->partner->email)->send(
            new PartnerCodeAssigned($this->record, $this->record->partner)
        );
    }
}
