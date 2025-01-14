<?php

namespace App\Enums;

use App\Traits\EnumHelper;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum ResignationStatusEnum: string implements HasLabel, HasIcon, HasColor
{
    use EnumHelper;

    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::APPROVED => 'Approved',
            self::REJECTED => 'Rejected',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::PENDING => 'heroicon-m-clock',
            self::APPROVED => 'heroicon-m-check',
            self::REJECTED => 'heroicon-m-x-mark',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::PENDING => 'primary',
            self::APPROVED => 'success',
            self::REJECTED => 'danger',
        };
    }
}
