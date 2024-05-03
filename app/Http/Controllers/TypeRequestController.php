<?php

namespace App\Http\Controllers;

use App\Http\Requests\TypeRequestO;
use App\Models\TypeRequest;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TypeRequest $typeRequest)
    {
        //
    }
}
