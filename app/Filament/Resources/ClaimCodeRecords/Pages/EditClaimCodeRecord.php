<?php

namespace App\Filament\Resources\ClaimCodeRecords\Pages;

use App\Filament\Resources\ClaimCodeRecords\ClaimCodeRecordResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditClaimCodeRecord extends EditRecord
{
    protected static string $resource = ClaimCodeRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
