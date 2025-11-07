<?php

namespace App\Filament\Resources\PartnersCodes\Pages;

use App\Filament\Resources\PartnersCodes\PartnersCodeResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPartnersCode extends ViewRecord
{
    protected static string $resource = PartnersCodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
