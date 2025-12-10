<?php
// filepath: c:\Users\ThinkPad X280\BE-Partnership\app\Filament\Resources\Partners\Schemas\PartnersForm.php

namespace App\Filament\Resources\Partners\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class PartnersForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('e.g., John Doe')
                    ->columnSpanFull()
                    ->helperText('Nama Partner/mitra'),

                TextInput::make('phone')
                    ->tel()
                    ->required()
                    ->maxLength(20)
                    ->unique(ignoreRecord: true)
                    ->placeholder('08123456789')
                    ->label('Phone Number'),

                FileUpload::make('image_profile')
                    ->label('Profile Image')
                    ->image()
                    ->disk('public')
                    ->directory('partners/profiles')
                    ->visibility('public')
                    ->imageEditor()
                    ->imageEditorAspectRatios([
                        '1:1',
                        '4:3',
                        '16:9',
                    ])
                    ->maxSize(2048)
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->nullable()
                    ->columnSpanFull()
                    ->helperText('Foto partner/mitra '),

                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->placeholder('partner@example.com')
                    ->helperText('Email yang digunakkan partner untuk login ke aplikasi partnership (partnership.thecabinhotelgroup.com) '),

                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->revealable()
                    ->dehydrateStateUsing(fn($state) => Hash::make($state))
                    ->dehydrated(fn($state) => filled($state))
                    ->required(fn(string $context): bool => $context === 'create')
                    ->maxLength(255)
                    ->helperText('Password default untuk login partner'),

                Select::make('creator_id')
                    ->label('Creator')
                    ->relationship('creator', 'name')
                    ->default(auth()->id())
                    ->required()
                    ->searchable()
                    ->preload()
                    ->native(false),

                //bank account to transfer
                Select::make('destination_bank')
                    ->options([
                        'BCA' => 'BCA',
                        'MANDIRI' => 'MANDIRI',
                    ])
                    ->native(false),

                TextInput::make('account_number')
                    ->label('Nomor rekening')
                    ->placeholder('e.g., 50000'),

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
