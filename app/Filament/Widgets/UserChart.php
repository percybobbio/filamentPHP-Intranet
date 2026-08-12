<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class UserChart extends ChartWidget
{
    protected ?string $heading = 'User Chart';

    protected function getData(): array
    {
        return [
            //
            'datasets' => [
                [
                    'label' => 'Blog posts created',
                    'data' => $this->getDataUser(),
                    'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        ];
        
    }

    protected function getFilters(): ?array
    {
        return [
            //
            'today' => 'Today',
            'week' => 'This Week',
            'month' => 'This Month',
            'year' => 'This Year',
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getDataUser(): array
    {
        return [0, 10, 5, 2, 21, 32, 45, 74, 65, 45, 77, 89];
            
    }
}
