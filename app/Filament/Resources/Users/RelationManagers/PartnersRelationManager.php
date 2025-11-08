<?php

namespace App\Filament\Resources\Users\RelationManagers;

use App\Filament\Resources\Partners\PartnersResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class PartnersRelationManager extends RelationManager
{
    protected static string $relationship = 'partners';

    protected static ?string $relatedResource = PartnersResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
