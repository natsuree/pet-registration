<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DewormingRecord extends Model
{
    protected $fillable = ['pet_id', 'product', 'administered_at', 'next_due_at', 'weight_kg', 'notes'];

    protected function casts(): array
    {
        return ['administered_at' => 'date', 'next_due_at' => 'date', 'weight_kg' => 'decimal:2'];
    }

    public function pet(): BelongsTo
    {
        return $this->belongsTo(Pet::class);
    }
}
