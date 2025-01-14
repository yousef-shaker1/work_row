<?php

namespace App\Enums;

use App\Traits\EnumHelper;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasIcon;

enum GenderEnum: string implements HasLabel
{
    use EnumHelper;

    case MALE = 'male';
    case FEMALE = 'female';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::MALE => 'Male',
            self::FEMALE => 'Female',
        };
    }
}
