<?php

namespace App\Enums;

use App\Traits\EnumHelper;
use Filament\Support\Colors\Color;
use Filament\Facades\Filament;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum EmploymentStatusEnum: string implements HasLabel, HasColor
{
    use EnumHelper;

    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case SUSPENDED = 'suspended';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::ACTIVE => 'Active',
            self::INACTIVE => 'Inactive',
            self::SUSPENDED => 'Suspended',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::ACTIVE => 'primary',
            self::INACTIVE => 'info',
            self::SUSPENDED => 'danger',
        };
    }
}