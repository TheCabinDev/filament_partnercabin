<?php

namespace App\Filament\Resources\RewardRedemptions\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
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

                        Select::make('type_reward')
                            ->options([
                                'CASH' => 'CASH',
                                // 'VOUCHER_STAY' => 'VOUCHER_STAY',
                                // 'MERCHANDISE' => 'MERCHANDISE',
                            ])
                            ->native(false),
                    ]),

                Fieldset::make('Pencairan dana ke rekening tujuan')
                    ->schema([
                        TextInput::make('raw_amount_to_redeem')
                            ->label('Nominal Pencarian dana total')
                            ->numeric()
                            ->minValue(0)
                            ->step(0.01)
                            // ->prefix('Rp')
                            ->placeholder('e.g., 50000')
                            ->helperText('samakan dengan jumlah "Nominal Pencarian Dana" + "admin fee"'),

                        TextInput::make('cash_amount')
                            ->label('Nominal Pencarian Dana')
                            ->numeric()
                            ->prefix('Rp')
                            ->placeholder('e.g., 50000')
                            ->helperText('jumlah dana yang akan didapatkan partner setelah dikurangi admin fee'),

                        TextInput::make('admin_fee_amount')
                            ->label('Nominal Fee Admin')
                            ->numeric()
                            ->prefix('Rp')
                            ->placeholder('e.g., 50000')
                            ->helperText('jumlah keseluruhan Admin Fee (admin fee, bank tf fee dll)'),

                    ]),
                fieldset::make('Status Penarikan dan Bukti')
                    ->schema([
                        DateTimePicker::make('request_date')
                            ->label('Waktu Request Penarikan Dana')
                            ->seconds(false),
                        DateTimePicker::make('settlement_date')
                            ->label('Waktu Penarikan Dana Disetujui/ditransfer')
                            ->after('request_date')
                            ->seconds(false),

                        Textarea::make('settlement_notes')
                            ->label('Catatan Penarikan Dana')
                            ->placeholder('e.g., Penarikan dana untuk bulan April 2026')
                            ->rows(3)
                            ->columnspanfull(),


                        FileUpload::make('settlement_proof_image')
                            ->label('Bukti Penarikan Dana (png, jpg, webp)')
                            ->image()
                            ->disk('public')
                            ->directory('partners/settlement_proofs')
                            ->visibility('public')
                            ->openable(true)
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '1:1',
                                '4:3',
                                '16:9',
                            ])
                            ->maxSize(2048)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->nullable()
                            ->helperText('Lampirkan bukti penarikan dana, seperti tangkapan layar transfer bank atau bukti pembayaran lainnya.'),

                        FileUpload::make('pdf_reward_trx')
                            ->label('History Transaksi Penarikan Dana (PDF)')
                            ->acceptedFileTypes(['application/pdf'])
                            ->disk('public')
                            ->directory('partners/pdf_trx_history')
                            ->visibility('public')
                            ->openable(true)

                            ->maxSize(2048)
                            ->acceptedFileTypes(['application/pdf'])
                            ->nullable()
                            ->helperText('Lampirkan history transaksi penarikan dana dalam format PDF.'),

                        Select::make('redemption_status')
                            ->options([
                                'PENDING' => 'PENDING',
                                'PROCESSING' => 'PROCESSING',
                                'COMPLETED' => 'COMPLETED',
                                'REJECTED' => 'REJECTED',
                            ])
                            ->native(false)
                            ->required(),
                    ])

            ])->columns(1);
    }
}
