<?php

namespace App\Http\Controllers;

use App\Http\Requests\NormRequest;
use App\Models\Make;
use App\Models\Norm;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class NormController extends Controller
{

    public function index(): Response
    {
        $models = Norm::with('make.equipmenttype')->orderBy('id','desc')->paginate(7);
        $makes = Make::with('equipmenttype')->where('estado', 1)->orderBy('nombre', 'asc')->get();

        return Inertia::render('Maker/Model/Index', compact('models','makes'));
    }

    public function store(NormRequest $request): RedirectResponse
    {
        try {
            Norm::create($request->all());
            return redirect()->route('model.index')->with('toast', ['Modelo creado exitosamente!','success']);
        } catch (QueryException $e) {

            if ($e->getCode() == 23000) {
                return redirect()->back()->with('toast', ['El Modelo ya existe!','warning']);
            }else{
                return redirect()->back()->with('toast', ['Ocurrió un error!','danger']);
            }
        }
    }

    public function createModel(NormRequest $request): RedirectResponse
    {
        try {
            Norm::create($request->all());
            return redirect()->route('make.index')->with('toast', ['Modelo creado exitosamente!','success']);
        } catch (QueryException $e) {

            if ($e->getCode() == 23000) {
                return redirect()->back()->with('toast', ['El Modelo ya existe!','warning']);
            }else{
                return redirect()->back()->with('toast', ['Ocurrió un error!','danger']);
            }
        }
    }

    public function update(NormRequest $request, Norm $model): RedirectResponse
    {
        try {
            $model->update($request->all());
            return redirect()->route('model.index')->with('toast', ['Modelo actualizado exitosamente!', 'success']);
        } catch (QueryException $e){
            if ($e->getCode() == 23000) {
                return redirect()->back()->with('toast', ['El Modelo ya existe!', 'warning']);
            }else{
                return redirect()->back()->with('toast', ['Ocurrió un error!','danger']);
            }
        }
    }

    public function change(Norm $model): RedirectResponse
    {
        if ($model->estado == 1) {
            $model->estado = 0;
        }else {
            $model->estado = 1;
        }

        $model->save();
        return redirect()->route('model.index')->with('toast', ['cambio de estado exitosamente!', 'success']);
    }

    public function destroy(Norm $model): RedirectResponse
    {
        try {
            $model->delete();
            return redirect()->route('model.index')->with('toast', ['Modelo eliminado exitosamente!', 'success']);
        } catch (QueryException $e) {
            if ($e->getCode() == 23000) {
                return redirect()->back()->with('toast', ['El Modelo no se puede eliminar porque está siendo usado en otra tabla!', 'danger']);
            }else{
                return redirect()->back()->with('toast', ['Error al eliminar el Modelo!', 'danger']);
            }
        }
    }

    public function search(Request $request): Response
    {
        $texto = $request->get('texto');
        $estado = null;

        if (strtolower($texto) === 'activo') {
            $estado = 1;
        } elseif (strtolower($texto) === 'inactivo') {
            $estado = 0;
        }

        $models = Norm::with('make.equipmenttype')
            ->join('makes', 'norms.make_id', '=', 'makes.id')
            ->join('equipment_types', 'makes.equipment_type_id', '=', 'equipment_types.id')
            ->where(function($query) use ($texto, $estado) {
                $query->where('equipment_types.nombre', 'like', '%' . $texto . '%')
                      ->orWhere('makes.nombre', 'like', '%' . $texto . '%');
                if (is_null($estado)) {
                    $query->orWhere('norms.nombre', 'like', '%' . $texto . '%');
                }
            })
            ->when(!is_null($estado), function ($query) use ($estado) {
                $query->orWhere('norms.estado', $estado);
            })
            ->orderBy("norms.id","desc")
            ->select('norms.*')
            ->paginate(7)
            ->appends(['texto' => $texto]);

        return Inertia::render('Maker/Model/Index', compact('models', 'texto'));
    }
}
