<?php

namespace App\Http\Controllers;

use App\Models\Repuesto;
use Illuminate\Http\Request;

class ConsultsController extends Controller
{
    // Vista para admin
    public function indexadmin()
    {
        return view('Consults'); 
    }

    // Vista para usuario normal
    public function indexnormal()
    {
        return view('Consultsn'); 
    }

    public function consultarAdmin(Request $request)
    {
        $request->validate([
            'nombreConsulta' => 'required|string|max:255',
        ]);

        $nombre = trim($request->input('nombreConsulta'));

        $resultado = Repuesto::where('nombre', 'like', '%' . $nombre . '%')->first();

        return view('Consults', compact('resultado'));
    }

    public function consultarNormal(Request $request)
    {
        $request->validate([
            'nombreConsulta' => 'required|string|max:255',
        ]);

        $nombre = trim($request->input('nombreConsulta'));

        $resultado = Repuesto::where('nombre', 'like', '%' . $nombre . '%')->first();

        return view('Consultsn', compact('resultado', 'nombre'));
    }
}