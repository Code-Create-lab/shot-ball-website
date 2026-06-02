<?php

namespace App\Filament\Widgets;

use App\Models\Registration;
use Filament\Widgets\ChartWidget;

class RegistrationsByTypeChart extends ChartWidget
{
    protected ?string $heading = 'By registration type';

    protected static ?int $sort = 3;

    protected ?string $maxHeight = '320px';

    protected function getData(): array
    {
        $rows = Registration::query()
            ->selectRaw('registration_type, COUNT(*) as total')
            ->groupBy('registration_type')
            ->pluck('total', 'registration_type');

        return [
            'datasets' => [
                [
                    'label' => 'Registrations',
                    'data' => $rows->values()->all(),
                    'backgroundColor' => ['#2563EB', '#DC2626', '#16A34A', '#F59E0B', '#7C3AED'],
                    'borderColor' => '#FFFFFF',
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $rows->keys()->all(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
