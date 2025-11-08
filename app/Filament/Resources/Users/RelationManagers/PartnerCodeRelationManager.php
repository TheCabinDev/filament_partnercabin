<?php

namespace App\Filament\Resources\Users\RelationManagers;

use App\Filament\Resources\PartnersCodes\PartnersCodeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class PartnerCodeRelationManager extends RelationManager
{
    protected static string $relationship = 'partnerCodes';

    protected static ?string $relatedResource = PartnersCodeResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
