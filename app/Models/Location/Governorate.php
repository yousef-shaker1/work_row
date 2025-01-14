<?php

namespace App\Models\Location;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Governorate extends Model
{
    protected $fillable = ['name', 'country_id', 'status'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getNameAttribute($value)
    {
        return ucfirst($value);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}
