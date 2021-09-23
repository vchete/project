<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use AppCrudController;

class InicioController extends AppCrudController
{
    public function index(Request $request)
    {
        return view('home.index');
    }
}