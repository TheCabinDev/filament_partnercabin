<?php

namespace App\Filament\Resources\PartnersCodes;

use App\Filament\Resources\PartnersCodes\Pages\CreatePartnersCode;
use App\Filament\Resources\PartnersCodes\Pages\EditPartnersCode;
use App\Filament\Resources\PartnersCodes\Pages\ListPartnersCodes;
use App\Filament\Resources\PartnersCodes\Pages\ViewPartnersCode;
use App\Filament\Resources\PartnersCodes\Schemas\PartnersCodeForm;
use App\Filament\Resources\PartnersCodes\Schemas\PartnersCodeInfolist;
use App\Filament\Resources\PartnersCodes\Tables\PartnersCodesTable;
use App\Models\PartnersCode;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PartnersCodeResource extends Resource
{
    protected static ?string $model = PartnersCode::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $pluralModelLabel = 'Kode Unik Partner';

    protected static ?string $recordTitleAttribute = 'Partner/mitra';

    protected static ?string $navigationLabel = 'Kode Unik Partner';
    protected static string | UnitEnum | null $navigationGroup = 'Partner/mitra';

    public static function form(Schema $schema): Schema
    {
        return PartnersCodeForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PartnersCodeInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PartnersCodesTable::configure($table);
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
            'index' => ListPartnersCodes::route('/'),
            'create' => CreatePartnersCode::route('/create'),
            'view' => ViewPartnersCode::route('/{record}'),
            'edit' => EditPartnersCode::route('/{record}/edit'),
        ];
    }
}
