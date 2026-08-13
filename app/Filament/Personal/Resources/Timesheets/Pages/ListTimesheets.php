<?php

namespace App\Filament\Personal\Resources\Timesheets\Pages;

use App\Filament\Personal\Resources\Timesheets\TimesheetResource;
use App\Models\Timesheet;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;
use Psy\Readline\Interactive\Input\KeyBindings;

class ListTimesheets extends ListRecords
{
    protected static string $resource = TimesheetResource::class;

    protected function getHeaderActions(): array
    {
        return [
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
                $timesheet->user_id = 2;
                $timesheet->day_in = Carbon::now();
                $timesheet->day_out = Carbon::now();
                $timesheet->type = 'work';
                $timesheet->save();
            }),
            Action::make('inPause')
            ->label('Pausar')
            ->color('info')
            ->requiresConfirmation(),
            CreateAction::make()
        ];
    }
}
