<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|min:3|max:100',
            'email' => 'required|min:3|max:255|unique:users,email',
            'pdv_id' => 'required',
            'rol' => 'required',
            'dni' => 'required|string|max:15|unique:users,dni',
        ];
    }
}
