<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Support\ImageOptimizer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MemberSeeder extends Seeder
{
    /**
     * Seed the members table from the original static roster shipped in
     * public/assets/img/members. Copies each photo onto the public disk
     * (so it is served from storage like admin uploads), then stores the
     * relative path. Idempotent — skips if members already exist.
     */
    public function run(): void
    {
        if (Member::query()->exists()) {
            $this->command?->info('Members already seeded — skipping.');

            return;
        }

        // [source filename, name, role, sort]
        $roster = [
            ['Durgesh Nandan.jpeg', 'Durgesh Nandan', "Assistant Commissioner of state taxes\nPresident GSBAB", 1],
            ['Ram Pravesh Kumar _Secretary General GSBAB.jpeg', 'Ram Pravesh Kumar', 'Secretary General, GSBAB', 2],
            ['AMIT KUMAR VERMA_Senior Vice President GSBAB.jpeg', 'Amit Kumar Verma', 'Senior Vice President, GSBAB', 3],
            ['Gaurav Kuma_Treasure GSBAB.jpeg', 'Gaurav Kumar', 'Treasurer, GSBAB', 4],
            ['Rakesh_ranjan_joint_secretary GSBAB.jpeg', 'Rakesh Ranjan', 'Joint Secretary, GSBAB', 5],
            ['VIKKI KUMAR JOINT SECRETARY GSBAB.jpeg', 'Vikki Kumar', 'Joint Secretary, GSBAB', 6],
            ['rituKumari.jpeg', 'Ritu Kumari', 'Vice President, GSBAB', 7],
        ];

        foreach ($roster as [$file, $name, $role, $sort]) {
            $source = public_path('assets/img/members/' . $file);

            if (! is_file($source)) {
                $this->command?->warn("Missing image, skipping {$name}: {$file}");

                continue;
            }

            // Slug the filename — spaces in stored paths break the Filament
            // FileUpload preview URL on the edit screen.
            $dest = 'members/' . Str::slug(pathinfo($file, PATHINFO_FILENAME)) . '.jpeg';
            Storage::disk('public')->put($dest, file_get_contents($source));

            // Match admin-upload behaviour: compress + convert to WebP.
            $dest = ImageOptimizer::toWebp($dest);

            Member::create([
                'name'       => $name,
                'role'       => $role,
                'image_path' => $dest,
                'sort_order' => $sort,
                'is_active'  => true,
            ]);
        }

        $this->command?->info('Seeded ' . count($roster) . ' members.');
    }
}
