<?php

namespace App\Filament\Resources\RewardRedemptions\RelationManagers;

use App\Filament\Resources\PartnersCodes\PartnersCodeResource;
use Filament\Actions\ViewAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class PartnerCodeRelationManager extends RelationManager
{
    protected static string $relationship = 'partnerCode';

    protected static ?string $relatedResource = PartnersCodeResource::class;

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
