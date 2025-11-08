<?php

namespace App\Filament\Resources\PartnersCodes\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PartnersCodeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('id_partner')
                    ->label('Partner')
                    ->relationship('partner', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->native(false)
                    ->columnSpanFull(),

                Select::make('id_creator')
                    ->label('Creator')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->default(fn () => auth()->id())
                    ->required()
                    ->disabled()
                    ->dehydrated()
                    ->native(false)
                    ->columnSpanFull(),

                TextInput::make('unique_code')
                    ->label('Unique Code')
                    ->required()
                    ->maxLength(50)
                    ->unique(ignoreRecord: true)
                    ->default(fn () => strtoupper(Str::random(50)))
                    // ->helperText('Auto-generated unique code')
                    // ->placeholder('AUTO-GENERATED')
                    ->columnSpanFull(),

                TextInput::make('fee_percentage')
                    ->label('Fee Percentage (%)')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100)
                    ->step(0.01)
                    ->suffix('%')
                    ->nullable()
                    ->placeholder('e.g., 10.5')
                    ->helperText('Set either fee percentage OR amount reduction'),

                TextInput::make('amount_reduction')
                    ->label('Amount Reduction')
                    ->numeric()
                    ->minValue(0)
                    ->step(0.01)
                    ->prefix('Rp')
                    ->nullable()
                    ->placeholder('e.g., 50000')
                    ->helperText('Set either fee percentage OR amount reduction'),

                TextInput::make('claim_quota')
                    ->label('Total Claim Quota')
                    ->numeric()
                    ->minValue(1)
                    ->nullable()
                    ->helperText('Leave empty for unlimited')
                    ->placeholder('e.g., 100'),

                TextInput::make('max_claim_per_account')
                    ->label('Max Claim Per Account')
                    ->numeric()
                    ->minValue(1)
                    ->nullable()
                    ->helperText('Leave empty for unlimited')
                    ->placeholder('e.g., 1'),

                DateTimePicker::make('use_started_at')
                    ->label('Start Date')
                    ->nullable()
                    ->default(now())
                    ->seconds(false)
                    ->native(false),

                DateTimePicker::make('use_expired_at')
                    ->label('Expiry Date')
                    ->nullable()
                    ->after('use_started_at')
                    ->seconds(false)
                    ->native(false),

                Select::make('status')
                    ->label('Status')
                    ->options([
                        'ACTIVE' => 'Active',
                        'INACTIVE' => 'Inactive',
                    ])
                    ->default('ACTIVE')
                    ->required()
                    ->native(false),
            ])
            ->columns(2);
    }
}
