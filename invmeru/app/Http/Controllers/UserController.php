<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    /**
     * Cambiar el PIN de un usuario
     */ 
    public function cambiarPin(Request $request)
    {
        $request->validate([
            'usuario'  => 'required|string',
            'pinNuevo' => 'required|digits:4',
        ]);

        $user = User::where('usuario', $request->usuario)->first();

        if (!$user) {
            return back()->with('error', 'El usuario no existe.');
        }

        // El mutator en User se encargará de encriptar
        $user->password = $request->pinNuevo;
        $user->save();

        return back()->with('success', 'El PIN del usuario ' . $request->usuario . ' fue actualizado correctamente.');
    }

    /**
     * Mostrar lista de usuarios
     */
    public function index()
    {
        $users = User::all();
        return view('ChangePin', compact('users'));
    }

    /**
     * Formulario para crear usuario
     */
    public function create()
    {
        return view('Createuser');
    }

    /**
     * Guardar nuevo usuario
     */
    public function store(Request $request)
    {
        $request->validate([
            'usuario' => 'required|string|unique:users,usuario',
            'rol'     => 'required|in:admin,usuario',
            'pin'     => 'required|digits:4|confirmed', 
        ]);

        User::create([
            'usuario'  => $request->usuario,
            'rol'      => $request->rol,
            'password' => $request->pin, 
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuario creado correctamente.');
    }

        /**
         * Formulario para editar usuario
          */
    public function edit(User $user)
    {
        return view('Edituser', compact('user'));
    }

        /**
         * Actualizar usuario
         */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'usuario' => 'required|string|unique:users,usuario,' . $user->id,
            'pin'     => 'nullable|digits:4|confirmed',
        ]);

        $user->usuario = $request->usuario;

        if ($request->filled('pin')) {
            $user->password = $request->pin; 
        }

        $user->save();

        return redirect()->route('usuarios.index')->with('success', 'Usuario se ha actualizado correctamente.');
    }

    //Activar - Desactivar
    public function toggleEstado(User $user)
    {
        $user->estado = !$user->estado; 
        $user->save();

        return redirect()->route('usuarios.index')->with('success', 'Estado del usuario actualizado correctamente.');
    }
    
    //Proteccion con usuario desactivado

    protected function credentials(Request $request)
    {
        return $request->only('usuario', 'password');
    }

    protected function guard()
    {
        return auth()->guard();
    }

    protected function attemptLogin(Request $request)
    {
        $credentials = $this->credentials($request);

    return Auth::attempt(
        array_merge($credentials, ['estado' => 1]),
        $request->filled('remember')
    );

}


}
