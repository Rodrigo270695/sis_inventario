<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PdvRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:30|min:3|unique:pdvs,nombre',
            'direccion' => 'nullable|string|max:255',
            'zonal_id' => 'required|exists:zonals,id',
        ];
    }
}
