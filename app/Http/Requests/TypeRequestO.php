<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TypeRequestO extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
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
                'max:50',
                'min:3',
                Rule::unique('type_requests')->ignore($this->typer ? $this->typer->id : null),
            ],
            'descripcion' => 'nullable|string'
        ];
    }
}
