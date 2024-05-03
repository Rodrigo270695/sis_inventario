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
                'min:2',
                Rule::unique('makes')->where(function ($query) {
                    return $query->where('equipment_type_id', $this->equipment_type_id);
                })->ignore($this->make ? $this->make->id : null),
            ],
            'equipment_type_id' => 'required|exists:zonals,id',
        ];
    }
}
