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
                    ->columnSpanFull(),

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
                    ->columnSpanFull(),

                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->placeholder('partner@example.com'),

                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->revealable()
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $context): bool => $context === 'create')
                    ->maxLength(255)
                    ->helperText('Leave empty to keep current password when editing'),

                Select::make('creator_id')
                    ->label('Creator')
                    ->relationship('creator', 'name')
                    ->default(auth()->id())
                    ->required()
                    ->searchable()
                    ->preload()
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
