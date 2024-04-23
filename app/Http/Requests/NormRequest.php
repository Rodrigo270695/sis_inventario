<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NormRequest extends FormRequest
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
                'max:70',
                'min:3',
                Rule::unique('norms')->ignore($this->norm ? $this->norm->id : null),
            ],
            'make_id' => 'required|exists:makes,id',
        ];
    }
}
