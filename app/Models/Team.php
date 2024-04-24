<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Team extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function accessories(): BelongsToMany
    {
        return $this->belongsToMany(Accessory::class, 'accessory_team');
    }

    public function make(): BelongsTo
    {
        return $this->belongsTo(Make::class);
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

}
