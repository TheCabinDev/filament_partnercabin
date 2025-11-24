<?php

namespace App\Filament\Resources\Partners\RelationManagers;

use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Facades\Filament;

class PartnerCodesRelationManager extends RelationManager
{
    protected static string $relationship = 'partnerCodes';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('unique_code')
                ->required()
                ->maxLength(255),

            Select::make('id_creator')
                ->relationship('user', 'name')
                ->searchable()
                ->preload()
                // ->default(auth()->id())
                ->required()
                ->label('Creator'),

            TextInput::make('fee_percentage')
                ->numeric()
                ->suffix('%')
                ->minValue(0)
                ->maxValue(100)
                ->label('Fee Percentage'),

            TextInput::make('amount_reduction')
                ->numeric()
                ->prefix('Rp')
                ->minValue(0)
                ->label('Amount Reduction'),

            TextInput::make('claim_quota')
                ->numeric()
                ->minValue(0)
                ->helperText('Leave empty for unlimited')
                ->label('Claim Quota'),

            TextInput::make('max_claim_per_account')
                ->numeric()
                ->default(1)
                ->minValue(1)
                ->required()
                ->label('Max Claim Per Account'),

            DateTimePicker::make('use_started_at')
                ->label('Start Date')
                ->native(false)
                ->default(now())
                ->required(),

            DateTimePicker::make('use_expired_at')
                ->label('End Date')
                ->after('use_started_at')
                ->native(false),

            Select::make('status')
                ->options([
                    'ACTIVE' => 'Active',
                    'INACTIVE' => 'Inactive',
                ])
                ->default('ACTIVE')
                ->required(),
        ])
            ->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('unique_code')
            ->columns([
                TextColumn::make('unique_code')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Code copied!')
                    ->copyMessageDuration(1500)
                    ->sortable()
                    ->label('Code'),

                TextColumn::make('user.name')
                    ->searchable()
                    ->sortable()
                    ->default('N/A')
                    ->label('Creator'),

                TextColumn::make('fee_percentage')
                    ->suffix('%')
                    ->sortable()
                    ->label('Fee %'),

                TextColumn::make('amount_reduction')
                    ->money('IDR')
                    ->sortable()
                    ->label('Reduction'),

                TextColumn::make('claim_quota')
                    ->default('Unlimited')
                    ->sortable()
                    ->label('Quota'),

                // disable 24112025
                // TextColumn::make('claimRecords_count')
                //     ->counts('claimRecords')
                //     ->label('Claims')
                //     ->sortable()
                //     ->badge()
                //     ->color('success')
                //     ->default(0),

                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'success' => 'ACTIVE',
                        'danger' => 'INACTIVE',
                    ]),

                TextColumn::make('use_expired_at')
                    ->label('Expires')
                    ->date('M d, Y')
                    ->sortable(),
            ])

            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'ACTIVE' => 'Active',
                        'INACTIVE' => 'Inactive',
                    ]),
            ])
            ->headerActions([
                CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['id_creator'] = Filament::auth()->id() ?? auth()->id();
                        $data['id_partner'] = $this->ownerRecord->id; // ← perbaikan di sini
                        return $data;
                    }),
            ])
            ->recordActions([
                EditAction::make(),
                // DissociateAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    // DissociateBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
