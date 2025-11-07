<?php

namespace App\Filament\Resources\ClaimCodeRecords;

use App\Filament\Resources\ClaimCodeRecords\Pages\CreateClaimCodeRecord;
use App\Filament\Resources\ClaimCodeRecords\Pages\EditClaimCodeRecord;
use App\Filament\Resources\ClaimCodeRecords\Pages\ListClaimCodeRecords;
use App\Filament\Resources\ClaimCodeRecords\Pages\ViewClaimCodeRecord;
use App\Filament\Resources\ClaimCodeRecords\Schemas\ClaimCodeRecordForm;
use App\Filament\Resources\ClaimCodeRecords\Schemas\ClaimCodeRecordInfolist;
use App\Filament\Resources\ClaimCodeRecords\Tables\ClaimCodeRecordsTable;
use App\Models\ClaimCodeRecord;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ClaimCodeRecordResource extends Resource
{
    protected static ?string $model = ClaimCodeRecord::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'claim_id';

    public static function form(Schema $schema): Schema
    {
        return ClaimCodeRecordForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ClaimCodeRecordInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ClaimCodeRecordsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListClaimCodeRecords::route('/'),
            'create' => CreateClaimCodeRecord::route('/create'),
            'view' => ViewClaimCodeRecord::route('/{record}'),
            'edit' => EditClaimCodeRecord::route('/{record}/edit'),
        ];
    }
}
