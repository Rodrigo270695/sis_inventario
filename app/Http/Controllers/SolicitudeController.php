<?php

namespace App\Http\Controllers;

use App\Http\Requests\SolicitudeRequest;
use App\Models\Solicitude;
use App\Models\TypeRequest;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SolicitudeController extends Controller
{

    public function index(): Response
    {

        $currentUser = auth()->user();
        $query = Solicitude::with(['typerequest', 'pdv']);

        if ($currentUser->hasAnyRole(['Administrador', 'Aprobador'])) {
            $query->where('aprobacion_local', 1);
        } elseif ($currentUser->hasAnyRole(['Supervisor 1', 'Supervisor 2'])) {
            $query->where('pdv_id', $currentUser->pdv_id);
        }

        $solicitudes = $query->orderBy('id', 'desc')->paginate(7);
        $types = TypeRequest::where('estado', 1)->orderBy('nombre', 'asc')->get();

        return Inertia::render('Operation/Solicitude/Index', compact('solicitudes', 'types'));
    }

    public function store(SolicitudeRequest $request): RedirectResponse
    {
        $currentUser = auth()->user();
        $data = $request->validated();
        $data['pdv_id'] = $currentUser->pdv_id;

        try {
            Solicitude::create($data);
            return redirect()->route('solicitude.index')->with('toast', ['Solicitud creada exitosamente!', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['Ocurrió un error al crear la solicitud!', 'danger']);
        }
    }

    public function update(SolicitudeRequest $request, Solicitude $solicitude)
    {
        if ($solicitude->aprobacion_local === 0) {
            $data = $request->validated();

            try {
                $solicitude->update($data);
                return redirect()->route('solicitude.index')->with('toast', ['Solicitud actualizada exitosamente!', 'success']);
            } catch (QueryException $e) {
                return redirect()->back()->with('toast', ['Ocurrió un error al actualizar la solicitud!', 'danger']);
            }
        } else {
            return redirect()->back()->with('toast', ['La solicitud no se puede actualizar porque ya está aprobada!', 'warning']);
        }
    }

    public function approve(Solicitude $solicitude)
    {
        if ($solicitude->aprobacion_local === 0) {
            $solicitude->update(['aprobacion_local' => 1]);
            return redirect()->route('solicitude.index')->with('toast', ['Solicitud aprobada exitosamente!', 'success']);
        } else {
            return redirect()->back()->with('toast', ['La solicitud ya está aprobada o rechazada!', 'warning']);
        }
    }

    public function reject(Solicitude $solicitude)
    {
        if ($solicitude->aprobacion_local === 0) {
            $solicitude->update(['aprobacion_local' => 2]);
            return redirect()->route('solicitude.index')->with('toast', ['Solicitud rechazada exitosamente!', 'success']);
        } else {
            return redirect()->back()->with('toast', ['La solicitud ya está aprobada o rechazada!', 'warning']);
        }
    }

    public function approveManagement(Solicitude $solicitude)
    {
        if ($solicitude->aprobacion_gerencia === 0) {
            $solicitude->update(['aprobacion_gerencia' => 1]);
            return redirect()->route('solicitude.index')->with('toast', ['Solicitud aprobada por la gerencia exitosamente!', 'success']);
        } else {
            return redirect()->back()->with('toast', ['La solicitud ya está aprobada o rechazada por la gerencia!', 'warning']);
        }
    }

    public function rejectManagement(Solicitude $solicitude)
    {
        if ($solicitude->aprobacion_gerencia === 0) {
            $solicitude->update(['aprobacion_gerencia' => 2]);
            return redirect()->route('solicitude.index')->with('toast', ['Solicitud rechazada por la gerencia exitosamente!', 'success']);
        } else {
            return redirect()->back()->with('toast', ['La solicitud ya está aprobada o rechazada por la gerencia!', 'warning']);
        }
    }

    public function destroy(Solicitude $solicitude)
    {
        //
    }
}
