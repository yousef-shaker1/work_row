<?php

namespace App\Filament\Employee\Resources\CheckResource\Pages;

use App\Models\Check;
use Filament\Actions;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Employee\Resources\CheckResource;

class ListChecks extends ListRecords
{
    protected static string $resource = CheckResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getTableQuery(): Builder
    {
        return Check::where('employee_id', Auth::guard('employee')->user()->id);
    }
}
