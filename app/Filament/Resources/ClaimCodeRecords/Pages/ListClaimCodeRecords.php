<?php

namespace App\Filament\Resources\ClaimCodeRecords\Pages;

use App\Filament\Resources\ClaimCodeRecords\ClaimCodeRecordResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListClaimCodeRecords extends ListRecords
{
    protected static string $resource = ClaimCodeRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
