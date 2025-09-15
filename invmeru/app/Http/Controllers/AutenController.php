<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AutenController extends Controller
{
    public function mostrarLogin()
    {
        return view('login'); 
    }

    public function procesarLogin(Request $request)
    {
        $credentials = $request->validate([
            'usuario' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            session(['rol' => Auth::user()->rol]);

            return redirect()->route('home');
        }

        return back()->with('error', 'Credenciales incorrectas');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
