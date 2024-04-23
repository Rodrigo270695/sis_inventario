<?php

namespace App\Http\Controllers;

use App\Http\Requests\MakeRequest;
use App\Models\EquipmentType;
use App\Models\Make;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MakeController extends Controller
{

    public function index(): Response
    {
        $makes = Make::with('equipmenttype')->orderBy('id','desc')->paginate(7);
        $types = EquipmentType::orderBy('nombre','asc')->get();

        return Inertia::render('Maker/Make/Index', compact(['makes','types']));
    }

    public function store(MakeRequest $request): RedirectResponse
    {
        try {
            Make::create($request->all());
            return redirect()->route('make.index')->with('toast', ['Marca creada exitosamente!','success']);
        } catch (QueryException $e) {

            if ($e->getCode() == 23000) {
                return redirect()->back()->with('toast', ['La marca ya existe!','warning']);
            }else{
                return redirect()->back()->with('toast', ['Ocurrió un error!','danger']);
            }
        }
    }

    public function update(Request $request, Make $make): RedirectResponse
    {
        try {
            $make->update($request->all());
            return redirect()->route('make.index')->with('toast', ['Marca actualizado exitosamente!', 'success']);
        } catch (QueryException $e){
            if ($e->getCode() == 23000) {
                return redirect()->back()->with('toast', ['La marca ya existe!', 'warning']);
            }else{
                return redirect()->back()->with('toast', ['Ocurrió un error!','danger']);
            }
        }
    }

    public function destroy(Make $make)
    {
        try {
            $make->delete();
            return redirect()->route('make.index')->with('toast', ['Marca eliminado exitosamente!', 'success']);
        } catch (QueryException $e) {
            if ($e->getCode() == 23000) {
                return redirect()->back()->with('toast', ['La Marca no se puede eliminar porque está siendo usado en otra tabla!', 'danger']);
            }else{
                return redirect()->back()->with('toast', ['Error al eliminar el pdv!', 'danger']);
            }
        }
    }

    public function change(Make $make): RedirectResponse
    {
        if ($make->estado == 1) {
            $make->estado = 0;
        }else {
            $make->estado = 1;
        }

        $make->save();
        return redirect()->route('make.index')->with('toast', ['cambio de estado exitosamente!', 'success']);
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

        $makes = Make::with('equipmenttype')->join('equipment_types', 'makes.equipment_type_id', '=', 'equipment_types.id')
            ->Where('equipment_types.nombre', 'like', '%' . $texto . '%')
            ->orWhere('makes.nombre', 'like', '%' . $texto . '%')
            ->orWhere('makes.estado', $estado)
            ->orderBy("makes.id","desc")
            ->select('makes.*')
            ->paginate(7)
            ->appends(['texto' => $texto]);

        return Inertia::render('Maker/Make/Index', compact('makes', 'texto'));
    }

}
