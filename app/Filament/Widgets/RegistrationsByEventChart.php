<?php

namespace App\Filament\Widgets;

use App\Models\Registration;
use Filament\Widgets\ChartWidget;

class RegistrationsByEventChart extends ChartWidget
{
    protected ?string $heading = 'By event level';

    protected static ?int $sort = 4;

    protected ?string $maxHeight = '320px';

    protected function getData(): array
    {
        $rows = Registration::query()
            ->selectRaw('event_type, COUNT(*) as total')
            ->groupBy('event_type')
            ->pluck('total', 'event_type');

        return [
            'datasets' => [
                [
                    'label' => 'Registrations',
                    'data' => $rows->values()->all(),
                    'backgroundColor' => ['#0EA5E9', '#F59E0B', '#EC4899', '#10B981', '#8B5CF6'],
                    'borderColor' => '#FFFFFF',
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $rows->keys()->all(),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
