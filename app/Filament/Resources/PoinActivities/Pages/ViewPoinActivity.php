<?php

namespace App\Filament\Resources\PoinActivities\Pages;

use App\Filament\Resources\PoinActivities\PoinActivityResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPoinActivity extends ViewRecord
{
    protected static string $resource = PoinActivityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
