<?php

namespace Database\Seeders;

use App\Models\Player;
use App\Support\ImageOptimizer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class PlayerSeeder extends Seeder
{
    /**
     * Seed the players table from the original static portraits in
     * public/assets/img/sliders. Idempotent — skips if players exist.
     */
    public function run(): void
    {
        if (Player::query()->exists()) {
            $this->command?->info('Players already seeded — skipping.');

            return;
        }

        // category => [slider basenames in display order]
        $roster = [
            'national'      => ['slider32', 'slider33'],
            'international' => ['slider2', 'slider34', 'slider35', 'slider16'],
        ];

        $seeded = 0;

        foreach ($roster as $category => $images) {
            foreach ($images as $i => $base) {
                $file   = $base . '.jpeg';
                $source = public_path('assets/img/sliders/' . $file);

                if (! is_file($source)) {
                    $this->command?->warn("Missing image, skipping: {$file}");

                    continue;
                }

                $dest = "players/{$base}.jpeg";
                Storage::disk('public')->put($dest, file_get_contents($source));

                // Match admin-upload behaviour: compress + convert to WebP.
                $dest = ImageOptimizer::toWebp($dest);

                Player::create([
                    'name'       => null,
                    'category'   => $category,
                    'image_path' => $dest,
                    'sort_order' => $i + 1,
                    'is_active'  => true,
                ]);

                $seeded++;
            }
        }

        $this->command?->info("Seeded {$seeded} players.");
    }
}
