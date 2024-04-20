<?php

namespace App\Http\Controllers;

use App\Models\Pdv;
use App\Models\Store;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class StoreController extends Controller
{

    public function index(): Response
    {
        $stores = Store::with('pdv')->orderBy('id','desc')->paginate(7);
        $pdvs = Pdv::where('estado', 1)->orderBy('nombre', 'asc')->get();

        return Inertia::render('Zona/Store/Index',compact(['stores','pdvs']));
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
    public function show(Store $store)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Store $store)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Store $store)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Store $store)
    {
        //
    }
}
