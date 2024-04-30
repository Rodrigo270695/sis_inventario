<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccessoryRequest;
use App\Models\Accessory;
use App\Models\Make;
use App\Models\Pdv;
use App\Models\Store;
use App\Models\Team;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class AccessoryController extends Controller
{

    public function index(): Response
    {
        $accessories = Accessory::with(['store.pdv', 'make.equipmenttype', 'teams'])
            ->orderBy('id', 'desc')
            ->paginate(7);
        $stores = Store::with(['pdv.zonal'])
            ->join('pdvs', 'stores.pdv_id', '=', 'pdvs.id')
            ->join('zonals', 'pdvs.zonal_id', '=', 'zonals.id')
            ->where('stores.estado', 1)
            ->orderBy('zonals.nombre', 'asc')
            ->select('stores.*', 'pdvs.nombre as pdv_nombre', 'zonals.nombre as zonal_nombre')
            ->distinct()
            ->get();
        $makes = Make::with('equipmenttype')
            ->join('equipment_types', 'makes.equipment_type_id', '=', 'equipment_types.id')
            ->where('makes.estado', 1)
            ->orderBy('equipment_types.nombre', 'asc')
            ->select('makes.*', 'equipment_types.nombre as equipmenttype_nombre')
            ->distinct()
            ->get();
        $teams = Team::with(['store.pdv'])
            ->select('teams.*', 'pdvs.nombre as pdv_nombre')
            ->join('stores', 'teams.store_id', '=', 'stores.id')
            ->join('pdvs', 'stores.pdv_id', '=', 'pdvs.id')
            ->where('teams.estado', 'USO')
            ->orderBy('pdvs.nombre', 'asc')
            ->get();

        return Inertia::render('Income/Accessory/Index', compact(['accessories', 'stores', 'makes', 'teams']));
    }

    public function store(AccessoryRequest $request): RedirectResponse
    {
        try {
            $data = $request->validated();

            $data['codigo_barras'] = $this->generateRandomNumber(14);

            if ($request->hasFile('documento') && $request->file('documento')->isValid()) {
                $documento = $request->file('documento');
                $extension = $documento->getClientOriginalExtension();
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx'];
                if (in_array($extension, $allowedExtensions)) {
                    $filename = time() . '.' . $extension;
                    // Guarda el archivo en el disco 'public' y especifica la carpeta 'documentos'
                    $documento->storeAs('documentos', $filename, 'public');
                    // Guarda solo el nombre del archivo en la base de datos
                    $data['documento'] = $filename;
                } else {
                    return redirect()->back()->with('toast', ['Tipo de archivo no permitido!', 'danger']);
                }
            }

            $accessory = new Accessory;
            $accessory->fill($data);
            $accessory->save();

            return redirect()->route('accessory.index')->with('toast', ['Accesorio creado exitosamente!', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['Ocurrió un error al guardar los datos!', 'danger']);
        }
    }

    public function update(AccessoryRequest $request, Accessory $accessory): RedirectResponse
    {
        try {
            $validatedData = $request->validated();

            $accessory->nombre = $validatedData['nombre'];
            $accessory->modelo = $validatedData['modelo'];
            $accessory->capacidad = $validatedData['capacidad'];
            $accessory->serie = $validatedData['serie'];
            $accessory->fecha_compra = $validatedData['fecha_compra'];
            $accessory->garantia_tienda = $validatedData['garantia_tienda'];
            $accessory->garantia_marca = $validatedData['garantia_marca'];
            $accessory->descripcion = $validatedData['descripcion'];
            $accessory->make_id = $validatedData['make_id'];
            $accessory->store_id = $validatedData['store_id'];

            $accessory->save();

            return redirect()->route('accessory.index')->with('toast', ['Accesorio actualizado exitosamente!', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['Ocurrió un error al actualizar los datos!', 'danger']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Accessory $accessory)
    {
        try {
            $accessory->delete();
            return redirect()->route('accessory.index')->with('toast', ['Accesorio eliminado exitosamente!', 'success']);
        } catch (QueryException $e) {
            if ($e->getCode() == 23000) {
                return redirect()->back()->with('toast', ['El Accesorio no se puede eliminar porque está siendo usado en otra tabla!', 'danger']);
            } else {
                return redirect()->back()->with('toast', ['Error al eliminar el Accesorio!', 'danger']);
            }
        }
    }

    private function generateRandomNumber($length = 14): string
    {
        $number = '';
        for ($i = 0; $i < $length; $i++) {
            $number .= random_int(0, 9);
        }
        return $number;
    }

    public function updateDocument(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'documento' => 'nullable|mimes:jpg,jpeg,png,doc,docx,pdf|max:3072',
        ]);
        $team = Accessory::findOrFail($id);

        if ($request->hasFile('documento') && $request->file('documento')->isValid()) {
            $documento = $request->file('documento');
            $extension = $documento->getClientOriginalExtension();
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx'];

            if (in_array($extension, $allowedExtensions)) {
                // Elimina el documento anterior si existe
                if ($team->documento) {
                    Storage::disk('public')->delete('documentos/' . $team->documento);
                }

                // Guarda el nuevo documento
                $filename = time() . '.' . $extension;
                $documento->storeAs('documentos', $filename, 'public');
                $team->documento = $filename;
                $team->save();

                return redirect()->route('accessory.index')->with('toast', ['Documento actualizado exitosamente!', 'success']);
            } else {
                return redirect()->back()->with('toast', ['Tipo de archivo no permitido!', 'danger']);
            }
        }

        return redirect()->back()->with('toast', ['No se proporcionó un documento válido.', 'danger']);
    }

    public function assignToTeam(Request $request): RedirectResponse
    {
        $request->validate([
            'accessory_id' => 'required|exists:accessories,id',
            'team_id' => 'required|exists:teams,id',
        ]);

        try {
            // Iniciar transacción para asegurar la integridad de los datos
            DB::beginTransaction();

            // Insertar la relación en la tabla accessory_team
            DB::table('accessory_team')->insert([
                'accessory_id' => $request->accessory_id,
                'team_id' => $request->team_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Actualizar el estado del accesorio
            $accessory = Accessory::find($request->accessory_id);
            $accessory->estado_asignado = 'ASIGNADO';
            $accessory->save();

            // Confirmar la transacción
            DB::commit();

            return redirect()->route('accessory.index')->with('toast', ['Accesorio asignado y actualizado exitosamente!', 'success']);
        } catch (QueryException $e) {
            // Revertir la transacción en caso de error
            DB::rollBack();
            return redirect()->back()->with('toast', ['Ocurrió un error al asignar el accesorio!', 'danger']);
        }
    }

    public function search(Request $request): Response
    {
        $texto = $request->get('texto');

        $accessories = Accessory::with(['store.pdv', 'make.equipmenttype', 'teams'])
            ->join('stores', 'accessories.store_id', '=', 'stores.id')
            ->join('pdvs', 'stores.pdv_id', '=', 'pdvs.id')
            ->where(function ($query) use ($texto) {
                $query->where('stores.nombre', 'like', '%' . $texto . '%')
                    ->orWhere('pdvs.nombre', 'like', '%' . $texto . '%')
                    ->orWhere('accessories.nombre', 'like', '%' . $texto . '%')
                    ->orWhere('accessories.estado_asignado', 'like', '%' . $texto . '%')
                    ->orWhere('accessories.estado', 'like', '%' . $texto . '%');
            })
            ->orderBy('accessories.id', 'desc')
            ->select('accessories.*', 'pdvs.nombre as pdv_nombre', 'stores.nombre as store_nombre')
            ->paginate(7)
            ->appends(['texto' => $texto]);

        $stores = Store::with(['pdv.zonal'])
            ->join('pdvs', 'stores.pdv_id', '=', 'pdvs.id')
            ->join('zonals', 'pdvs.zonal_id', '=', 'zonals.id')
            ->where('stores.estado', 1)
            ->orderBy('zonals.nombre', 'asc')
            ->select('stores.*', 'pdvs.nombre as pdv_nombre', 'zonals.nombre as zonal_nombre')
            ->distinct()
            ->get();

        $makes = Make::with('equipmenttype')
            ->join('equipment_types', 'makes.equipment_type_id', '=', 'equipment_types.id')
            ->where('makes.estado', 1)
            ->orderBy('equipment_types.nombre', 'asc')
            ->select('makes.*', 'equipment_types.nombre as equipmenttype_nombre')
            ->distinct()
            ->get();

        $teams = Team::with(['store.pdv'])
            ->select('teams.*', 'pdvs.nombre as pdv_nombre')
            ->join('stores', 'teams.store_id', '=', 'stores.id')
            ->join('pdvs', 'stores.pdv_id', '=', 'pdvs.id')
            ->where('teams.estado', 'USO')
            ->orderBy('pdvs.nombre', 'asc')
            ->get();

        return Inertia::render('Income/Accessory/Index', compact(['accessories', 'stores', 'makes', 'teams', 'texto']));
    }
}
