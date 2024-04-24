<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Make extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function equipmentType(): BelongsTo
    {
        return $this->belongsTo(EquipmentType::class);
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
