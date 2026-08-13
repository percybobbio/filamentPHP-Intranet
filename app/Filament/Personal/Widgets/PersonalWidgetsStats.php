<?php

namespace App\Filament\Personal\Widgets;

use App\Models\Holiday;
use App\Models\Timesheet;
use App\Models\User;
use Carbon\Carbon;
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
            ->get();

        $totalWorkHours = 0;
        foreach ($timesheets as $timesheet) {
            // Assuming you have a 'hours' field in your Timesheet model
            $startTime = Carbon::parse($timesheet->day_in);
            $finishTime = Carbon::parse($timesheet->day_out);
            $totalDuration = $finishTime->diffInSeconds($startTime);
            $totalWorkHours += $totalDuration;
        }

        $timeFormatted = gmdate('H:i:s', $totalWorkHours); // Format the total work hours as H:i:s

        return $timeFormatted ?? 0; // Return 0 if no timesheets found
    }
}
