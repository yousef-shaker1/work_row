<?php

namespace App\Filament\Employee\Resources\CheckResource\Pages;

use App\Filament\Employee\Resources\CheckResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCheck extends CreateRecord
{
    protected static string $resource = CheckResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->user()->id; 
        $data['name'] = auth()->user()->name;    
        return $data;
    }
}
