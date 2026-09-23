<?php

namespace App\Filament\Resources\Agents\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AgentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Agent')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('service.nom')
                    ->label('Service')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('statut')
                    ->label('Disponibilité')
                    ->badge()
                    ->searchable(),
                TextColumn::make('rating')->icon('heroicon-o-star')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('is_badges')
                    ->label('Badge')
                    ->boolean(),
                TextColumn::make('recommendedBy.name')->label('Assigné à')->searchable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
