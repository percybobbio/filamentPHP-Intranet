<?php

namespace App\Filament\Personal\Widgets;

use App\Models\Holiday;
use App\Models\Timesheet;
use App\Models\User;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class PersonalWidgetsStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            //
            Stat::make('Pending holidays', $this->getPendingHoliday(Auth::user())),
            Stat::make('Approved holidays', $this->getApprovedHoliday(Auth::user())),
            Stat::make('Total Work', $this->getTotalWork(Auth::user())),
            Stat::make('Total Pause', $this->getTotalPause(Auth::user())),
        ];
    }

    protected function getPendingHoliday(User $user): int
    {
       $totalPendingHolidays = Holiday::where('user_id', $user->id)
            ->where('type', 'pending')
            ->count();

        return $totalPendingHolidays;
    }

    protected function getApprovedHoliday(User $user): int
    {
       $totalApprovedHolidays = Holiday::where('user_id', $user->id)
            ->where('type', 'approved')
            ->count();

        return $totalApprovedHolidays;
    }

    protected function getTotalWork(User $user)
    {
        // Implement the logic to calculate the total work hours for the user
        $timesheets = Timesheet::where('user_id', $user->id)
            ->where('type', 'work')
            ->whereNotNull('day_in') // Ensure that day_in is not null
            ->whereNotNull('day_out') // Ensure that day_out is not null
            ->get();

            //dd($timesheets); // Debugging line to inspect the retrieved timesheets

        $totalWorkHours = 0;
        foreach ($timesheets as $timesheet) {
            // Assuming you have a 'hours' field in your Timesheet model
            $startTime = Carbon::parse($timesheet->day_in);
            $finishTime = Carbon::parse($timesheet->day_out);
            $totalDuration = $finishTime->diffInSeconds($startTime, true);
            //\Log::info("Timesheet {$timesheet->id}: {$startTime} - {$finishTime} = {$totalDuration} segundos");
            $totalWorkHours += $totalDuration;
        }

        $timeFormatted = gmdate('H:i:s', $totalWorkHours); // Format the total work hours as H:i:s
        Notification::make()
                ->title("Has trabajado un total de {$timeFormatted} horas.")
                ->success()
                ->send();

        return $timeFormatted ?? 0; // Return 0 if no timesheets found
    }

    protected function getTotalPause(User $user)
    {
        // Implement the logic to calculate the total pause hours for the user
        $timesheets = Timesheet::where('user_id', $user->id)
            ->where('type', 'pause')
            ->whereNotNull('day_in') // Ensure that day_in is not null
            ->whereNotNull('day_out') // Ensure that day_out is not null
            ->get();

            //dd($timesheets); // Debugging line to inspect the retrieved timesheets

        $totalPauseHours = 0;
        foreach ($timesheets as $timesheet) {
            // Assuming you have a 'hours' field in your Timesheet model
            $startTime = Carbon::parse($timesheet->day_in);
            $finishTime = Carbon::parse($timesheet->day_out);
            $totalDuration = $finishTime->diffInSeconds($startTime, true);
            //\Log::info("Timesheet {$timesheet->id}: {$startTime} - {$finishTime} = {$totalDuration} segundos");
            $totalPauseHours += $totalDuration;
        }

        $timeFormatted = gmdate('H:i:s', $totalPauseHours); // Format the total pause hours as H:i:s

        return $timeFormatted ?? 0; // Return 0 if no timesheets found
    }
}
