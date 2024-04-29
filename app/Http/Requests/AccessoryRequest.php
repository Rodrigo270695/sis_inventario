<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AccessoryRequest extends FormRequest
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
            'nombre' => 'required|min:2|max:50',
            'modelo' => 'nullable|string|max:50',
            'capacidad' => 'nullable|string|max:8',
            'serie' => 'nullable|string|max:40',
            'estado_asignado' => 'in:ASIGNADO,NO ASIGNADO',
            'fecha_compra' => 'required|date',
            'garantia_tienda' => 'required|integer',
            'garantia_marca' => 'nullable|integer',
            'codigo_barras' => 'string|max:14|unique:accessories,codigo_barras',
            'estado' => '   in:BAJA,DESECHADO,MANTENIMIENTO-DAÑO,MANTENIMIENTO-GARANTIA,RESERVADO,USO',
            'descripcion' => 'nullable|string',
            'documento' => 'nullable|mimes:jpg,jpeg,png,doc,docx,pdf|max:3072',
            'make_id' => 'required|exists:makes,id',
            'store_id' => 'required|exists:stores,id',
        ];
    }
}
