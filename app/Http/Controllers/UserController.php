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
        $users = User::where('id', '!=', $currentUser->id)->with(['roles', 'pdv.zonal'])->orderBy('id', 'desc')->paginate(7);
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

            $user->assignRole($request->rol_id);

            return redirect()->route('user.index')->with('toast', ['Usuario actualizado exitosamente!', 'success']);
        } catch (QueryException $e) {
            dd($e);
            return redirect()->back()->with('toast', ['Ocurrió un error!', 'danger']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
