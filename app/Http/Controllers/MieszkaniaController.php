<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class MieszkaniaController extends Controller
{
    public function index()
    {
        $viewData = [];
        $viewData['list'] = [1,2,3,4,5];
        return view('mieszkania.mieszkania', compact('viewData'));
    }
}
