<?php

namespace App\Models\Employee;

use Filament\Panel;
use App\Models\Check;
use App\Enums\GenderEnum;
use App\Traits\HasCompany;
use App\Traits\HasLocation;
use App\Models\Company\Branch;
use App\Enums\EmploymentTypeEnum;
use App\Models\Company\Department;
use App\Enums\EmploymentStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn() => "{$this->first_name} {$this->middle_name} {$this->last_name}",
        );
    }
    public function getLocationStringAttribute()
    {
        return $this->city . ', ' . $this->country; // تكوين السلسلة النصية كما تحتاج
    }

    public function educations(): HasMany
    {
        return $this->hasMany(Education::class);
    }

    public function experiences(): HasMany
    {
        return $this->hasMany(Experience::class);
    }

    public function workHistories(): HasMany
    {
        return $this->hasMany(WorkHistory::class);
    }

    public function resignation(): HasOne
    {
        return $this->hasOne(Resignation::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }


    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    

    public function residency(): HasOne
    {
        return $this->hasOne(Residency::class);
    }

    public function checks(): HasMany
    {
        return $this->hasMany(Check::class);
    }


}
