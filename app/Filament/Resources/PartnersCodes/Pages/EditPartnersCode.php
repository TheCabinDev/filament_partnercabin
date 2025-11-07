<?php

namespace App\Filament\Resources\PartnersCodes\Pages;

use App\Filament\Resources\PartnersCodes\PartnersCodeResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPartnersCode extends EditRecord
{
    protected static string $resource = PartnersCodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
