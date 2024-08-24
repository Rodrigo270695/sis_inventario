<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TeamRequest extends FormRequest
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
            'nombre' => ['required','string','max:50',Rule::unique('teams')->ignore($this->team ? $this->team->id : null),],
            'modelo' => 'nullable|string|max:50',
            'ip' => 'nullable|ip',
            'serie' => 'nullable|string|max:40',
            'procesador' => 'nullable|string|max:60',
            'mac' => 'nullable|string|max:30',
            'estado' => 'nullable|in:BAJA,DESECHADO,MANTENIMIENTO-DAÑO,MANTENIMIENTO-GARANTIA,RESERVADO,USO',
            'fecha_compra' => 'required|date',
            'garantia_tienda' => 'required|integer|min:1',
            'garantia_marca' => 'nullable|integer|min:0',
            'codigo_barras' => 'nullable|string|max:100',
            'descripcion' => 'nullable|string',
            'documento' => 'nullable|mimes:jpg,jpeg,png,doc,docx,pdf|max:3072',
            'make_id' => 'required|exists:makes,id',
            'store_id' => 'required|exists:stores,id',
        ];
    }
}
