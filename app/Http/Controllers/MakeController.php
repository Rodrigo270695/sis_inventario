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

    public function update(Request $request, Make $make)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Make $make)
    {
        //
    }
}
