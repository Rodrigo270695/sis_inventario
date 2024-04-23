<?php

namespace App\Http\Controllers;

use App\Http\Requests\EquipmentTypeRequest;
use App\Models\EquipmentType;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class EquipmentTypeController extends Controller
{

    public function index(): Response
    {
        $types = EquipmentType::orderBy('id', 'desc')->paginate(7);
        return Inertia::render('Maker/Type/Index', compact('types'));
    }

    public function store(EquipmentTypeRequest $request)
    {
        try {
            EquipmentType::create($request->all());
            return redirect()->route('type.index')->with('toast', ['Tipo creado exitosamente!','success']);
        } catch (QueryException $e) {

            if ($e->getCode() == 23000) {
                return redirect()->back()->with('toast', ['El tipo ya existe!','warning']);
            }else{
                return redirect()->back()->with('toast', ['Ocurrió un error!','danger']);
            }
        }
    }

    public function createType(EquipmentTypeRequest $request)
    {
        try {
            EquipmentType::create($request->all());
            return redirect()->route('make.index')->with('toast', ['Tipo creado exitosamente!','success']);
        } catch (QueryException $e) {

            if ($e->getCode() == 23000) {
                return redirect()->back()->with('toast', ['El tipo ya existe!','warning']);
            }else{
                return redirect()->back()->with('toast', ['Ocurrió un error!','danger']);
            }
        }
    }

    public function update(EquipmentTypeRequest $request, EquipmentType $type)
    {
        try {
            $type->update($request->all());
            return redirect()->route('type.index')->with('toast', ['Tipo actualizado exitosamente!', 'success']);
        } catch (QueryException $e){
            if ($e->getCode() == 23000) {
                return redirect()->back()->with('toast', ['El Tipo ya existe!', 'warning']);
            }else{
                return redirect()->back()->with('toast', ['Ocurrió un error!','danger']);
            }
        }
    }

    public function destroy(EquipmentType $type)
    {
        try {
            $type->delete();
            return redirect()->route('type.index')->with('toast', ['Tipo eliminado exitosamente!', 'success']);
        } catch (QueryException $e) {
            if ($e->getCode() == 23000) {
                return redirect()->back()->with('toast', ['El Tipo no se puede eliminar porque está siendo usado en otra tabla!', 'danger']);
            }else{
                return redirect()->back()->with('toast', ['Error al eliminar el Tipo!', 'danger']);
            }
        }
    }

    public function search(Request $request)
    {
        $texto = $request->get('texto');
        $types = EquipmentType::where('nombre', 'like', '%' . $texto . '%')
            ->orderBy("id","desc")
            ->paginate(7)
            ->appends(['texto' => $texto]);

        return Inertia::render('Maker/Type/Index', compact('types', 'texto'));
    }

}
