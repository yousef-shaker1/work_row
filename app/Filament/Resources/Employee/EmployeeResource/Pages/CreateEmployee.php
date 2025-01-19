<?php

namespace App\Filament\Resources\Employee\EmployeeResource\Pages;

use App\Filament\Resources\Employee\EmployeeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateEmployee extends CreateRecord
{
    protected static string $resource = EmployeeResource::class;

}
