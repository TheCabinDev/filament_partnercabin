<?php

namespace App\Filament\Resources\ClaimCodeRecords\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ClaimCodeRecordInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('claim_id'),
                TextEntry::make('id_partner')
                    ->numeric(),
                TextEntry::make('id_code')
                    ->numeric(),
                TextEntry::make('reservation_id'),
                TextEntry::make('reservation_total_price')
                    ->numeric(),
                TextEntry::make('total_coin_earned')
                    ->numeric(),
                TextEntry::make('reservation_status'),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
