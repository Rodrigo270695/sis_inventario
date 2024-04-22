<?php

namespace App\Http\Controllers;

use App\Http\Requests\PdvRequest;
use App\Models\Pdv;
use App\Models\Zonal;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PdvController extends Controller
{

    public function index(): Response
    {
        $pdvs = Pdv::with('zonal')->orderBy('id','desc')->paginate(7);
        $zonals = Zonal::where('estado', 1)->orderBy('nombre', 'asc')->get();

        return Inertia::render('Zona/Pdv/Index',compact(['pdvs','zonals']));
    }

    public function store(PdvRequest $request): RedirectResponse
    {
        try {
            Pdv::create($request->all());
            return redirect()->route('pdv.index')->with('toast', ['Pdv creado exitosamente!','success']);
        } catch (QueryException $e) {

            if ($e->getCode() == 23000) {
                return redirect()->back()->with('toast', ['El pdv ya existe!','warning']);
            }else{
                return redirect()->back()->with('toast', ['Ocurrió un error!','danger']);
            }
        }
    }

    public function update(PdvRequest $request, Pdv $pdv): RedirectResponse
    {
        try {
            $pdv->update($request->all());
            return redirect()->route('pdv.index')->with('toast', ['Pdv actualizado exitosamente!', 'success']);
        } catch (QueryException $e){
            if ($e->getCode() == 23000) {
                return redirect()->back()->with('toast', ['El Pdv ya existe!', 'warning']);
            }else{
                return redirect()->back()->with('toast', ['Ocurrió un error!','danger']);
            }
        }
    }

    public function change(Pdv $pdv): RedirectResponse
    {
        if ($pdv->estado == 1) {
            $pdv->estado = 0;
        }else {
            $pdv->estado = 1;
        }

        $pdv->save();
        return redirect()->route('pdv.index')->with('toast', ['cambio de estado exitosamente!', 'success']);
    }

    public function destroy(Pdv $pdv)
    {
        try {
            $pdv->delete();
            return redirect()->route('pdv.index')->with('toast', ['Pdv eliminado exitosamente!', 'success']);
        } catch (QueryException $e) {
            if ($e->getCode() == 23000) {
                return redirect()->back()->with('toast', ['El Pdv no se puede eliminar porque está siendo usado en otra tabla!', 'danger']);
            }else{
                return redirect()->back()->with('toast', ['Error al eliminar el pdv!', 'danger']);
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

        $pdvs = Pdv::with('zonal')->join('zonals', 'pdvs.zonal_id', '=', 'zonals.id')
            ->where('zonals.unidad_negocio', 'like', '%' . $texto . '%')
            ->orWhere('zonals.nombre', 'like', '%' . $texto . '%')
            ->orWhere('pdvs.nombre', 'like', '%' . $texto . '%')
            ->orWhere('pdvs.estado', $estado)
            ->orderBy("pdvs.id","desc")
            ->select('pdvs.*')
            ->paginate(7)
            ->appends(['texto' => $texto]);

        return Inertia::render('Zona/Pdv/Index', compact('pdvs', 'texto'));
    }

}
