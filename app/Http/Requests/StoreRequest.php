<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => [
                'required',
                'string',
                'max:70',
                'min:3',
                // Asegura que la combinación de nombre y pdv_id sea única
                Rule::unique('stores')->where(function ($query) {
                    return $query->where('pdv_id', $this->pdv_id);
                })->ignore($this->store ? $this->store->id : null),
            ],
            'descripcion' => 'nullable|string',
            'pdv_id' => 'required|exists:pdvs,id',
        ];
    }
}
