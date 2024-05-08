<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($this->user ? $this->user->id : null),
            ],
            'dni' => [
                'required',
                'numeric',
                'digits:8',
                Rule::unique('users')->ignore($this->user ? $this->user->id : null),
            ],
            'name' => 'required|min:3|max:100',
            'pdv_id' => 'required',
            'rol' => 'required',
        ];
    }
}
