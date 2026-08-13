<?php

namespace App\Filament\Resources\Timesheets\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TimesheetsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns(self::getAdminColumns())
            ->filters([
                //
                SelectFilter::make('type')
                ->options([
                    'work' => 'Working',
                    'pause' => 'In Pause',
                    'sick' => 'Sick',
                ])
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function configureForEmployee(Table $table): Table
    {
        return $table
            ->columns(self::getEmployeeColumns())
            ->filters([
                //
                SelectFilter::make('type')
                ->options([
                    'work' => 'Working',
                    'pause' => 'In Pause',
                    'sick' => 'Sick',
                ])
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    private static function getAdminColumns(): array
    {
        return [
            TextColumn::make('calendar.name')
                ->sortable()
                ->searchable(),
            TextColumn::make('user.name')
                ->sortable()
                ->searchable(),
            TextColumn::make('type')
                ->label('Tipo')
                ->searchable(),
            TextColumn::make('day_in')
                ->dateTime()
                ->sortable()
                ->searchable(),
            TextColumn::make('day_out')
                ->dateTime()
                ->sortable()
                ->searchable(),
            TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            TextColumn::make('updated_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }

    private static function getEmployeeColumns(): array
    {
        return [
            TextColumn::make('type')
                ->label('Tipo')
                ->searchable(),
            TextColumn::make('day_in')
                ->dateTime()
                ->sortable()
                ->searchable(),
            TextColumn::make('day_out')
                ->dateTime()
                ->sortable()
                ->searchable(),
            TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            TextColumn::make('updated_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }
}
