<?php

namespace App\Models;

use App\Support\ImageOptimizer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected static function booted(): void
    {
        // Compress + convert uploaded image to WebP before persisting.
        static::saving(function (Member $member): void {
            $value = $member->image_path;

            if (is_string($value) && $value !== '' && $member->isDirty('image_path')) {
                $member->image_path = ImageOptimizer::toWebp($value);
            }
        });
    }

    protected $fillable = [
        'name',
        'role',
        'image_path',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'sort_order' => 'integer',
    ];

    /** Active members in display order. */
    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id');
    }
}
