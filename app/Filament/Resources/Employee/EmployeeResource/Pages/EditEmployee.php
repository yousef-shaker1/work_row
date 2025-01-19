<?php

namespace App\Filament\Resources\Employee\EmployeeResource\Pages;

use App\Filament\Resources\Employee\EmployeeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEmployee extends EditRecord
{
    protected static string $resource = EmployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('View Log')
                ->icon('heroicon-m-clipboard-document-list')
                ->color('secondary')
                ->url(EmployeeResource::getUrl('view-log', parameters: [
                    'record' => $this->record,
                ])),
            Actions\DeleteAction::make(),
        ];
    }
}
