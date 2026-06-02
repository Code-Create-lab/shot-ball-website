<?php

namespace App\Filament\Widgets;

use App\Models\Registration;
use Filament\Widgets\Widget;
use Illuminate\Support\Str;

class BiharRegistrationMap extends Widget
{
    protected string $view = 'filament.widgets.bihar-registration-map';

    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 'full';

    /**
     * Counts keyed by the GeoJSON district name (no parenthetical suffix).
     *
     * @return array<string, int>
     */
    public function getCountsProperty(): array
    {
        return Registration::query()
            ->selectRaw('district, COUNT(*) as total')
            ->groupBy('district')
            ->pluck('total', 'district')
            ->mapWithKeys(fn ($total, $district) => [
                // "East Champaran (Motihari)" -> "East Champaran"
                trim(Str::before($district, '(')) => $total,
            ])
            ->all();
    }

    protected function getViewData(): array
    {
        $counts = $this->getCountsProperty();

        return [
            'counts' => $counts,
            'geojsonUrl' => '/assets/geo/bihar-districts.geojson',
            'max' => $counts ? max($counts) : 0,
            'total' => array_sum($counts),
        ];
    }
}
