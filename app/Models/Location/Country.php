<?php

namespace App\Models\Location;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends Model
{
    protected $fillable = ['name', 'status'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getNameAttribute($value)
    {
        return ucfirst($value);
    }

    public function governorates(): HasMany
    {
        return $this->hasMany(Governorate::class);
    }
}
