<?php
// filepath: c:\Users\ThinkPad X280\BE-Partnership\app\Filament\Resources\Users\Schemas\UserForm.php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Enter user name')
                    ->helperText('Masukkan nama admin yang akan dibuat'),

                TextInput::make('email')
                    ->label('Email Address')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->placeholder('user@example.com'),

                DateTimePicker::make('email_verified_at')
                    ->label('Email Verified At')
                    ->default(now())
                    ->disabled()
                    // ->dehydrated()
                    ->seconds(false)
                    ->native(false)
                    ->helperText('auto verified'),

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
                    ->helperText('password admin yang akan dibuat'),
            ])
            ->columns(2);
    }
}
