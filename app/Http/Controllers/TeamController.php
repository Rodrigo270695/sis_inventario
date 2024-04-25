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
use Illuminate\Support\Str;

class TeamController extends Controller
{

    public function index(): Response
    {
        $teams = Team::with(['accessories', 'store.pdv', 'make']) // Carga los accesorios y la tienda asociada
                      ->join('stores', 'teams.store_id', '=', 'stores.id')
                      ->orderBy('stores.nombre', 'asc') // Ordena por el nombre de la tienda
                      ->select('teams.*') // Evita seleccionar columnas de la tabla stores en el resultado final
                      ->paginate(7);
        $stores = Store::with('pdv.zonal')->where('estado', 1)->orderBy('nombre','asc')->get();
        $makes = Make::with('equipmenttype')->where('estado', 1)->orderBy('nombre','asc')->get();

        return Inertia::render('Income/Team/Index', compact(['teams','stores','makes']));
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
                    $path = $documento->storeAs('documentos', $filename, 'public');
                    $data['documento'] = 'storage/' . $path;
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
