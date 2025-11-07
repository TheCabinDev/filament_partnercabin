<?php

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
                FileUpload::make('attachment')
                ->disk('public')
                ->directory('form-attachments')
                ->visibility('public'),

                TextInput::make('name')
                    ->label('Partner Name')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Enter partner name'),

                TextInput::make('email')
                    ->label('Email Address')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->placeholder('partner@example.com'),

                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->required(fn (string $context): bool => $context === 'create')
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->dehydrated(fn ($state) => filled($state))
                    ->minLength(8)
                    ->maxLength(255)
                    ->placeholder('Minimum 8 characters')
                    ->revealable()
                    ->helperText('Leave empty to keep current password when editing'),

                Select::make('status')
                    ->label('Status')
                    ->options([
                        'ACTIVE' => 'Active',
                        'INACTIVE' => 'Inactive',
                    ])
                    ->default('ACTIVE')
                    ->required()
                    ->native(false),

                Select::make('creator_id')
                    ->label('Created By')
                    ->relationship('creator', 'name')
                    ->default(fn () => auth()->id())
                    ->required()
                    ->disabled()
                    ->dehydrated()
                    ->visibleOn('create'),
            ]);
    }
}
