<?php

namespace App\Filament\Resources\PoinActivities\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PoinActivityForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                TextInput::make('reservation_id')
                    ->label('reservation_id')
                    ->required(),

                Select::make('id_unique_code')
                    ->label('id code ')
                    ->relationship('partnercode', 'unique_code')
                    ->required()
                    ->native(false),

                Select::make('id_partner')
                    ->label('id partner ')
                    ->relationship('partner', 'name')
                    ->required(),

                Select::make('type_activity')
                    ->label('type_activity')
                    ->options([
                        'EARN' => 'EARN',
                        'USE' => 'USE',
                        'EXPIRE' => 'EXPIRE',
                    ])
                    ->default('EARN')
                    ->required()
                    ->native(false),

                TextInput::make('amount')
                    ->label('amount'),

                DateTimePicker::make('date_transaction')
                    ->label('Tanggal record')
                    ->required()
            ])
            ->columns(2);
    }
}
