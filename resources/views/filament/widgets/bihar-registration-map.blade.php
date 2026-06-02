<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">Registrations across Bihar</x-slot>
        <x-slot name="description">{{ $total }} registration(s) mapped by district. Darker = more.</x-slot>

        <div wire:ignore>
            <div
                id="bihar-registration-map"
                style="height: 480px; width: 100%; border-radius: 12px; overflow: hidden; z-index: 0; background:#FFFBEB;"
            ></div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>

@assets
    <link rel="stylesheet" href="/assets/leaflet/leaflet.css" />
    <script src="/assets/leaflet/leaflet.js" defer></script>
@endassets

@script
<script>
    (function () {
        const counts = @json($counts);
        const geojsonUrl = @json($geojsonUrl);
        const max = @json($max) || 1;

        const el = document.getElementById('bihar-registration-map');
        if (!el) return;

        function colorFor(count) {
            if (!count) return '#FEF3C7';
            const stops = ['#FDE68A', '#FCD34D', '#FBBF24', '#F59E0B', '#D97706', '#B45309'];
            const t = Math.min(count / max, 1);
            return stops[Math.min(stops.length - 1, Math.floor(t * (stops.length - 1)))];
        }

        function render() {
            if (!window.L) { setTimeout(render, 100); return; }
            if (el.dataset.rendered) return;
            el.dataset.rendered = '1';

            const map = L.map(el, { scrollWheelZoom: false, attributionControl: false })
                .setView([25.9, 85.8], 7);

            L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
                maxZoom: 12, minZoom: 6,
            }).addTo(map);

            fetch(geojsonUrl)
                .then((r) => r.json())
                .then((geo) => {
                    const layer = L.geoJSON(geo, {
                        style: (f) => ({
                            fillColor: colorFor(counts[f.properties.district] || 0),
                            weight: 1,
                            color: '#FFFFFF',
                            fillOpacity: 0.85,
                        }),
                        onEachFeature: (f, lyr) => {
                            const name = f.properties.district;
                            const n = counts[name] || 0;
                            lyr.bindTooltip(
                                `<strong>${name}</strong><br>${n} registration${n === 1 ? '' : 's'}`,
                                { sticky: true }
                            );
                            lyr.on({
                                mouseover: (e) => e.target.setStyle({ weight: 2.5, color: '#0F172A', fillOpacity: 1 }),
                                mouseout: (e) => layer.resetStyle(e.target),
                            });
                        },
                    }).addTo(map);

                    map.fitBounds(layer.getBounds(), { padding: [10, 10] });
                    setTimeout(() => map.invalidateSize(), 200);

                    const legend = L.control({ position: 'bottomright' });
                    legend.onAdd = () => {
                        const div = L.DomUtil.create('div');
                        div.style.cssText = 'background:#fff;padding:8px 10px;border-radius:8px;font:12px/1.4 Arial,sans-serif;box-shadow:0 1px 4px rgba(0,0,0,.2);';
                        div.innerHTML =
                            '<div style="font-weight:700;margin-bottom:4px;color:#0F172A;">Registrations</div>' +
                            '<div style="display:flex;align-items:center;gap:6px;color:#334155;">' +
                            '<span style="display:inline-block;width:14px;height:14px;background:#FEF3C7;border:1px solid #ddd;"></span> 0' +
                            '<span style="display:inline-block;width:14px;height:14px;background:#F59E0B;margin-left:8px;"></span> ' + Math.ceil(max / 2) +
                            '<span style="display:inline-block;width:14px;height:14px;background:#B45309;margin-left:8px;"></span> ' + max +
                            '</div>';
                        return div;
                    };
                    legend.addTo(map);
                });
        }

        render();
    })();
</script>
@endscript
