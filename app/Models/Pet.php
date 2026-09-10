<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pet extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'species', 'breed', 'sex', 'date_of_birth', 'color', 'microchip', 'photo_path', 'owner_name', 'owner_email', 'owner_number'];

    protected function casts(): array
    {
        return ['date_of_birth' => 'date'];
    }

    protected static function booted(): void
    {
        static::created(function (self $pet): void {
            $pet->forceFill(['code' => 'PET-'.str_pad((string) $pet->id, 4, '0', STR_PAD_LEFT)])->saveQuietly();
        });
    }

    public function vaccinations(): HasMany
    {
        return $this->hasMany(Vaccination::class);
    }

    public function dewormingRecords(): HasMany
    {
        return $this->hasMany(DewormingRecord::class);
    }
}
