<?php

namespace App\Http\Controllers;

use App\Models\Accessory;
use App\Models\Make;
use App\Models\Pdv;
use App\Models\Store;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AccessoryController extends Controller
{

    public function index(): Response
    {
        $pdvs = Pdv::with(['stores.teams.accessories', 'stores.teams.make.equipmenttype'])
        ->orderBy('nombre', 'asc')
        ->paginate(1);
    $stores = Store::with(['pdv.zonal'])
        ->join('pdvs', 'stores.pdv_id', '=', 'pdvs.id')
        ->join('zonals', 'pdvs.zonal_id', '=', 'zonals.id')
        ->where('stores.estado', 1)
        ->orderBy('zonals.nombre', 'asc')
        ->select('stores.*', 'pdvs.nombre as pdv_nombre', 'zonals.nombre as zonal_nombre')
        ->distinct()
        ->get();
    $makes = Make::with('equipmenttype')
        ->join('equipment_types', 'makes.equipment_type_id', '=', 'equipment_types.id')
        ->where('makes.estado', 1)
        ->orderBy('equipment_types.nombre', 'asc')
        ->select('makes.*', 'equipment_types.nombre as equipmenttype_nombre')
        ->distinct()
        ->get();

    return Inertia::render('Income/Accessory/Index', compact(['pdvs', 'stores', 'makes']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Accessory $accessory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Accessory $accessory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Accessory $accessory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Accessory $accessory)
    {
        //
    }
}
