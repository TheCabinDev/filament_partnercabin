<?php

namespace App\Filament\Resources\RewardRedemptions\RelationManagers;

use App\Filament\Resources\Partners\PartnersResource;
use Filament\Actions\ViewAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class PartnerRelationManager extends RelationManager
{
    protected static string $relationship = 'partner';

    protected static ?string $relatedResource = PartnersResource::class;

    public function canCreate(): bool
    {
        return false;
    }

    public function canEdit($record): bool
    {
        return false;
    }

    public function canDelete($record): bool
    {
        return false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([])
            ->recordActions([
                ViewAction::make(),
            ]);
    }
}
