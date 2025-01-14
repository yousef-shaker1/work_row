<?php

namespace App\Models\Employee;

use App\Models\Employee\Employee;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Residency extends Model implements HasMedia
{
    use InteractsWithMedia;
    
    protected $fillable = [
        'civil_id_number',
        'civil_id_expiration',
        'passport_number',
        'passport_expiration',
        'iqama_number',
        'iqama_expiration',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
