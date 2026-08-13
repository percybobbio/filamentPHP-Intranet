<?php

namespace App\Filament\Personal\Resources\Timesheets\Pages;

use App\Filament\Personal\Resources\Timesheets\TimesheetResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateTimesheet extends CreateRecord
{
    protected static string $resource = TimesheetResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Auth::user()->id; // Set the user_id to the currently authenticated user's ID

        return $data;
    }
}
