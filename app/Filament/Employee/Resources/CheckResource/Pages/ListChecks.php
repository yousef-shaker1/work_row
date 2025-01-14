<?php

namespace App\Filament\Employee\Resources\CheckResource\Pages;

use App\Filament\Employee\Resources\CheckResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListChecks extends ListRecords
{
    protected static string $resource = CheckResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
