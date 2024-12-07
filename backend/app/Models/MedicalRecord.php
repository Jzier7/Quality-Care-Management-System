<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicalRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'healthcare_worker_id',
        'serial_number',
        'date',
        'diagnosis',
        'prescriptions'
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function healthcare_worker(): BelongsTo
    {
        return $this->belongsTo(HealthCareWorker::class);
    }
}
