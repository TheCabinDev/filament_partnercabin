<?php

namespace App\Filament\Resources\PartnersCodes\Pages;

use App\Filament\Resources\PartnersCodes\PartnersCodeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPartnersCodes extends ListRecords
{
    protected static string $resource = PartnersCodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
