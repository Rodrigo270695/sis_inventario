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

    /**
     * Display the specified resource.
     */
    public function show(Pdv $pdv)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pdv $pdv)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pdv $pdv)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pdv $pdv)
    {
        //
    }
}
