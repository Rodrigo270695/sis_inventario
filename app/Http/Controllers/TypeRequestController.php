<?php

namespace App\Http\Controllers;

use App\Http\Requests\TypeRequestO;
use App\Models\TypeRequest;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TypeRequestController extends Controller
{

    public function index(): Response
    {
        $types = TypeRequest::orderBy('id', 'desc')->paginate(7);
        return Inertia::render('Operation/Type/Index', compact('types'));
    }

    public function store(TypeRequestO $request): RedirectResponse
    {
        try {
            TypeRequest::create($request->all());
            return redirect()->route('typer.index')->with('toast', ['Tipo creado exitosamente!', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['Ocurrió un error!', 'danger']);
        }
    }

    public function update(TypeRequestO $request, TypeRequest $typer): RedirectResponse
    {
        try {
            $typer->update($request->all());
            return redirect()->route('typer.index')->with('toast', ['Tipo actualizado exitosamente!', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['Ocurrió un error!', 'danger']);
        }
    }
    public function destroy(TypeRequest $typer)
    {
        try {
            $typer->delete();
            return redirect()->route('typer.index')->with('toast', ['Tipo eliminado exitosamente!', 'success']);
        } catch (QueryException $e) {
            if ($e->getCode() == 23000) {
                return redirect()->back()->with('toast', ['El Tipo no se puede eliminar porque está siendo usado en otra tabla!', 'danger']);
            } else {
                return redirect()->back()->with('toast', ['Error al eliminar el Tipo!', 'danger']);
            }
        }
    }

    public function change(TypeRequest $type): RedirectResponse
    {
        if ($type->estado == 1) {
            $type->estado = 0;
        }else {
            $type->estado = 1;
        }

        $type->save();
        return redirect()->route('typer.index')->with('toast', ['cambio de estado exitosamente!', 'success']);
    }

    public function search(Request $request)
    {
        $texto = $request->get('texto');
        $estado = null;

        if (strtolower($texto) === 'activo') {
            $estado = 1;
        } elseif (strtolower($texto) === 'inactivo') {
            $estado = 0;
        }

        $types = TypeRequest::where('nombre', 'like', '%' . $texto . '%')
            ->orWhere('estado', $estado)
            ->orderBy("id", "desc")
            ->paginate(7)
            ->appends(['texto' => $texto]);

        return Inertia::render('Operation/Type/Index', compact('types', 'texto'));
    }
}
