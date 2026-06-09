<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Testimonial extends Model
{
    protected $fillable = [
        'name',
        'quote',
        'avatar',
        'rating',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'rating'     => 'integer',
        'sort_order' => 'integer',
    ];

    /** Avatar initial — explicit value, else first letter of name. */
    public function getInitialAttribute(): string
    {
        return Str::upper($this->avatar ?: Str::substr(trim($this->name), 0, 1));
    }

    /** Active testimonials in display order. */
    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id');
    }
}
