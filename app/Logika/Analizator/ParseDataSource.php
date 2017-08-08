<?php
namespace App\Logika\Analizator;
/**
 * Parsuje dane testu podanego z pliku csv, do postaci tablicy dającej się zapisać w bazie mysql.
 *
 * @package App\Logika\Analizator
 */
class ParseDataSource {

    // TODO: poprawić tak aby uzupełniało płeć, dysleksję inlokalizację niezależnie od wielkości litery np. m
    const LOKALIZACJA_PATTERN = ['m', 'W', 'w', 'mi', 'wi'];
    const PLEC_PATTERN = ['k', 'K', 'M', 'C', 'D', 'c', 'd'];
    const DYSLEKSJA_PATTERN = ['T', 'N', 't', 'n'];

    const ZADANIA_PATTERN = ['zadanie', 'zadania'];
    const OBSZARY_PATTERN = ['obszar', 'obszary'];
    const UMIEJETNOSCI_PATTERN = ['umiejetnosci', 'umiejetnosc', 'umiejętność', 'umiejętności'];
    const PUNKTY_PATTERN = ['punkty', 'punkt'];

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
    private $students = [];


    public function parse()
    {
        if(!$this->checkIsValidEntryData()) {
            throw new \Exception('Brakuje danych do parsowania.');
        }
        // $this->csvDataToParse podmienić k,m na odpowiednie wielkie litery
        $this->prepareHeaderData(); // przyotowuje dane z nagłówka csv, ilość i nazwy zadań obszarów, umiejętności
        $this->prepareAnalizaData(); // przygotowuje dane do tabeli wyników i uczniów
        $this->prepareObszarData(); // przygotowuje dane do tabeli obszarów
        if(!$this->checkIsValidParsedData()) {
            throw new \Exception('Błędnie sparsowane dane.');
        }
    }

    /**
     * Ustawia dane odnośnie zadań, obszarów, umiejętnośći, punktów z nagłówka csv.
     */
    private function prepareHeaderData()
    {
        $headerData = $this->getHeaderData();
        foreach ($headerData as $rowData) {
            $item = strtolower($rowData[0]);
            if (in_array($item, self::ZADANIA_PATTERN)) {
                $this->nazwy_zadan = $this->cutArray($rowData);
            } elseif (in_array($item, self::OBSZARY_PATTERN)) {
                $this->nazwy_obszarow = $this->cutArray($rowData);
            } elseif (in_array($item, self::UMIEJETNOSCI_PATTERN)) {
                $this->nazwy_umiejetnosci = $this->cutArray($rowData);
            } elseif (in_array($item, self::PUNKTY_PATTERN)) {
                $this->nazwy_punktow = $this->cutArray($rowData);
            } else {
                var_dump($item);
                var_dump($rowData);
                throw new \Exception('niepoprawny parametr w zadanie, obszar umiejętność, punkty '.$item);
            }
        }
    }

    /**
     * Przygotowuje dane obszarów i umiejętności w postaci wierszy
     */
    private function prepareObszarData()
    {
        if (!$this->nazwy_umiejetnosci) {
            dd('Nie ma nazw umiejętności.');
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
        if (!$this->analizaData) {
            $this->prepareHeaderData();
        }
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
        $examData = $this->getExamDataFromRow($analizaRow);
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
        $nr_ucznia = preg_replace("/[^0-9]/", '', $kod_ucznia); // numer musi być liczbą
        $klasa = preg_replace("/[0-9]/", '', $kod_ucznia); // kod ucznia musi być stringiem
        $this->students[$kod_ucznia] = $kod_ucznia; // liczymy ilu jest uczniów do późniejszego walidowania
        $studentInfo = $this->getStudentInfoFromRow($analizaRow);

        $lokalizacja = $plec = $dysleksja = '';
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
                throw new \Exception('niepoprawny parametr w danych plec, lokalizacja, dysleksja dla ucznia '.$kod_ucznia);
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
     * Ostatnie 3 elementy wiersza danych csv zawierają informację o dyslekcji, lokalizacji i płci
     *
     * @param array $analizaRow
     * @return mixed
     */
    private function getStudentInfoFromRow($analizaRow)
    {
        return array_slice($analizaRow, -3, 3);
    }

    /**
     * Pobiera kod ucznia z wiersza danych csv
     *
     * @param array $analizaRow
     * @return string
     */
    private function getKodUcznia($analizaRow)
    {
        // pierwszy element wiersza danych wyników z csv zawiera kod ucznia
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
        // 4 pierwsze elementy, to dane dla
        // zadań, obszarów, umiejętności, maksymalnej liczby punktów,
        // pozostałe to dane
        return array_slice($this->csvDataToParse, 4);
    }

    /**
     * Pobiera tylko dane wyników egzaminu.
     * Pomija pierwszy element i bierze tyle elementów ile było zadań.
     *
     * @param array $analizaRow
     * @return mixed
     */
    private function getExamDataFromRow($analizaRow)
    {
        return array_slice($analizaRow, 1, count($this->nazwy_zadan));
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

    private function checkIsValidEntryData()
    {
        return is_array($this->csvDataToParse) && !empty($this->analiza_id);
    }

    private function checkIsValidParsedData()
    {
        $isCount = count($this->nazwy_obszarow) == count($this->nazwy_punktow)
            && count($this->nazwy_punktow) == count($this->nazwy_zadan);
        if (!$isCount) {
            throw new \Exception('nie zgadzają się limity obszarów, punktów i zadan.');
        }
        $ilosc_uczniow = count($this->students);
        $ilosc_zadan = count($this->nazwy_zadan);
        $ilosc_wierszy = count($this->dane_wyniki_egzaminu);
        $isMultiply = $ilosc_wierszy == $ilosc_uczniow * $ilosc_zadan;
        if (!$isMultiply) {
            throw new \Exception('nie zgadza się liczba uczniów, zadań i ilości wynków.');
        }
        return $isCount && $isMultiply;
    }


    /**
     * Pobiera dane nagłówka z danych csv, bez wyników egzaminu,
     * pozostawiając same wyniki
     *
     * @return array
     */
    private function getHeaderData()
    {
        // 4 pierwsze elementy, to dane dla zadań, obszarów, umiejętności, maksymalnej liczby punktów
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