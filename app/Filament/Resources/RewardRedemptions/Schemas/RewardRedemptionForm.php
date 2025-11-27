<?php

namespace App\Filament\Resources\RewardRedemptions\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Schema;

class RewardRedemptionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Fieldset::make('Data Awal penarikan reward')
                    ->schema([
                        Select::make('id_partner')
                            ->label('Nama Partner/mitra')
                            ->relationship('partner', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->native(false),
                        Select::make('id_unique_code')
                            ->label('Partner Code')
                            ->required()
                            ->relationship('partnercode', 'unique_code')
                            ->searchable()
                            ->preload()
                            ->native(false),
                        Select::make('type_reward')
                            ->options([
                                'CASH' => 'CASH',
                                'VOUCHER_STAY' => 'VOUCHER_STAY',
                                'MERCHANDISE' => 'MERCHANDISE',
                            ])
                            ->native(false),
                    ]),

                Fieldset::make('Penarikan poin ke rekening tujuan')
                    ->schema([
                        TextInput::make('poin_to_redeem')
                            ->label('Amount poin to redeem')
                            ->numeric()
                            ->minValue(0)
                            ->step(0.01)
                            // ->prefix('Rp')
                            ->placeholder('e.g., 50000')
                            ->helperText('jumlah poin yang akan ditukarkan'),

                        TextInput::make('cash_amount')
                            ->label('Uang yang akan didapatkan')
                            ->numeric()
                            ->prefix('Rp')
                            ->placeholder('e.g., 50000')
                            ->helperText('jumlah uang yang akan didapatkan'),
                        Select::make('destination_bank')
                            ->options([
                                'BCA' => 'BCA',
                                'MANDIRI' => 'MANDIRI',
                            ])
                            ->native(false),

                        TextInput::make('account_number')
                            ->label('Nomor rekening')
                            ->placeholder('e.g., 50000'),
                        Select::make('redemption_status')
                            ->options([
                                'PENDING' => 'PENDING',
                                'PROCESSING' => 'PROCESSING',
                                'COMPLETED' => 'COMPLETED',
                                'REJECTED' => 'REJECTED',
                            ])
                            ->native(false),
                    ]),
            ])->columns(1);
    }
}
