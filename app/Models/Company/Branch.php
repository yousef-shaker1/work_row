<?php

namespace App\Models\Company;

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
}
