<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        return view ('Admin');
    }

    public function create()
    {
        return view ('Create');
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
