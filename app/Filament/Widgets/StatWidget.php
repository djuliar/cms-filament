<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', \App\Models\User::count())
                ->label('Total Users')
                ->description('Total number of users')
                ->icon('heroicon-o-users')
                ->chart([1, 3, 5, 10, 20, 40])
                ->color('success'),
            Stat::make('Total Article', \App\Models\Article::count())
                ->label('Total Article')
                ->description('Total number of articles')
                ->icon('heroicon-o-newspaper')
                ->chart([12, 30, 15, 5, 20, 25])
                ->color('danger'),
            Stat::make('Total Category', \App\Models\Category::count())
                ->label('Total Category')
                ->description('Total number of categories')
                ->icon('heroicon-o-squares-2x2')
                ->chart([55, 45, 33, 12, 8, 5])
                ->color('warning'),
        ];
    }
}
