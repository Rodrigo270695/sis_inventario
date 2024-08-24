<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PdvRequest extends FormRequest
{

    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'nombre' => [
                'required',
                'string',
                'max:30',
                'min:3',
                Rule::unique('pdvs')->ignore($this->pdv ? $this->pdv->id : null),
            ],
            'direccion' => 'nullable|string|max:255',
            'zonal_id' => 'required|exists:zonals,id',
        ];
    }
}
