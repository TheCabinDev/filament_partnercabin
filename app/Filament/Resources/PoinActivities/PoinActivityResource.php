<?php

namespace App\Filament\Resources\PoinActivities;

use App\Filament\Resources\PoinActivities\Pages\CreatePoinActivity;
use App\Filament\Resources\PoinActivities\Pages\EditPoinActivity;
use App\Filament\Resources\PoinActivities\Pages\ListPoinActivities;
use App\Filament\Resources\PoinActivities\Pages\ViewPoinActivity;
use App\Filament\Resources\PoinActivities\Schemas\PoinActivityForm;
use App\Filament\Resources\PoinActivities\Schemas\PoinActivityInfolist;
use App\Filament\Resources\PoinActivities\Tables\PoinActivitiesTable;
use App\Models\PoinActivity;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PoinActivityResource extends Resource
{
    protected static ?string $model = PoinActivity::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Poin Activity';

    public static function form(Schema $schema): Schema
    {
        return PoinActivityForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PoinActivityInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PoinActivitiesTable::configure($table);
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
            'index' => ListPoinActivities::route('/'),
            'create' => CreatePoinActivity::route('/create'),
            'view' => ViewPoinActivity::route('/{record}'),
            'edit' => EditPoinActivity::route('/{record}/edit'),
        ];
    }
}
