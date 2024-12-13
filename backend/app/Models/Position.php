<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Position extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description'
    ];

    public function healthcare_workerss(): HasMany
    {
        return $this->hasMany(HealthCareWorker::class);
    }
}
