<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Solicitude extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function typeRequest(): BelongsTo
    {
        return $this->belongsTo(TypeRequest::class, 'type_request_id');
    }

    public function pdv(): BelongsTo
    {
        return $this->belongsTo(Pdv::class, 'pdv_id');
    }
}
