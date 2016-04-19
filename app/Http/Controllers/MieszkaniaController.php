<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class MieszkaniaController extends Controller
{
    public function index()
    {
        return view('mieszkania.mieszkania');
    }
}
