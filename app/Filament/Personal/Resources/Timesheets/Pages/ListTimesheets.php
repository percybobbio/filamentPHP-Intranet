<?php

namespace App\Filament\Personal\Resources\Timesheets\Pages;

use App\Filament\Personal\Resources\Timesheets\TimesheetResource;
use App\Models\Timesheet;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;
use Psy\Readline\Interactive\Input\KeyBindings;

class ListTimesheets extends ListRecords
{
    protected static string $resource = TimesheetResource::class;

    protected function getHeaderActions(): array
    {
        $lastTimesheet = Timesheet::where('user_id', Auth::user()->id)->orderByDesc('id')->first();

        if($lastTimesheet === null) {
            return[
                Action::make('inwork')
                ->label('Entrar a Trabajar')
                ->color('success')
                ->requiresConfirmation()
                ->keyBindings(
                    'ctrl+s',
                )
                ->action(function () {
                    // Logic to handle the "inwork" action
                    $user = Auth::user();
                    $timesheet = new Timesheet();
                    $timesheet->calendar_id = 1;
                    $timesheet->user_id = $user->id;
                    $timesheet->day_in = Carbon::now();
                    $timesheet->type = 'work';
                    $timesheet->save();
                }),
                CreateAction::make(),
            ];
            
        }

        return [
            Action::make('inwork')
            ->label('Entrar a Trabajar')
            ->color('success')
            ->visible(!$lastTimesheet->day_out == null)
            ->disabled($lastTimesheet->day_out == null) // Disable if the last timesheet has day_out as null
            ->requiresConfirmation()
            ->keyBindings(
                'ctrl+s',
            )
            ->action(function () {
                // Logic to handle the "inwork" action
                $user = Auth::user();
                $timesheet = new Timesheet();
                $timesheet->calendar_id = 1;
                $timesheet->user_id = $user->id;
                $timesheet->day_in = Carbon::now();
                $timesheet->type = 'work';
                $timesheet->save();

                Notification::make()
                        ->title('¡Bienvenido! Has iniciado tu jornada laboral. A chambear!!!')
                        ->success()
                        ->send();
            }),
            Action::make('inPause')
            ->label('Pausar Labores')
            ->color('info')
            ->requiresConfirmation()
            ->visible($lastTimesheet->day_out == null && $lastTimesheet->type !== 'pause')
            ->disabled(!$lastTimesheet->day_out == null)
            ->action(function() use($lastTimesheet) {
                // Logic to handle the "inPause" action
                //Primero cerramos el último registro de trabajo, recordar la funcion $lastTimesheet es la última fila de la tabla timesheets
                $lastTimesheet->day_out = Carbon::now();
                $lastTimesheet->save();

                //Segundo creamos un nuevo registro de pausa, donde el campo day_in toma el valor de la hora actual y el campo type toma el valor de 'pause'
                $timesheet = new Timesheet();
                $timesheet->calendar_id = 1;
                $timesheet->user_id = Auth::user()->id;
                $timesheet->day_in = Carbon::now();
                $timesheet->type = 'pause';
                $timesheet->save();

                Notification::make()
                        ->title('Has iniciado tu pausa laboral. A descansar un poco!!!')
                        ->icon('heroicon-o-pause')
                        ->color('warning')
                        ->success()
                        ->send();
            }),
            Action::make('stopPause')
            ->label('Reanudar Labores')
            ->color('info')
            ->visible($lastTimesheet->day_out == null && $lastTimesheet->type == 'pause')
            ->disabled(!$lastTimesheet->day_out == null)
            ->requiresConfirmation()
            ->action(function() use($lastTimesheet) {
                // Logic to handle the "stopPause" action
                //Primero cerramos el último registro de pausa, recordar la funcion $lastTimesheet es la última fila de la tabla timesheets
                $lastTimesheet->day_out = Carbon::now();
                $lastTimesheet->save();

                //Segundo creamos un nuevo registro de trabajo, donde el campo day_in toma el valor de la hora actual y el campo type toma el valor de 'pause'
                $timesheet = new Timesheet();
                $timesheet->calendar_id = 1;
                $timesheet->user_id = Auth::user()->id;
                $timesheet->day_in = Carbon::now();
                $timesheet->type = 'work';
                $timesheet->save();

                Notification::make()
                        ->title('Has iniciado tu jornada laboral. A chambear!!!')
                        ->icon('heroicon-o-play')
                        ->color('warning')
                        ->success()
                        ->send();
            }),
            Action::make('stopWork')
            ->label('Concluir jornada laboral')
            ->color('danger')
            ->requiresConfirmation()
            ->visible($lastTimesheet->day_out == null && $lastTimesheet->type !== 'pause')
            ->disabled(!$lastTimesheet->day_out == null)
            ->action(function() use($lastTimesheet) {
                // Logic to handle the "stopWork" action
                //cierra el dia de trabajo, al hacer esto, el campo day_out de la última fila de la tabla timesheets toma el valor de la hora actual
                $lastTimesheet->day_out = Carbon::now();
                $lastTimesheet->save();

                Notification::make()
                        ->title('Has concluido tu jornada laboral a las ' . Carbon::now()->format('H:i:s') . ' ¡Buen trabajo!')
                        ->icon('heroicon-o-check')
                        ->color('success')
                        ->success()
                        ->send();
            }),
            CreateAction::make()
        ];
    }
}
