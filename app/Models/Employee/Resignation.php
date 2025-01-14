<?php

namespace App\Models\Employee;

use App\Models\Employee\Employee;
use App\Enums\ResignationStatusEnum;
use Illuminate\Database\Eloquent\Model;

class Resignation extends Model
{
    protected $fillable = [
        'employee_id',
        'resignation_date',
        'last_working_day',
        'reason',
        'notes',
        'status',
    ];

    protected $casts = [
        'resignation_date' => 'date',
        'last_working_day' => 'date',
        'status' => ResignationStatusEnum::class,
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
