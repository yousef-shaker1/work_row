<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = [
        'name',
        'description',
        'company_id',
    ];
}
