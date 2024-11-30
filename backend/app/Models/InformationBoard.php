<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class InformationBoard extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function files(): MorphMany
    {
        return $this->morphMany(File::class, 'fileable');
    }
}
