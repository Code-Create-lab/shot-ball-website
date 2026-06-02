<?php

namespace App\Filament\Widgets;

use App\Models\Registration;
use Filament\Widgets\ChartWidget;

class RegistrationsByDistrictChart extends ChartWidget
{
    protected ?string $heading = 'Registrations by district';

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected ?string $maxHeight = '320px';

    protected function getData(): array
    {
        $rows = Registration::query()
            ->selectRaw('district, COUNT(*) as total')
            ->groupBy('district')
            ->orderByDesc('total')
            ->pluck('total', 'district');

        return [
            'datasets' => [
                [
                    'label' => 'Registrations',
                    'data' => $rows->values()->all(),
                    'backgroundColor' => '#F59E0B',
                    'borderColor' => '#B45309',
                    'borderWidth' => 1,
                    'borderRadius' => 4,
                ],
            ],
            'labels' => $rows->keys()->all(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => ['display' => false],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => ['precision' => 0],
                ],
            ],
        ];
    }
}
