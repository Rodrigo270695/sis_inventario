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
                Rule::unique('stores')->ignore($this->store ? $this->store->id : null),
            ],
            'descripcion' => 'nullable|string',
            'pdv_id' => 'required|exists:pdvs,id',
        ];
    }
}
