<?php

namespace App\Filament\Resources\ClaimCodeRecords\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;

class ClaimCodeRecordForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // TextInput::make('id')
                //     ->label('Claim ID')
                //     ->disabled()
                //     ->dehydrated()
                //     ->helperText('Auto-generated claim ID'),
                    // ->columnSpanFull(),
                Select::make('id_partner')
                    ->label('Partner')
                    ->relationship('partner', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->native(false),
                Select::make('id_code')
                    ->label('Partner Code')
                    ->required()
                    ->relationship('partnercode', 'unique_code')
                    ->searchable()
                    ->preload()
                    ->native(false),
                TextInput::make('reservation_id')
                    ->required(),
                TextInput::make('reservation_total_price')
                    ->required()
                    ->numeric(),
                TextInput::make('total_poin_earned')
                    ->required()
                    ->numeric(),
                Select::make('reservation_status')
                    ->label('Reservation Status')
                    ->options([
                        'SUCCESS' => 'SUCCESS',
                        'EXPIRED' => 'EXPIRED',
                    ])
                    ->required()
                    ->native(false)
                    ->default('SUCCESS'),
            ])
            ->columns(2);
    }
}
