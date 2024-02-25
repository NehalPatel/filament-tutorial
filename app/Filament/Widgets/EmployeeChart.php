<?php

namespace App\Filament\Widgets;

use App\Models\Employee;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class EmployeeChart extends ChartWidget
{
    protected static ?string $heading = 'Employee Chart';

    protected static string $color = 'warning';

    public ?string $filter = 'today';
    protected static bool $isLazy = true;
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'half';

    protected function getData(): array
    {
        $activeFilter = $this->filter;

        $data = null;

        switch ($activeFilter) {
            case 'today':
                $data = Trend::model(Employee::class)
                    ->between(
                        start: now()->startOfDay(),
                        end: now()->endOfDay(),
                    )
                    ->perHour() // Adjust the granularity based on your needs
                    ->count();
                break;

            case 'week':
                $data = Trend::model(Employee::class)
                    ->between(
                        start: now()->startOfWeek(),
                        end: now()->endOfWeek(),
                    )
                    ->perDay()
                    ->count();
                break;

            case 'month':
                $data = Trend::model(Employee::class)
                    ->between(
                        start: now()->startOfMonth(),
                        end: now()->endOfMonth(),
                    )
                    ->perDay()
                    ->count();
                break;

            case 'year':
                $data = Trend::model(Employee::class)
                    ->between(
                        start: now()->startOfYear(),
                        end: now()->endOfYear(),
                    )
                    ->perMonth()
                    ->count();
                break;

            // Add more cases for additional filters if needed

            default:
                // Default case (e.g., if no filter is selected)
                $data = Trend::model(Employee::class)
                    ->between(
                        start: now()->startOfYear(),
                        end: now()->endOfYear(),
                    )
                    ->perMonth()
                    ->count();
                break;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Employee created',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }


    protected function getType(): string
    {
        return 'bar';
    }

    protected function getFilters(): ?array
    {
        return [
            'today' => 'Today',
            'week' => 'This week',
            'month' => 'This month',
            'year' => 'This year',
        ];
    }
}
