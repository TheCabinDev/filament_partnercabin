<?php

namespace App\Filament\Resources\ClaimCodeRecords\Pages;

use App\Filament\Resources\ClaimCodeRecords\ClaimCodeRecordResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewClaimCodeRecord extends ViewRecord
{
    protected static string $resource = ClaimCodeRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
