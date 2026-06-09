<?php

namespace Database\Seeders;

use App\Models\PressClipping;
use App\Support\ImageOptimizer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class PressClippingSeeder extends Seeder
{
    /**
     * Seed the press_clippings table from the original static gallery in
     * public/assets/img/news (news1.jpeg .. news16.jpeg). Idempotent.
     */
    public function run(): void
    {
        if (PressClipping::query()->exists()) {
            $this->command?->info('Press clippings already seeded — skipping.');

            return;
        }

        $seeded = 0;

        for ($n = 1; $n <= 16; $n++) {
            $file   = "news{$n}.jpeg";
            $source = public_path('assets/img/news/' . $file);

            if (! is_file($source)) {
                $this->command?->warn("Missing image, skipping: {$file}");

                continue;
            }

            $dest = "news/{$file}";
            Storage::disk('public')->put($dest, file_get_contents($source));

            // Match admin-upload behaviour: compress + convert to WebP.
            $dest = ImageOptimizer::toWebp($dest);

            PressClipping::create([
                'image_path' => $dest,
                'caption'    => null,
                'sort_order' => $n,
                'is_active'  => true,
            ]);

            $seeded++;
        }

        $this->command?->info("Seeded {$seeded} press clippings.");
    }
}
