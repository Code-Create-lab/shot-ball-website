<?php

namespace App\Filament\Widgets;

use App\Models\Visitor;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class VisitorStats extends StatsOverviewWidget
{
    /** Live refresh so "Active now" stays current without a manual reload. */
    protected ?string $pollingInterval = '30s';

    protected static ?int $sort = 0;

    protected ?string $heading = 'Live traffic';

    protected ?string $description = 'Real-time visitor activity on the public website';

    protected function getStats(): array
    {
        $window = Carbon::now()->subMinutes(Visitor::ACTIVE_WINDOW_MINUTES);

        $activeNow = Visitor::where('last_seen_at', '>=', $window)->count();

        $today = Visitor::whereDate('last_seen_at', Carbon::today())->count();

        $uniqueVisitors = Visitor::count();

        $pageViews = (int) Visitor::sum('visits');

        $weekTrend = $this->dailyVisitorTrend();

        return [
            // 1 — Active now: the headline live metric (green = healthy/online).
            Stat::make('Active now', $activeNow)
                ->description($activeNow === 1 ? 'visitor online' : 'visitors online')
                ->descriptionIcon('heroicon-m-signal')
                ->color($activeNow > 0 ? 'success' : 'gray')
                ->extraAttributes(['class' => 'ring-1 ring-green-500/20']),

            // 2 — Today: fresh daily reach (blue = data/info).
            Stat::make('Visitors today', $today)
                ->description(Carbon::today()->format('D, d M Y'))
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('info'),

            // 3 — Unique visitors all-time, with a 7-day sparkline (amber accent).
            Stat::make('Unique visitors', number_format($uniqueVisitors))
                ->description('All time · last 7 days trend')
                ->descriptionIcon('heroicon-m-users')
                ->chart($weekTrend)
                ->color('warning'),

            // 4 — Total page views (gray = neutral volume metric).
            Stat::make('Page views', number_format($pageViews))
                ->description('Total loads tracked')
                ->descriptionIcon('heroicon-m-eye')
                ->chart($weekTrend)
                ->color('primary'),
        ];
    }

    /**
     * New unique visitors per day for the last 7 days, oldest → newest,
     * used as the sparkline data for the trend stats.
     */
    protected function dailyVisitorTrend(): array
    {
        $rows = Visitor::query()
            ->where('created_at', '>=', Carbon::today()->subDays(6))
            ->selectRaw('DATE(created_at) as day, COUNT(*) as total')
            ->groupBy('day')
            ->pluck('total', 'day');

        $trend = [];
        for ($i = 6; $i >= 0; $i--) {
            $day = Carbon::today()->subDays($i)->toDateString();
            $trend[] = (int) ($rows[$day] ?? 0);
        }

        return $trend;
    }
}
