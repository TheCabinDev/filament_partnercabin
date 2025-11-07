<?php

namespace App\Filament\Resources\Partners;

use App\Filament\Resources\Partners\Pages\CreatePartners;
use App\Filament\Resources\Partners\Pages\EditPartners;
use App\Filament\Resources\Partners\Pages\ListPartners;
use App\Filament\Resources\Partners\Pages\ViewPartners;
use App\Filament\Resources\Partners\Schemas\PartnersForm;
use App\Filament\Resources\Partners\Schemas\PartnersInfolist;
use App\Filament\Resources\Partners\Tables\PartnersTable;
use App\Models\Partners;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class PartnersResource extends Resource
{
    protected static ?string $model = Partners::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationLabel = 'Partners';

    protected static ?string $modelLabel = 'Partner';

    protected static ?string $pluralModelLabel = 'Partners';

    public static function form(Schema $schema): Schema
    {
        return PartnersForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PartnersInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PartnersTable::configure($table);
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
            'index' => ListPartners::route('/'),
            'create' => CreatePartners::route('/create'),
            'view' => ViewPartners::route('/{record}'),
            'edit' => EditPartners::route('/{record}/edit'),
        ];
    }
}
