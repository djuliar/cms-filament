<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class FileManager extends Page
{
    protected static ?string $navigationGroup = 'Settings';
    protected static ?string $navigationIcon = 'heroicon-o-folder';
    protected static ?int $navigationSort = 2;

    protected static string $view = 'filament.pages.file-manager';


}
