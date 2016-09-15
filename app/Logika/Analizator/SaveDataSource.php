<?php
namespace App\Logika\Analizator;
use App\Analiza;
use Illuminate\Support\Facades\Storage;


/**
 * Zapisuje plik z danymi na dysku i tworzy wpis w bazie,
 * którego id jest jednocześnie id nowej analizy.
 *
 * @package App\Logika\Analizator
 */
class SaveDataSource {

    const STORE_DIRECTORY_NAME = 'analiza_csv';

    public function save($data, $fileToStore)
    {
        $savedData = $this->saveInDatabase($data);
        $this->saveFileInStorage($savedData, $fileToStore);
    }

    private function saveInDatabase($dataToSave)
    {
        $analiza = Analiza::create($dataToSave);
        $analiza->file_path = $this->createFilePathName($analiza->id, $analiza->file_path);
        $analiza->save();
        return $analiza;
    }

    private function saveFileInStorage($savedData, $fileToStore)
    {
        $isOk = Storage::disk('local')->put($savedData->file_path,
            file_get_contents($fileToStore->getRealPath())
        );

        return $isOk;
    }

    private function createFilePathName($id, $filePathName)
    {
        return self::STORE_DIRECTORY_NAME.'/'.$id.'-'.preg_replace("/\s/", '_', $filePathName);
    }
}