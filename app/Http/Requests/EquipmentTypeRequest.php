<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EquipmentTypeRequest extends FormRequest
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
                Rule::unique('equipment_types')->ignore($this->equipment_type ? $this->equipment_types->id : null),
            ]
        ];
    }
}
