<?php

namespace App\Models;

use App\Support\ImageOptimizer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    public const CATEGORIES = [
        'national'      => 'National',
        'international' => 'International',
    ];

    protected static function booted(): void
    {
        // Compress + convert uploaded image to WebP before persisting.
        static::saving(function (Player $player): void {
            $value = $player->image_path;

            if (is_string($value) && $value !== '' && $player->isDirty('image_path')) {
                $player->image_path = ImageOptimizer::toWebp($value);
            }
        });
    }

    protected $fillable = [
        'name',
        'category',
        'image_path',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'sort_order' => 'integer',
    ];

    /** Active players in display order. */
    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id');
    }
}
