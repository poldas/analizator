<?php

/*
 * Taken from
 * https://github.com/laravel/framework/blob/5.2/src/Illuminate/Auth/Console/stubs/make/controllers/HomeController.stub
 */

namespace App\Http\Controllers;

use Carbon\Carbon;
use Storage;
use App\Analiza;
use Illuminate\Http\Request;

/**
 * Class HomeController
 * @package App\Http\Controllers
 */
class AnalizatorController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function create(Request $request)
    {
        return view('analiza.create');
    }

    public function przegladaj()
    {
        $analizy = Analiza::all();
        return view('analiza.przegladaj', compact('analizy'));
    }

    public function showAnaliza($id)
    {
        $analiza = Analiza::find($id);
        return view('analiza.show', compact('analiza'));
    }

    public function createNewAnaliza()
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
        $request['file_path'] = $file_storage_name = $request->file('file')->getClientOriginalName();
        Storage::disk('local')->put(
            'analiza_csv/'.$file_storage_name,
            file_get_contents($request->file('file')->getRealPath())
        );
        $analiza = Analiza::create($request->all());
        $analiza->file_path = $file_storage_name.'-'.$analiza->id;
        $analiza->save();
        request()->session()->put('alert-success', 'Dane zapisane');
        return  redirect('analiza/przegladaj');
    }

    public function deleteAnaliza($id)
    {
        $analiza = Analiza::where('id', $id)->first();
        $i = Storage::disk('local')->delete('analiza_csv/'.$analiza->file_path)
            && $analiza->delete();
        if ($i) {
            $type = 'alert-success';
            $message = "Udało się usunąć ".$analiza->file_path;
        } else {
            $type = 'alert-danger';
            $message = "Nie udało się usunąć ".$analiza->file_path;
        }
        request()->session()->put($type, $message);
        return redirect('analiza/przegladaj');
    }
}