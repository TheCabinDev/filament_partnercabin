<?php

namespace App\Filament\Resources\PartnersCodes\Pages;

use App\Filament\Resources\PartnersCodes\PartnersCodeResource;
use App\Filament\Widgets\PartnerCodeOverview;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPartnersCode extends ViewRecord
{
    protected static string $resource = PartnersCodeResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            // Return an array of widgets to display
            // StockSparepartStatsOverview::class,
            PartnerCodeOverview::class
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
