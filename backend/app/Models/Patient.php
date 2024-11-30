<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'birthdate',
        'address',
        'emergency_contact',
        'sex',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
