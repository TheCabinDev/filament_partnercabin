<?php

namespace App\Filament\Resources\Partners\Pages;

use App\Filament\Resources\Partners\PartnersResource;
use App\Filament\Resources\Partners\Widgets\PartnerStatsOverview;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPartners extends ViewRecord
{
    protected static string $resource = PartnersResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            PartnerStatsOverview::class,
        ];
    }

    public function getWidgetData(): array  // ← public
    {
        return [
            'record' => $this->record,
        ];
    }
}
