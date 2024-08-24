<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ZonalRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:40', 'min:3', Rule::unique('zonals', 'nombre')->ignore($this->zonal)],
            'unidad_negocio' => 'required|in:ADMINISTRACION,DISTRIBUIDORA,FRANQUICIA,DAM,PROACTIVO,REACTIVO',
        ];
    }
}
