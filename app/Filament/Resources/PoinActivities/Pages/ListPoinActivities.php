<?php

namespace App\Filament\Resources\PoinActivities\Pages;

use App\Filament\Resources\PoinActivities\PoinActivityResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPoinActivities extends ListRecords
{
    protected static string $resource = PoinActivityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
