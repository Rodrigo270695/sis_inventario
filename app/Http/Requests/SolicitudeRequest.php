<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SolicitudeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'mensaje' => 'required|string',
            'type_request_id' => 'required|exists:type_requests,id',
            'observacion_local' => 'nullable|string',
            'observacion_gerencia' => 'nullable|string',
        ];
    }
}
