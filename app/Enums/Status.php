<?php

namespace App\Enums;

use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum Status: int implements HasLabel, HasIcon, HasColor
{
    case Active = 1;
    case Inactive = 0;

    public const DEFAULT = self::Active->value;

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Active => 'Active',
            self::Inactive => 'Inactive',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Active => 'heroicon-o-check-circle',
            self::Inactive => 'heroicon-o-x-circle',
        };
    }

    public function getColor(): ?string
    {
        return match ($this) {
            self::Active => 'success',
            self::Inactive => 'danger',
        };
    }
}
