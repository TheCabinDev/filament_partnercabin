<?php

namespace App\Filament\Resources\RewardRedemptions;

use App\Filament\Resources\RewardRedemptions\Pages\CreateRewardRedemption;
use App\Filament\Resources\RewardRedemptions\Pages\EditRewardRedemption;
use App\Filament\Resources\RewardRedemptions\Pages\ListRewardRedemptions;
use App\Filament\Resources\RewardRedemptions\Pages\ViewRewardRedemption;
use App\Filament\Resources\RewardRedemptions\Schemas\RewardRedemptionForm;
use App\Filament\Resources\RewardRedemptions\Schemas\RewardRedemptionInfolist;
use App\Filament\Resources\RewardRedemptions\Tables\RewardRedemptionsTable;
use App\Models\RewardRedemption;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class RewardRedemptionResource extends Resource
{
    protected static ?string $model = RewardRedemption::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $pluralModelLabel = 'Penarikan Poin';

    protected static ?string $recordTitleAttribute = 'Penarikan Poin';

    protected static ?string $navigationLabel = 'Penarikan Poin';
    protected static string | UnitEnum | null $navigationGroup = 'Poin Partner';
    
    public static function form(Schema $schema): Schema
    {
        return RewardRedemptionForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return RewardRedemptionInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RewardRedemptionsTable::configure($table);
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
            'index' => ListRewardRedemptions::route('/'),
            'create' => CreateRewardRedemption::route('/create'),
            'view' => ViewRewardRedemption::route('/{record}'),
            'edit' => EditRewardRedemption::route('/{record}/edit'),
        ];
    }
}
