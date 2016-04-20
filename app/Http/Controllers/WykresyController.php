<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class WykresyController extends Controller
{
    public function wykresy()
    {
        return view('analiza.wykresy');
    }
}
