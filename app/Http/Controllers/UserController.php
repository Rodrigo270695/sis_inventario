<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Pdv;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{

    public function index(): Response
    {
        $currentUser = auth()->user();
        if ($currentUser) {
            $users = User::with(['roles', 'pdv.zonal'])
                ->where('id', '!=', $currentUser->id)
                ->orderBy('id', 'desc')
                ->paginate(7);
        } else {
            $users = User::with(['roles', 'pdv.zonal'])
                ->orderBy('id', 'desc')
                ->paginate(7);
        }
        $roles = Role::orderBy('name', 'asc')->get();
        $pdvs = Pdv::with('zonal')->where('estado', 1)->orderBy('nombre', 'asc')->get();

        return Inertia::render('User/Index', compact('users', 'roles', 'pdvs'));
    }

    public function store(UserRequest $request): RedirectResponse
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'dni' => $request->dni,
                'email' => $request->email,
                'pdv_id' => $request->pdv_id,
                'password' => Hash::make($request->dni),
            ]);

            $user->assignRole($request->rol);

            return redirect()->route('user.index')->with('toast', ['Usuario actualizado exitosamente!', 'success']);
        } catch (QueryException $e) {
            dd($e);
            return redirect()->back()->with('toast', ['Ocurrió un error!', 'danger']);
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->update([
                'name' => $request->name,
                'dni' => $request->dni,
                'email' => $request->email,
                'pdv_id' => $request->pdv_id,
            ]);

            $user->syncRoles($request->rol);

            return redirect()->route('user.index')->with('toast', ['Usuario actualizado exitosamente!', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['Ocurrió un error al actualizar el usuario!', 'danger']);
        }
    }

    public function change(User $user): RedirectResponse
    {
        if ($user->estado == 1) {
            $user->estado = 0;
        } else {
            $user->estado = 1;
        }

        $user->save();
        return redirect()->route('user.index')->with('toast', ['cambio de estado exitosamente!', 'success']);
    }

    public function destroy(User $user)
    {
        try {
            $user->delete();
            return redirect()->route('user.index')->with('toast', ['Usuario eliminado exitosamente!', 'success']);
        } catch (QueryException $e) {
            if ($e->getCode() == 23000) {
                return redirect()->back()->with('toast', ['El Usuario no se puede eliminar porque está siendo usado en otra tabla!', 'danger']);
            } else {
                return redirect()->back()->with('toast', ['Error al eliminar el pdv!', 'danger']);
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

        $currentUser = auth()->user();
        $users = User::with(['roles', 'pdv.zonal'])
            ->where(function ($query) use ($texto, $estado) {
                $query->where('name', 'like', '%' . $texto . '%')
                    ->orWhere('email', 'like', '%' . $texto . '%')
                    ->orWhere('dni', 'like', '%' . $texto . '%')
                    ->orWhere('estado', $estado)
                    ->orWhereHas('pdv', function ($q) use ($texto) {
                        $q->where('nombre', 'like', '%' . $texto . '%');
                    })
                    ->orWhereHas('pdv.zonal', function ($q) use ($texto) {
                        $q->where('nombre', 'like', '%' . $texto . '%');
                    })
                    ->orWhereHas('roles', function ($q) use ($texto) {
                        $q->where('name', 'like', '%' . $texto . '%');
                    });
            })
            ->where('id', '!=', $currentUser->id) // Excluir al usuario actual de los resultados
            ->orderBy('id', 'desc')
            ->paginate(7)
            ->appends(['texto' => $texto]);

        return Inertia::render('User/Index', compact('users', 'texto'));
    }
}
