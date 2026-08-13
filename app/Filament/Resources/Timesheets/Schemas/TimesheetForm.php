<?php

namespace App\Filament\Resources\Timesheets\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class TimesheetForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema(self::getAdminFormFields());
    }

    public static function configureForEmployee(Schema $schema): Schema
    {
        return $schema->schema(self::getEmployeeFormFields());
    }

    private static function getBaseFields(): array
    {
        return [
            Select::make('calendar_id')
                ->relationship(name: 'calendar', titleAttribute: 'name')
                ->label('Calendario')
                ->searchable()
                ->preload()
                ->required(),
            Select::make('type')
                ->options([
                    'work' => 'Working',
                    'pause' => 'In Pause',
                    'sick' => 'Sick',
                ])
                ->label('Tipo')
                ->required()
                ->default('work'),
            DateTimePicker::make('day_in')
                ->required(),
            DateTimePicker::make('day_out')
                ->required(),
        ];
    }

    private static function getAdminOnlyFields(): array
    {
        return [
            Select::make('calendar_id')
                ->relationship(name: 'calendar', titleAttribute: 'name')
                ->label('Calendario')
                ->searchable()
                ->preload()
                ->required(),
            Select::make('user_id')
                ->relationship(name: 'user', titleAttribute: 'name')
                ->label('Usuario')
                ->searchable()
                ->preload()
                ->required(),
        ];
    }

    //Formulario Admin todos los campos
    private static function getAdminFormFields(): array{
        return array_merge(
            self::getAdminOnlyFields(),
            self::getBaseFields()
        );
    }

    //Formulario Empleado solo los campos base
    private static function getEmployeeFormFields(): array{
        return self::getBaseFields();
    }   
}
