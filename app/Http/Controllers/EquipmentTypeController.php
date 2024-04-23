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

    /**
     * Display the specified resource.
     */
    public function show(EquipmentType $equipmentType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EquipmentType $equipmentType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EquipmentType $equipmentType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EquipmentType $equipmentType)
    {
        //
    }
}
