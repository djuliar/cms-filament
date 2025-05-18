<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Widgets;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-group';

    protected static string $view = 'filament.pages.dashboard';

    protected function getHeaderWidgets() : array
    {
        return [
            Widgets\AccountWidget::class,
        ];
    }

    protected function getFooterWidgets() : array
    {
        return [
            Widgets\FilamentInfoWidget::class,
        ];
    }
}
