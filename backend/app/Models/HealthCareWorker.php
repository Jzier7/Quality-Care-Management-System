<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HealthCareWorker extends Model
{
    use HasFactory;

    protected $table = 'healthcare_workers';

    protected $fillable = [
        'user_id',
        'license_number',
        'department',
        'shift_start_time',
        'shift_end_time',
        'position'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
