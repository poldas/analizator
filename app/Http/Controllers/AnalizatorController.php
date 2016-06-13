<?php

/*
 * Taken from
 * https://github.com/laravel/framework/blob/5.2/src/Illuminate/Auth/Console/stubs/make/controllers/HomeController.stub
 */

namespace App\Http\Controllers;

use App\Logika\Analizator\Analizator;
use App\Obszar;
use App\Wynik;
use App\Analiza;
use App\Uczen;
use App\ParseAnalizaFileData;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use Storage;
use Illuminate\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\File;
use Illuminate\Database\QueryException;

/**
 * Class HomeController
 * @package App\Http\Controllers
 */
class AnalizatorController extends Controller
{

    /**
     * @var Analizator
     */
    private $analizator;

    public function __construct()
    {
        $this->analizator = new Analizator();
    }

    public function lista()
    {
        $analizy = $this->analizator->getAll();
        return view('analiza.lista', compact('analizy'));
    }

    public function show($id)
    {
        $daneWykresu = [];
        if ( Session::has('daneWykresu')) {
            $daneWykresu = Session::get('daneWykresu');
        }
        list($analiza, $uczniowie) = $this->analizator->getTest($id);
        if (!$analiza || !$uczniowie) {
            return redirect('analiza/lista');
        }
        return view('analiza.show', compact('analiza', 'uczniowie', 'daneWykresu'));
    }

    public function konfiguruj($id)
    {
        $this->analizator->konfiguruj($id);
        return redirect('analiza/show/'.$id);
    }

    public function parsuj($id)
    {
        $daneWykresu = $this->analizator->parseData($id);
//        return view('analiza.show', compact('daneWykresu'));
        return response()->json($daneWykresu);
        return redirect('analiza/wykresy/'.$id)->with('daneWykresu', $daneWykresu);
    }

    public function delete($id)
    {
        $this->analizator->deleteDataSet($id);
        return redirect('analiza/lista');
    }

    public function obszarDelete($id_analiza)
    {
       $this->analizator->obszarDelete($id_analiza);
        return redirect('analiza/show/'.$id_analiza);
    }

    public function wynikiDelete($id_analiza)
    {
        $this->analizator->wynikiDelete($id_analiza);
        return redirect('analiza/show/'.$id_analiza);
    }

    public function uczniowieDelete($id_analiza)
    {
        $this->analizator->uczniowieDelete($id_analiza);
        return redirect('analiza/show/'.$id_analiza);
    }

    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function createForm()
    {
        return view('analiza.create');
    }

    public function createNew()
    {
        $request = request();
        try{
            $this->validate($request, [
                'nazwa' => 'required|max:150',
                'file' => 'required',
            ]);
        } catch(ValidationException $e) {
            return redirect('analiza/create')
                ->withErrors($e->getMessage())
                ->withInput();
        }
        $this->analizator->createDataSet($request);
        return  redirect('analiza/lista');
    }
}