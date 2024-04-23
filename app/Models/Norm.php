<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Norm extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function make(): BelongsTo
    {
        return $this->belongsTo(Make::class);
    }
}
