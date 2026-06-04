<?php

namespace App\Models;

use App\Support\ImageOptimizer;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    protected static function booted(): void
    {
        // Compress + convert uploaded images to WebP before persisting.
        static::saving(function (Registration $registration): void {
            foreach (['photo_path', 'signature_path'] as $field) {
                $value = $registration->{$field};

                if (is_string($value) && $value !== '' && $registration->isDirty($field)) {
                    $registration->{$field} = ImageOptimizer::toWebp($value);
                }
            }
        });

        // Assign a human-friendly registration id (GSBAB-1, GSBAB-2, ...) once the row has an id.
        static::created(function (Registration $registration): void {
            if (empty($registration->registration_id)) {
                $registration->registration_id = 'GSBAB-' . $registration->id;
                $registration->saveQuietly();
            }
        });
    }

    protected $fillable = [
        'registration_type',
        'event_type',
        'first_name',
        'middle_name',
        'last_name',
        'dob',
        'father_name',
        'mother_name',
        'address',
        'village_city',
        'state',
        'district',
        'club1',
        'club2',
        'pincode',
        'country',
        'aadhaar',
        'mobile',
        'email',
        'email_status',
        'photo_path',
        'signature_path',
    ];

    protected $casts = [
        'dob' => 'date',
    ];

    /**
     * Display reference, e.g. GSBAB-1. Falls back to a computed value
     * for unsaved/sample records (mail previews).
     */
    public function getRefAttribute(): string
    {
        return $this->registration_id
            ?: 'GSBAB-' . ($this->id ?? 0);
    }
}
