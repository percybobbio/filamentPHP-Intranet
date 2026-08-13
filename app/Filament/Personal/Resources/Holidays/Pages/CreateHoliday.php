<?php

namespace App\Filament\Personal\Resources\Holidays\Pages;

use App\Filament\Personal\Resources\Holidays\HolidayResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateHoliday extends CreateRecord
{
    protected static string $resource = HolidayResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Auth::user()->id; // Set the user_id to the currently authenticated user's ID
        $data['type'] = 'pending';

        return $data;
    }
}
