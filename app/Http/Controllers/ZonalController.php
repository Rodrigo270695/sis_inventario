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
                return redirect()->back()->with('toast', ['El pdv ya existe!','warning']);
            }else{
                return redirect()->back()->with('toast', ['Ocurrió un error!','danger']);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Zonal $zonal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Zonal $zonal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Zonal $zonal)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Zonal $zonal)
    {
        //
    }
}
