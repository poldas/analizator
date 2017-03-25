<?php
namespace App\Http\Controllers;
use App\Logika\Analizator\Analizator;
use App\Analiza;
use App\ParseAnalizaFileData;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use Storage;
use Illuminate\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\File;
use Symfony\Component\HttpFoundation\Request;

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

    public function create()
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
        $this->analizator->createDataSetFromRequest($request);
        return  redirect('analiza/lista');
    }

    public function __construct()
    {
        $this->middleware('cors');
        $this->analizator = new Analizator();
    }

    public function lista()
    {
        $analizy = $this->analizator->getAllAnalize();
        return view('analiza.lista', compact('analizy'));
    }

    public function show($id)
    {
        $daneWykresu = [];
        if ( Session::has('daneWykresu')) {
            $daneWykresu = Session::get('daneWykresu');
        }
        list($analiza, $uczniowie) = $this->analizator->getAnalizeById($id);
        if (!$analiza || !$uczniowie) {
            return redirect('analiza/lista');
        }
        return view('analiza.show', compact('analiza', 'uczniowie', 'daneWykresu'));
    }

    public function konfiguruj($id)
    {
        $this->analizator->addData($id);
        return redirect('analiza/show/'.$id);
    }

    public function parsuj($id)
    {
        $daneWykresu = $this->analizator->parseData($id);
        return response()->json($daneWykresu);
//        return redirect('analiza/wykresy/'.$id)->with('daneWykresu', $daneWykresu);
    }

    public function delete(Request $request, $id)
    {
        if ($request->has('onlyData') ) {
            $this->analizator->wynikiDelete($id);
            $this->analizator->obszarDelete($id);
            $this->analizator->uczniowieDelete($id);
        } else {
            $analiza = Analiza::where('id', $id)->first();
            $i = Storage::disk('local')->delete($analiza->file_path)
                && $analiza->delete();
            if ($i) {
                $type = 'alert-success';
                $message = "Plik ".$analiza->file_path." i dane zostały usunięte.";
            } else {
                $type = 'alert-danger';
                $message = "Nie udało się usunąć ".$analiza->file_path;
            }
            request()->session()->put($type, $message);
        }
        return redirect('analiza/show/'.$id);
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
}