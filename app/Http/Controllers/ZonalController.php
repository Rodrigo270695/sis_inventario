<?php

namespace App\Http\Controllers;

use App\Http\Requests\ZonalRequest;
use App\Models\Zonal;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ZonalController extends Controller
{

    public function index(): Response
    {
        $zonals = Zonal::orderBy('id', 'desc')->paginate(7);
        return Inertia::render('Zona/Zonal/Index', compact('zonals'));
    }

    public function store(ZonalRequest $request): RedirectResponse
    {
        try {
            Zonal::create($request->all());
            return redirect()->route('zonal.index')->with('toast', ['Zonal creado exitosamente!','success']);
        } catch (QueryException $e) {

            if ($e->getCode() == 23000) {
                return redirect()->back()->with('toast', ['El Zonal ya existe!','warning']);
            }else{
                return redirect()->back()->with('toast', ['Ocurrió un error!','danger']);
            }
        }
    }


    public function update(ZonalRequest $request, Zonal $zonal)
    {
        try {
            $zonal->update($request->all());
            return redirect()->route('zonal.index')->with('toast', ['Zonal actualizado exitosamente!', 'success']);
        } catch (QueryException $e){
            if ($e->getCode() == 23000) {
                return redirect()->back()->with('toast', ['El Pdv ya existe!', 'warning']);
            }else{
                return redirect()->back()->with('toast', ['Ocurrió un error!','danger']);
            }
        }
    }

    public function change(Zonal $zonal): RedirectResponse
    {

        if ($zonal->estado == 1) {
            $zonal->estado = 0;
        }else {
            $zonal->estado = 1;
        }

        $zonal->save();
        return redirect()->route('zonal.index')->with('toast', ['cambio de estado exitosamente!', 'success']);

    }

    public function destroy(Zonal $zonal): RedirectResponse
    {
        try {
            $zonal->delete();
            return redirect()->route('zonal.index')->with('toast', ['Zonal eliminado exitosamente!', 'success']);
        } catch (QueryException $e) {
            if ($e->getCode() == 23000) {
                return redirect()->back()->with('toast', ['El Zonal no se puede eliminar porque está siendo usado en otra tabla!', 'danger']);
            }else{
                return redirect()->back()->with('toast', ['Error al eliminar el zonal!', 'danger']);
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

        $zonals = Zonal::where('unidad_negocio', 'like', '%' . $texto . '%')
            ->orWhere('nombre', 'like', '%' . $texto . '%')
            ->orWhere('estado', $estado)
            ->orderBy("id","desc")
            ->paginate(7)
            ->appends(['texto' => $texto]);

        return Inertia::render('Zona/Zonal/Index', compact('zonals', 'texto'));
    }

}
