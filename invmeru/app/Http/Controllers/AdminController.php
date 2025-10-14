<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Deposito;

class AdminController extends Controller
{
    public function index()
    {
        return view ('Admin');
    }

    public function create()
    {
        $depositos = Deposito::all();
        return view('create', compact('depositos')); // ← Asegúrate de que el nombre de la vista sea 'create'
    }

    public function addExistence()
    {
        return view ('Add');
    }

    public function deleteRecord()
    {
        return view ('Delete');
    }

    public function editPin()
    {
        return view ('ChangePin');
    }
}
