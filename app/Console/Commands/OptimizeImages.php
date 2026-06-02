<?php

namespace App\Console\Commands;

use App\Models\Registration;
use App\Support\ImageOptimizer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OptimizeImages extends Command
{
    protected $signature = 'images:optimize
        {--disk=public : Filesystem disk to read from}
        {--quality=80 : WebP quality (0-100)}
        {--max-edge=1600 : Longest edge in pixels before downscaling}
        {--dir=* : Extra directories to sweep (relative to the disk root)}
        {--assets : Also compress static site images in public/assets/img in place (keeps filename + format)}
        {--assets-path=assets/img : Public path to sweep when --assets is set}
        {--assets-max-edge=1920 : Longest edge for static site images}
        {--assets-quality=82 : JPEG quality for static site images}';

    protected $description = 'Convert and compress registration images to WebP (DB rows + any loose files)';

    public function handle(): int
    {
        $disk = $this->option('disk');
        $quality = (int) $this->option('quality');
        $maxEdge = (int) $this->option('max-edge');

        $converted = 0;
        $skipped = 0;

        // 1) Registration records — keeps DB paths in sync.
        $this->info('Optimizing registration records...');
        Registration::query()->chunkById(100, function ($rows) use (&$converted, &$skipped, $disk, $quality, $maxEdge) {
            foreach ($rows as $registration) {
                foreach (['photo_path', 'signature_path'] as $field) {
                    $path = $registration->{$field};
                    if (! is_string($path) || $path === '') {
                        continue;
                    }

                    $new = ImageOptimizer::toWebp($path, $disk, $maxEdge, $quality);

                    if ($new !== $path) {
                        $registration->{$field} = $new;
                        $registration->saveQuietly();
                        $converted++;
                        $this->line("  <info>✓</info> {$path} → {$new}");
                    } else {
                        $skipped++;
                    }
                }
            }
        });

        // 2) Loose files in the upload directories (orphans / extra --dir).
        $dirs = array_merge(
            ['registrations/photos', 'registrations/signatures'],
            (array) $this->option('dir'),
        );

        foreach ($dirs as $dir) {
            foreach (Storage::disk($disk)->files($dir) as $file) {
                if (Str::endsWith(Str::lower($file), '.webp')) {
                    continue;
                }

                $new = ImageOptimizer::toWebp($file, $disk, $maxEdge, $quality);
                if ($new !== $file) {
                    $converted++;
                    $this->line("  <info>✓</info> {$file} → {$new}");
                }
            }
        }

        // 3) Static site images (in place, same filename + format).
        if ($this->option('assets')) {
            $this->newLine();
            $this->info('Compressing static site images in place...');

            $base = public_path($this->option('assets-path'));
            $maxEdge = (int) $this->option('assets-max-edge');
            $aQuality = (int) $this->option('assets-quality');
            $savedBytes = 0;
            $touched = 0;

            if (! is_dir($base)) {
                $this->warn("  Path not found: {$base}");
            } else {
                $iterator = new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator($base, \FilesystemIterator::SKIP_DOTS)
                );

                foreach ($iterator as $file) {
                    if (! $file->isFile()) {
                        continue;
                    }
                    if (! in_array(Str::lower($file->getExtension()), ['jpg', 'jpeg', 'png'], true)) {
                        continue;
                    }

                    $saved = ImageOptimizer::optimizeInPlace($file->getPathname(), $maxEdge, $aQuality);
                    if ($saved > 0) {
                        $touched++;
                        $savedBytes += $saved;
                        $this->line(sprintf('  <info>✓</info> %s  (-%s KB)', $file->getFilename(), number_format($saved / 1024)));
                    }
                }
            }

            $this->info(sprintf('Static images: %d compressed, %s MB saved.', $touched, number_format($savedBytes / 1048576, 1)));
        }

        $this->newLine();
        $this->info("Done. Converted {$converted}, already-optimized {$skipped}.");

        return self::SUCCESS;
    }
}
