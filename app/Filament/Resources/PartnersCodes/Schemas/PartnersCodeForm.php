<?php

namespace App\Filament\Resources\PartnersCodes\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Builder;

class PartnersCodeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('id_partner')
                    ->label('Nama Partner/mitra')
                    ->relationship(
                        name: 'partner',
                        titleAttribute: 'name',
                        // Tambahkan callback di bawah ini untuk memfilter status
                        // modifyQueryUsing: fn(Builder $query) => $query->where('status', 'ACTIVE')
                        modifyQueryUsing: fn(Builder $query) => $query->where('status', 'ACTIVE')
                    )
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
                    ->default(fn() => auth()->id())
                    ->required()
                    ->disabled()
                    ->dehydrated()
                    ->native(false)
                    ->columnSpanFull(),

                // TextInput::make('unique_code')
                //     ->label('Unique Code')
                //     ->required()
                //     ->maxLength(50)
                //     ->unique(ignoreRecord: true)
                //     ->default(fn () => strtoupper(Str::random(10)))
                //     // ->helperText('Auto-generated unique code')
                //     // ->placeholder('AUTO-GENERATED')
                //     ->columnSpanFull()
                //     ->helperText('Kode unik untuk klaim reservasi')
                //     ->validationMessages([
                //         'unique' => 'Kode ini sudah ada. Silahkan gunakan kode lain.',
                //     ]),

                TextInput::make('unique_code')
                    ->label('Unique Code')
                    ->required()
                    ->maxLength(50)
                    ->minLength(3)
                    ->unique(ignoreRecord: true)

                    ->regex('/^[A-Z0-9]+$/i')

                    ->extraInputAttributes([
                        'style' => 'text-transform: uppercase',
                    ])

                    ->columnSpanFull()

                    ->validationMessages([
                        'required' => 'Kode unik wajib diisi.',
                        'unique' => 'Kode ini sudah ada. Silakan gunakan kode lain.',   //not working
                        'regex' => 'Kode hanya boleh berisi huruf kapital (A–Z) dan angka (0–9).',
                        'max' => 'Kode maksimal 50 karakter.',
                        'min' => 'Kode minimal 3 karakter.',
                    ])

                    // BACKEND: paksa kapital sebelum disimpan
                    ->dehydrateStateUsing(fn($state) => strtoupper($state))

                    // FORM: tampilkan selalu kapital
                    ->formatStateUsing(fn($state) => strtoupper($state)),


                TextInput::make('fee_percentage')
                    ->label('Fee (%)')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(20)
                    ->step(0.01)
                    ->suffix('%')
                    ->nullable()
                    ->placeholder('e.g., 10.5')
                    ->helperText('keuntungan yang diperoleh partner jika kode ini sukses digunakan untuk reservasi. maksimal 20%'),

                TextInput::make('reduction_percentage')
                    ->label('Diskon (%)')
                    ->numeric()
                    ->suffix('%')
                    ->minValue(0)
                    ->maxValue(20)
                    ->helperText('maksimal diskon 20%'),

                TextInput::make('claim_quota')
                    ->label('Total Claim Quota')
                    ->numeric()
                    ->minValue(1)
                    ->nullable()
                    ->helperText('kuota kode ini dapat diklaim menjadi reservasi sukses')
                    ->placeholder('e.g., 100'),

                TextInput::make('max_claim_per_account')
                    ->label('Max Claim Per Account')
                    ->numeric()
                    ->minValue(1)
                    ->nullable()
                    ->helperText('kuota maksimal 1 email user dapat melakukan klaim dengan kode ini')
                    ->placeholder('e.g., 1'),

                DateTimePicker::make('use_started_at')
                    ->label('Start Date')
                    ->nullable()
                    ->default(now())
                    ->seconds(false)
                    ->native(false)
                    ->helperText('tanggal kode dapat digunakan/diklaim'),

                DateTimePicker::make('use_expired_at')
                    ->label('Expiry Date')
                    ->nullable()
                    ->after('use_started_at')
                    ->seconds(false)
                    ->native(false)
                    ->helperText('tanggal kode tidak dapat digunakan/diklaim (expired)'),

                Select::make('status')
                    ->label('Status')
                    ->options([
                        'ACTIVE' => 'Active',
                        'INACTIVE' => 'Inactive',
                    ])
                    ->default('INACTIVE')
                    ->required()
                    ->native(false),
            ])
            ->columns(2);
    }
}
