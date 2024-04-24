<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRequest;
use App\Models\Pdv;
use App\Models\Store;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class StoreController extends Controller
{

    public function index(): Response
    {
        $stores = Store::with('pdv.zonal')->orderBy('id','desc')->paginate(7);
        $pdvs = Pdv::with('zonal')->where('estado', 1)->orderBy('nombre', 'asc')->get();

        return Inertia::render('Zona/Store/Index',compact(['stores','pdvs']));
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        try {
            Store::create($request->all());
            return redirect()->route('store.index')->with('toast', ['Almacén creado exitosamente!','success']);
        } catch (QueryException $e) {

            if ($e->getCode() == 23000) {
                return redirect()->back()->with('toast', ['El almacén ya existe!','warning']);
            }else{
                return redirect()->back()->with('toast', ['Ocurrió un error!','danger']);
            }
        }
    }

    public function createStore(StoreRequest $request): RedirectResponse
    {
        try {
            Store::create($request->all());
            return redirect()->route('pdv.index')->with('toast', ['Almacén creado exitosamente!','success']);
        } catch (QueryException $e) {

            if ($e->getCode() == 23000) {
                return redirect()->back()->with('toast', ['El almacén ya existe!','warning']);
            }else{
                return redirect()->back()->with('toast', ['Ocurrió un error!','danger']);
            }
        }
    }

    public function update(StoreRequest $request, Store $store): RedirectResponse
    {
        try {
            $store->update($request->all());
            return redirect()->route('store.index')->with('toast', ['Almacén actualizado exitosamente!', 'success']);
        } catch (QueryException $e){
            if ($e->getCode() == 23000) {
                return redirect()->back()->with('toast', ['El Almacén ya existe!', 'warning']);
            }else{
                return redirect()->back()->with('toast', ['Ocurrió un error!','danger']);
            }
        }
    }

    public function change(Store $store): RedirectResponse
    {
        if ($store->estado == 1) {
            $store->estado = 0;
        }else {
            $store->estado = 1;
        }

        $store->save();
        return redirect()->route('store.index')->with('toast', ['cambio de estado exitosamente!', 'success']);
    }

    public function destroy(Store $store)
    {
        try {
            $store->delete();
            return redirect()->route('store.index')->with('toast', ['Almacén eliminado exitosamente!', 'success']);
        } catch (QueryException $e) {
            if ($e->getCode() == 23000) {
                return redirect()->back()->with('toast', ['El Almacén no se puede eliminar porque está siendo usado en otra tabla!', 'danger']);
            }else{
                return redirect()->back()->with('toast', ['Error al eliminar el Almacén!', 'danger']);
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

        $stores = Store::with('pdv.zonal')->join('pdvs', 'stores.pdv_id', '=', 'pdvs.id')
            ->orWhere('pdvs.nombre', 'like', '%' . $texto . '%')
            ->orWhere('stores.nombre', 'like', '%' . $texto . '%')
            ->orWhere('stores.estado', $estado)
            ->orderBy("stores.id","desc")
            ->select('stores.*')
            ->paginate(7)
            ->appends(['texto' => $texto]);

        return Inertia::render('Zona/Store/Index', compact('stores', 'texto'));
    }

}
