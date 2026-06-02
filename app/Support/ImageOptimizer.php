<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageOptimizer
{
    /**
     * Convert an image stored on a disk to a compressed WebP, resizing it
     * down to a max width/height. Deletes the original and returns the new
     * relative path. Returns the original path unchanged if it is already
     * WebP, missing, or not a supported raster image.
     *
     * @param  string  $path     Relative path on the disk (e.g. registrations/photos/x.jpg)
     * @param  string  $disk     Filesystem disk name
     * @param  int     $maxEdge  Longest edge in pixels; larger images are scaled down
     * @param  int     $quality  WebP quality 0-100
     */
    public static function toWebp(string $path, string $disk = 'public', int $maxEdge = 1600, int $quality = 80): string
    {
        if ($path === '' || Str::endsWith(Str::lower($path), '.webp')) {
            return $path;
        }

        $storage = Storage::disk($disk);

        if (! $storage->exists($path)) {
            return $path;
        }

        $absolute = $storage->path($path);

        $image = self::createFromFile($absolute);
        if ($image === null) {
            return $path; // unsupported / corrupt — leave as-is
        }

        [$width, $height] = [imagesx($image), imagesy($image)];

        // Scale down if larger than the max edge (keep aspect ratio).
        $scale = min(1, $maxEdge / max($width, $height));
        if ($scale < 1) {
            $newWidth = max(1, (int) round($width * $scale));
            $newHeight = max(1, (int) round($height * $scale));
            $resized = imagecreatetruecolor($newWidth, $newHeight);
            imagealphablending($resized, false);
            imagesavealpha($resized, true);
            imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagedestroy($image);
            $image = $resized;
        } else {
            imagealphablending($image, false);
            imagesavealpha($image, true);
        }

        $webpPath = Str::beforeLast($path, '.') . '.webp';
        $webpAbsolute = $storage->path($webpPath);

        $ok = imagewebp($image, $webpAbsolute, $quality);
        imagedestroy($image);

        if (! $ok) {
            return $path;
        }

        // Remove the original (only if it differs from the new file).
        if ($webpPath !== $path) {
            $storage->delete($path);
        }

        return $webpPath;
    }

    /**
     * Recompress/resize an image in place, keeping its filename and format
     * (so existing references stay valid). Only overwrites when the result is
     * smaller. Returns bytes saved (0 if skipped or no gain).
     */
    public static function optimizeInPlace(string $absolute, int $maxEdge = 1920, int $jpegQuality = 82): int
    {
        if (! is_file($absolute)) {
            return 0;
        }

        $info = @getimagesize($absolute);
        if ($info === false) {
            return 0;
        }

        $type = $info[2];
        if (! in_array($type, [IMAGETYPE_JPEG, IMAGETYPE_PNG], true)) {
            return 0; // skip gif/webp/svg/etc.
        }

        $before = (int) filesize($absolute);

        $image = self::createFromFile($absolute);
        if ($image === null) {
            return 0;
        }

        [$width, $height] = [imagesx($image), imagesy($image)];
        $scale = min(1, $maxEdge / max($width, $height));

        if ($scale < 1) {
            $newWidth = max(1, (int) round($width * $scale));
            $newHeight = max(1, (int) round($height * $scale));
            $resized = imagecreatetruecolor($newWidth, $newHeight);
            imagealphablending($resized, false);
            imagesavealpha($resized, true);
            imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagedestroy($image);
            $image = $resized;
        } else {
            imagealphablending($image, false);
            imagesavealpha($image, true);
        }

        $tmp = $absolute . '.opt-tmp';

        if ($type === IMAGETYPE_JPEG) {
            imageinterlace($image, true); // progressive
            $ok = imagejpeg($image, $tmp, $jpegQuality);
        } else {
            $ok = imagepng($image, $tmp, 9); // max zlib compression, lossless
        }

        imagedestroy($image);

        if (! $ok || ! is_file($tmp)) {
            @unlink($tmp);
            return 0;
        }

        $after = (int) filesize($tmp);

        if ($after > 0 && $after < $before) {
            rename($tmp, $absolute);
            return $before - $after;
        }

        @unlink($tmp);
        return 0;
    }

    private static function createFromFile(string $absolute): ?\GdImage
    {
        $info = @getimagesize($absolute);
        if ($info === false) {
            return null;
        }

        $image = match ($info[2]) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($absolute),
            IMAGETYPE_PNG  => @imagecreatefrompng($absolute),
            IMAGETYPE_WEBP => @imagecreatefromwebp($absolute),
            IMAGETYPE_GIF  => @imagecreatefromgif($absolute),
            default        => false,
        };

        return $image instanceof \GdImage ? $image : null;
    }
}
