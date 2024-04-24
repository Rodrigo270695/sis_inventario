<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Accessory extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'accessory_team');
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
