<?php

namespace App\Filament\Widgets;

use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Users', User::query()->count())
                ->description('All Users registered for.')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('danger'),
            Stat::make('Employees', Employee::query()->count())
                ->description('Employees added in database')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make('Departments', Department::query()->count())
                ->description('Departments added into the system')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('info'),
        ];
    }
}
