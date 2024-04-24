<?php

namespace App\Http\Controllers;

use App\Http\Requests\TeamRequest;
use App\Models\Make;
use App\Models\Store;
use App\Models\Team;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TeamController extends Controller
{

    public function index(): Response
    {
        $teams = Team::all();
        $stores = Store::with('pdv.zonal')->where('estado', 1)->orderBy('nombre','asc')->get();
        $makes = Make::with('equipmenttype')->where('estado', 1)->orderBy('nombre','asc')->get();

        return Inertia::render('Income/Team/Index', compact(['teams','stores','makes']));
    }

    public function store(TeamRequest $request): RedirectResponse
    {
        dd($request);
        try {
            $data = $request->all();

            // Verificar si el archivo de documento fue cargado y procesarlo
            if ($request->hasFile('documento')) {
                $documento = $request->file('documento');
                // Guardar el archivo en el disco especificado y obtener la ruta
                $path = $documento->store('documentos', 'public');
                // Agregar la ruta del archivo al array de datos
                $data['documento'] = $path;
            }

            // Crear el equipo con los datos procesados
            Team::create($data);

            return redirect()->route('team.index')->with('toast', ['Equipo creado exitosamente!', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['Ocurrió un error!', 'danger']);
        }
    }

    public function update(Request $request, Team $team)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Team $team)
    {
        //
    }
}
