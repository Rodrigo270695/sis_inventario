<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Store extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function pdv(): BelongsTo
    {
        return $this->belongsTo(Pdv::class);
    }

    public function teams(): HasMany
    {
        return $this->hasMany(Team::class);
    }

    public function accessories(): HasMany
    {
        return $this->hasMany(Accessory::class);
    }

}
