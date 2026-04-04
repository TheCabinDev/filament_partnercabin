<?php

namespace App\Filament\Resources\ClaimCodeRecords\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Fieldset;

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
                Fieldset::make('Data Partner dan Kode Unik')
                    ->schema([
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
                        Select::make('reservation_status')
                            ->label('Reservation Status')
                            ->options([
                                'SUCCESS' => 'SUCCESS',
                                'EXPIRED' => 'EXPIRED',
                            ])
                            ->required()
                            ->native(false)
                            ->default('SUCCESS'),
                    ])->columns(3),

                Fieldset::make('Data Reservasi dan waktu check-in/out')
                    ->schema([
                        TextInput::make('reservation_id')
                            ->required(),
                        TextInput::make('reservation_total_price')
                            ->label('Total Harga Reservasi')
                            ->belowContent('nilai ini digunakan untuk perhitungan komisi. Nilai ini bukan harga yang dibayarkan tamu, tapi harga total reservasi sebelum potongan untuk tamu')
                            ->required()
                            ->numeric(),
                        DateTimePicker::make('check_in_time')
                            ->label('Waktu Check-in')
                            ->seconds(false),
                        DateTimePicker::make('check_out_time')
                            ->label('Waktu Check-out')
                            ->seconds(false),

                    ])
                    ->columns(2),
                Fieldset::make('Data Keuntungan dan reduksi transaksi')
                    ->schema([
                        TextInput::make('total_poin_earned')
                            ->label('Keuntungan Partner dari trx ini')
                            ->belowContent('nilai ini dihitung berdasarkan rate profit % dikalikan dengan Total Harga Reservasi')
                            ->required()
                            ->numeric()
                            ->columnspanFull(),
                        TextInput::make('rate_profit')
                            ->belowContent('nilai ini digunakan untuk menghitung keuntungan partner dari transaksi ini. Nilai ini dihitung berdasarkan rate profit % dikalikan dengan Total Harga Reservasi')
                            ->label('Rate profit %')
                            ->numeric(),
                        TextInput::make('rate_for_guest')
                            ->label('Rate potongan untuk tamu %')
                            ->belowContent('nilai ini digunakan untuk menghitung potongan harga untuk tamu. Nilai ini dihitung berdasarkan rate potongan untuk tamu % dikalikan dengan Total Harga Reservasi')
                            ->numeric(),
                    ])
                    ->columns(2),
            ])->columns(1);
    }
}
