<?php

namespace App\Filament\Resources\PoinActivities\Pages;

use App\Filament\Resources\PoinActivities\PoinActivityResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPoinActivity extends EditRecord
{
    protected static string $resource = PoinActivityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
