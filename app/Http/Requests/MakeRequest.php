<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MakeRequest extends FormRequest
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
                'max:50',
                'min:3',
                Rule::unique('makes')->ignore($this->make ? $this->make->id : null),
            ],
            'equipment_type_id' => 'required|exists:zonals,id',
        ];
    }
}
