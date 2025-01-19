<?php

namespace App\Models;

use App\Models\Company;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    protected $fillable = ['name', 'company_id', 'start_time', 'end_time', 'type', 'work_days'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    protected $casts = [
        'work_days' => 'array',
    ];
}
