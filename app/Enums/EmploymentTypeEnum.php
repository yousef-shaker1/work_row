<?php

namespace App\Enums;

use App\Traits\EnumHelper;
use Filament\Support\Contracts\HasLabel;

enum EmploymentTypeEnum: string implements HasLabel
{
    use EnumHelper;

    case FULL_TIME = 'full time';
    case PART_TIME = 'part time';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::FULL_TIME => 'Full Time',
            self::PART_TIME => 'Part Time',
        };
    }
}
