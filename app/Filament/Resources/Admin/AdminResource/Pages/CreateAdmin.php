<?php

namespace App\Filament\Resources\Admin\AdminResource\Pages;

use App\Filament\Resources\Admin\AdminResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAdmin extends CreateRecord
{
    protected static string $resource = AdminResource::class;
}
