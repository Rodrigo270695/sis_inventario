<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ZonalRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:40|min:3|unique:zonals,nombre',
            'unidad_negocio' => 'required|in:ADMINISTRACION,DISTRIBUIDORA,FRANQUICIA,DAM,PROACTIVO,REACTIVO',
        ];
    }
}
