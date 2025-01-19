<?php

namespace App\Models\Company;

use App\Models\Location\Country;
use App\Models\Location\Governorate;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = [
        'name',
        'country_id',
        'governorate_id',
        'address',
        'company_id',
    ];
    protected $appends = [
        'location_string',
    ];

    public function getLocationStringAttribute()
    {
        return $this->city . ', ' . $this->country; // تكوين السلسلة النصية كما تحتاج
    }
    
    public function country()
{
    return $this->belongsTo(Country::class);
}

public function governorate()
{
    return $this->belongsTo(Governorate::class);
}
}

