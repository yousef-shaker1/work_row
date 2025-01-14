<?php

namespace App\Filament\Employee\Resources\CheckResource\Pages;

use App\Filament\Employee\Resources\CheckResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCheck extends EditRecord
{
    protected static string $resource = CheckResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
