<?php

namespace App\Models;

use App\Support\ImageOptimizer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PressClipping extends Model
{
    protected static function booted(): void
    {
        // Compress + convert uploaded image to WebP before persisting.
        static::saving(function (PressClipping $clipping): void {
            $value = $clipping->image_path;

            if (is_string($value) && $value !== '' && $clipping->isDirty('image_path')) {
                $clipping->image_path = ImageOptimizer::toWebp($value);
            }
        });
    }

    protected $fillable = [
        'image_path',
        'caption',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'sort_order' => 'integer',
    ];

    /** Active clippings in display order. */
    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id');
    }
}
