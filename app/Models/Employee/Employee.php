<?php

namespace App\Models\Employee;

use Filament\Panel;
use App\Enums\GenderEnum;
use App\Traits\HasCompany;
use App\Traits\HasLocation;
use App\Enums\EmploymentTypeEnum;
use App\Enums\EmploymentStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Employee extends Authenticatable
{
    use HasCompany, HasLocation;
    
    protected $fillable = [
        'first_name',
        'is_admin',
        'middle_name',
        'last_name',
        'email',
        'work_email',
        'phone',
        'password',
        'gender',
        'date_of_birth',
        'hire_date',
        'job_title',
        'department_id',
        'branch_id',
        'status',
        'employement_type',
        'salary',
        'country_id',
        'governorate_id',
        'address',
        'company_id',
    ];

    protected $hidden = [
        'password',
    ];
    protected $casts = [
        'password' => 'hashed',
        'gender' => GenderEnum::class,
        'date_of_birth' => 'date',
        'hire_date' => 'date',
        'status' => EmploymentStatusEnum::class,
        'employement_type' => EmploymentTypeEnum::class,
        'is_admin' => 'boolean',
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'employee') {
            return true;
        }

        return false;
    }
    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    protected $appends = [
        'full_name',
        'location_string',
    ];


}
