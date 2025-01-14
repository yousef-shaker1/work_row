<?php

namespace App\Models;

use App\Models\Company;
use App\Models\Employee\Employee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Check extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class);
    }
}
