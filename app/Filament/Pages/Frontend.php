<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Widgets;

class Frontend extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $view = 'filament.pages.frontend';

    protected static ?string $slug = '/';

    public function getTitle(): string
    {
        return '';
    }
}
