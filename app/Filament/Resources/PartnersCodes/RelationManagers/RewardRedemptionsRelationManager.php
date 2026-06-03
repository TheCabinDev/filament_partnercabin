<?php

namespace App\Filament\Resources\PartnersCodes\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RewardRedemptionsRelationManager extends RelationManager
{
    protected static string $relationship = 'rewardRedemptions';

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

    public static function canViewForRecord($ownerRecord, string $pageClass): bool
    {
        return str_ends_with($pageClass, '\\ViewPartnersCode');
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('raw_amount_to_redeem')
                ->numeric()
                ->required(),
            TextInput::make('cash_amount')
                ->numeric()
                ->required(),
            Select::make('redemption_status')
                ->options([
                    'PENDING' => 'PENDING',
                    'PROCESSING' => 'PROCESSING',
                    'COMPLETED' => 'COMPLETED',
                    'REJECTED' => 'REJECTED',
                ])
                ->required(),
        ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('raw_amount_to_redeem')
                    ->sortable(),
                TextColumn::make('cash_amount')
                    ->sortable(),
                TextColumn::make('redemption_status')
                    ->badge(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->headerActions([])
            ->recordActions([]);
    }
}
