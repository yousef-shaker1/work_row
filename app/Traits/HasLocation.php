<?php

namespace App\Traits;

use App\Models\Location\Country;
use App\Models\Location\Governorate;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasLocation
{
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function governorate(): BelongsTo
    {
        return $this->belongsTo(Governorate::class);
    }

    // protected function locationString(): Attribute
    // {
    //     return new Attribute(
    //         get: fn() => getLocationString($this->country, $this->governorate),
    //     );
    // }
}
