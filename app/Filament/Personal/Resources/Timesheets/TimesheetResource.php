<?php

namespace App\Filament\Personal\Resources\Timesheets;

use App\Filament\Personal\Resources\Timesheets\Pages\CreateTimesheet;
use App\Filament\Personal\Resources\Timesheets\Pages\EditTimesheet;
use App\Filament\Personal\Resources\Timesheets\Pages\ListTimesheets;
use App\Filament\Resources\Timesheets\Schemas\TimesheetForm;
use App\Filament\Resources\Timesheets\Tables\TimesheetsTable;
use App\Models\Timesheet;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class TimesheetResource extends Resource
{
    protected static ?string $model = Timesheet::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::TableCells;

    protected static ?string $recordTitleAttribute = 'calendar_id';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_id', Auth::user()->id);
    }

    public static function form(Schema $schema): Schema
    {
        return TimesheetForm::configureForEmployee($schema->schema([]));
    }

    public static function table(Table $table): Table
    {
        return TimesheetsTable::configureForEmployee($table);
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
            'index' => ListTimesheets::route('/'),
            'create' => CreateTimesheet::route('/create'),
            'edit' => EditTimesheet::route('/{record}/edit'),
        ];
    }
}
