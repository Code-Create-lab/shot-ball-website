<?php

namespace App\Filament\Widgets;

use App\Models\Registration;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class RegistrationStats extends StatsOverviewWidget
{
    protected ?string $pollingInterval = null;

    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $total = Registration::count();
        $thisMonth = Registration::whereBetween('created_at', [
            Carbon::now()->startOfMonth(),
            Carbon::now()->endOfMonth(),
        ])->count();

        $topDistrict = Registration::query()
            ->selectRaw('district, COUNT(*) as aggregate')
            ->groupBy('district')
            ->orderByDesc('aggregate')
            ->first();

        $districtsCovered = Registration::query()->distinct('district')->count('district');

        return [
            Stat::make('Total registrations', $total)
                ->description('All time')
                ->descriptionIcon('heroicon-m-users')
                ->color('warning'),
            Stat::make('This month', $thisMonth)
                ->description(Carbon::now()->format('F Y'))
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('info'),
            Stat::make('Top district', $topDistrict?->district ?? '—')
                ->description($topDistrict ? "{$topDistrict->aggregate} registrations" : 'No data yet')
                ->descriptionIcon('heroicon-m-map-pin')
                ->color('success'),
            Stat::make('Districts covered', $districtsCovered . ' / 38')
                ->description('Across Bihar')
                ->descriptionIcon('heroicon-m-map')
                ->color('primary'),
        ];
    }
}
