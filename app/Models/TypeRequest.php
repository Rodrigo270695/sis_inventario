<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TypeRequest extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function solicitudes(): HasMany
    {
        return $this->hasMany(Solicitude::class, 'type_request_id');
    }
}
