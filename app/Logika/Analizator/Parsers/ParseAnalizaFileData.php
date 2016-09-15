<?php
namespace App\Logika\Analizator\Parsers;
/**
 * Parsuje dane testu podanego z pliku csv, do postaci tablicy dającej się zapisać w bazie mysql.
 *
 * @package App\Logika\Analizator
 */
class ParseAnalizaFileData {

    const LOKALIZACJA_PATTERN = ['m', 'w', 'mi', 'wi'];
    const PLEC_PATTERN = ['K', 'M', 'C', 'D', 'c', 'd'];
    const DYSLEKSJA_PATTERN = ['T', 'N', 't', 'n'];

    public $dane_obszar = [];
    public $dane_uczniowie = [];
    public $dane_wyniki_egzaminu = [];

    private $analiza_id = 0;
    private $csvDataToParse;
    private $nazwy_zadan;
    private $nazwy_obszarow;
    private $nazwy_punktow;
    private $nazwy_umiejetnosci;
    private $analizaData;
    private $headerData;
    private $students = [];


    public function parse()
    {
        if(!$this->checkIsValidEntryData()) {
            echo "Brakuje danych do parsowania.";
            return;
        }
        $this->prepareHeaderData();
        $this->prepareAnalizaData();
        $this->prepareObszarData();
        if(!$this->checkIsValidParsedData()) {
            echo "Błędnie sparsowane dane.";
            return;
        }
    }

    private function checkIsValidEntryData()
    {
        return is_array($this->csvDataToParse) && !empty($this->analiza_id);
    }

    private function checkIsValidParsedData()
    {
        $isCount = count($this->nazwy_umiejetnosci) == count($this->nazwy_zadan)
            && count($this->nazwy_obszarow) == count($this->nazwy_punktow)
            && count($this->nazwy_punktow) == count($this->nazwy_zadan);

        $ilosc_uczniow = count($this->students);
        $ilosc_zadan = count($this->nazwy_zadan);
        $ilosc_wierszy = count($this->analizaData);
        $isMultiply = $ilosc_wierszy == $ilosc_uczniow * $ilosc_zadan;
        return $isCount && $isMultiply;
    }

    /**
     * Ustawia dane odnośnie zadań, obszarów, umiejętnośći, punktów z nagłówka csv.
     *
     * @param array $item
     */
    private function prepareHeaderData()
    {
        $this->headerData = $this->getHeaderData();
        foreach ($this->headerData as $rowData) {
            switch (strtolower($rowData[0])) {
                case 'zadanie' :
                    $this->nazwy_zadan = $this->cutArray($rowData);
                    break;
                case 'obszar' :
                    $this->nazwy_obszarow = $this->cutArray($rowData);
                    break;
                case 'umiejętności' :
                    $this->nazwy_umiejetnosci = $this->cutArray($rowData);
                    break;
                case 'punkty' :
                    $this->nazwy_punktow = $this->cutArray($rowData);
                    break;
            }
        }
    }

    /**
     * Pobiera tylko dane odnośnie punktów z wiersza danych csv.
     * Pomija kod ucznia (pierwszy element) i 3 ostatnie dyslekcję, płeć, lokalizację.
     *
     * @param array $arr
     * @return array
     */
    private function cutArray($rowData)
    {
        array_shift($rowData); // pierwszy element wiersza, to string, nie zawiera się on w danych analizy
        array_splice($rowData, -3, 3); // trzy ostatnie elementy wiersza danych odpowiadają za lokalizacje, dysleksje, płeć
        return $rowData;
    }

    /**
     * Przygotowuje dane obszarów i umiejętności w postaci wierszy
     */
    private function prepareObszarData()
    {
        if (!$this->nazwy_umiejetnosci) {
            $this->prepareHeaderData();
        }
        foreach ($this->nazwy_umiejetnosci as $pozycja => $nazwaUmiejetnosci) {
            $umiejetnosci = explode('/', trim($nazwaUmiejetnosci));
            foreach ($umiejetnosci as $umiejetnosc) {
                $this->dane_obszar[] = [
                    'id_analiza' => $this->analiza_id,
                    'obszar' => trim($this->nazwy_obszarow[$pozycja]),
                    'umiejetnosc' => $umiejetnosc,
                    'nr_zadania' => trim($this->nazwy_zadan[$pozycja])
                ];
            };
        }
    }

    /**
     * Ustawia dane odnośnie wyników egzaminu, uczniów w postaci wierszy danych, jeden wiersz, to jeden wpis w bazie.
     *
     * @param array $item
     */
    private function prepareAnalizaData()
    {
        $this->analizaData = $this->getAnalizaData();
        foreach ($this->analizaData as $analizaRow) {
            if (empty($analizaRow[0])) {
                break;
            }
            $studentData = $this->getStudentData($analizaRow);
            $this->dane_uczniowie[] = $studentData;
            $this->getExamData($analizaRow, $studentData);
        }
    }

    /**
     * Pobiera dane o wynikach egzaminów w postaci wierszy w tabeli wyniki_egzaminow
     *
     * @param array $analizaRow wiersz danych pojedynczego ucznia
     * @param array $studentInfo informacje o uczniu dla którego pobierane są wyniki
     */
    private function getExamData($analizaRow, $studentInfo)
    {
        // pobieramy tylko dane odnośnie punktów za zadania
        $examData = array_slice($analizaRow, 1, count($this->nazwy_zadan));
        foreach ($examData as $pozycja => $liczba_punktow) {
            $this->dane_wyniki_egzaminu[] = [
                'id_analiza' => $this->analiza_id,
                'klasa' => $studentInfo['klasa'],
                'kod_ucznia' => $studentInfo['kod_ucznia'],
                'nr_zadania' => trim($this->nazwy_zadan[$pozycja]),
                'liczba_punktow' => $liczba_punktow,
                'max_punktow' => $this->nazwy_punktow[$pozycja]
            ];
        }
    }

    /**
     * Pobiera dane ucznia z wiersza danych csv
     *
     * @param array $analizaRow
     * @return array
     * @throws \Exception
     */
    private function getStudentData($analizaRow)
    {
        $kod_ucznia = $this->getKodUcznia($analizaRow);
        $nr_ucznia = preg_replace("/[^0-9]/", '', $kod_ucznia);
        $klasa = preg_replace("/[0-9]/", '', $kod_ucznia);
        $this->students[$kod_ucznia] = $kod_ucznia;
        $studentInfo = array_slice($analizaRow, -3, 3);
        foreach ($studentInfo as $item) {
            if (in_array($item, self::PLEC_PATTERN)) {
                $plec = $item;
            } elseif (in_array($item, self::LOKALIZACJA_PATTERN)) {
                $lokalizacja = $item;
            } elseif (in_array($item, self::DYSLEKSJA_PATTERN)) {
                $dysleksja = $item;
            } else {
                var_dump($item);
                var_dump($analizaRow);
                throw new \Exception('niepoprawny parametr w danych plec, lokalizacja, dysleksja '.$item);
            }
        }
        return [
            'updated_at' => time(),
            'id_analiza' => $this->analiza_id,
            'kod_ucznia' => $kod_ucznia,
            'lokalizacja' => $lokalizacja,
            'dysleksja' => $dysleksja,
            'plec' => $plec,
            'nr_ucznia' => $nr_ucznia,
            'klasa' => $klasa,
        ];
    }

    /**
     * Pobiera kod ucznia z wiersza danych csv
     *
     * @param array $analizaRow
     * @return string
     */
    private function getKodUcznia($analizaRow)
    {
        $kodTmp = array_slice($analizaRow, 0, 1);
        $kod_ucznia = reset($kodTmp);
        return $kod_ucznia;
    }

    /**
     * Pobiera dane wyników uczniów z danych csv, bez nagłówka
     *
     * @return array
     */
    private function getAnalizaData()
    {
        return array_slice($this->csvDataToParse, 4);
    }

    /**
     * Pobiera dane nagłówka z danych csv, bez wyników egzaminu
     *
     * @return array
     */
    private function getHeaderData()
    {
        return array_slice($this->csvDataToParse, 0, 4);
    }

    public function setAnalizaId($analizaId)
    {
        $this->analiza_id = $analizaId;
        return $this;
    }

    /**
     * Ustawia dane pobrane z pliku csv
     *
     * @param array $csvDataToParse
     * @return $this
     */
    public function setDataToParse($csvDataToParse)
    {
        $this->csvDataToParse = $csvDataToParse;
        return $this;
    }
}