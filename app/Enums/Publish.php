<?php

namespace App\Enums;

use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum Publish: int implements HasLabel, HasIcon, HasColor
{
    case PUBLISH = 1;
    case DRAFT = 0;

    public const DEFAULT = self::PUBLISH->value;

    public function getLabel(): ?string
    {
        return match ($this) {
            self::PUBLISH => 'PUBLISH',
            self::DRAFT => 'DRAFT',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::PUBLISH => 'heroicon-o-document-check',
            self::DRAFT => 'heroicon-o-document-text',
        };
    }

    public function getColor(): ?string
    {
        return match ($this) {
            self::PUBLISH => 'success',
            self::DRAFT => 'warning',
        };
    }
}
