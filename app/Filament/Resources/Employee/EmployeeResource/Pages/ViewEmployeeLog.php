<?php

namespace App\Filament\Admin\Resources\User\EmployeeResource\Pages;

use Filament\Actions;
use Filament\Infolists;
use App\Livewire\EmployeeLog;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;
use App\Filament\Resources\Employee\EmployeeResource;

class ViewEmployeeLog extends ViewRecord
{
    protected static string $resource = EmployeeResource::class;

    public function getTitle(): string|Htmlable
    {
        return parent::getTitle() . ' Log';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Livewire::make(EmployeeLog::class)
                    ->columnSpanFull()
            ]);
    }
}
