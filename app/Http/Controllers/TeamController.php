<?php

namespace App\Http\Controllers;

use App\Http\Requests\TeamRequest;
use App\Models\Make;
use App\Models\Pdv;
use App\Models\Store;
use App\Models\Team;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Str;

class TeamController extends Controller
{

    public function index(): Response
    {
        $pdvs = Pdv::with(['stores.teams.accessories', 'stores.teams.make.equipmenttype'])
        ->orderBy('nombre', 'asc')
        ->paginate(2);
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

        return Inertia::render('Income/Team/Index', compact(['pdvs', 'stores', 'makes']));
    }

    public function store(TeamRequest $request): RedirectResponse
    {
        try {
            $data = $request->validated();

            $data['codigo_barras'] = Str::ulid();

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

            $team = new Team;
            $team->fill($data);
            $team->save();

            return redirect()->route('team.index')->with('toast', ['Equipo creado exitosamente!', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['Ocurrió un error al guardar los datos!', 'danger']);
        }
    }

    public function update(TeamRequest $request, Team $team)
    {
        try {
            $team->update($request->all());
            return redirect()->route('team.index')->with('toast', ['Equipo actualizado exitosamente!', 'success']);
        } catch (QueryException $e){
            return redirect()->back()->with('toast', ['Ocurrió un error!','danger']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Team $team)
    {
        //
    }


}
