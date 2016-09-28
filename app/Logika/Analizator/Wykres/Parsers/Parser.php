<?php
namespace App\Logika\Analizator\Wykres\Parsers;

use Illuminate\Support\Facades\DB;

abstract class Parser implements IParseToChartData{

    const TYP_SREDNIA = 'srednia';
    const TYP_OBSZAR = 'obszar';
    const TYP_ZADANIE = 'zadanie';
    const TYP_CZESTOSC = 'czestosc';

    const COLUMN_NAME_DYSLEKSJA = 'dysleksja';
    const COLUMN_NAME_KLASA = 'klasa';
    const COLUMN_NAME_PLEC = 'plec';
    const COLUMN_NAME_LOKALIZACJA = 'lokalizacja';
    const COLUMN_NAME_OBSZAR = 'obszar';
    const COLUMN_NAME_UMIEJETNOSC = 'umiejetnosc';
    const COLUMN_NAME_NR_ZADANIA = 'nr_zadania';
    const COLUMN_NAME_SREDNIA_PKT = 'srednia';
    const COLUMN_NAME_ILOSC_WYNIKOW = 'ilosc_wynikow';
    const COLUMN_NAME_SUMA = 'suma';

    protected $name_czestosc = 'Częstość wyników';
    protected $name_srednia = 'Średnia';
    protected $name_obszar = 'Obszar';
    protected $wynik;

    protected $mapowanie_podzialu_wykresu = [
        'T' => 'dysleksja',
        'N' => 'bez dysleksji',
        'm' => 'miasto',
        'w' => 'wieś',
        'K' => 'kobieta',
        'M' => 'mężczyzna',
        'wszystko' => 'całość'
    ];

    protected $mapowanie_nazw_podzialu = [
        'T' => 'wg dyslektyków',
        'N' => 'wg dyslektyków',
        'm' => 'wg lokalizacji',
        'w' => 'wg lokalizacji',
        'K' => 'wg płci',
        'M' => 'wg płci',
        'wszystko' => ''
    ];
    protected $chatsTypeToRender;

    abstract function parseDataToChart($id);
    abstract function getResult();

    protected function dbSelect($sql, $options) {
        return DB::select($sql, $options);
    }
    public function addToRender($chartToRender)
    {
        $this->chatsTypeToRender[] = $chartToRender;
    }
    protected function addNewChart($data)
    {
        $this->wynik[] = $data;
    }
    protected function prepareSeries($dane)
    {
//        $series = [];
//        $tags = [];
//        foreach ($dane as $podzial1 => $dane3) {
//            $nazwa_podzialu = $this->getSeriesName($podzial1);
//            $tags[] = $nazwa_podzialu;
//            $series[] = [
//                'name' => $nazwa_podzialu,
//                'data' => $dane3,
//                'dataLabels' => [
//                    'enabled' => true,
//                    'format' => '{point.y:.2f}'
//                ]
//            ];
//        }
//        $nazwa_wykresu = $this->mapuj_nazwe_do_wykresu($podzial1);
//        $tags[] = $nazwa_wykresu;
//        return [
//            'name' => $nazwa_wykresu,
//            'series' => $series,
//            'tags' => $tags
//        ];
    }

    protected function mapuj_dla_sredniej_klasa($dane_db, $column_srednia, $category_column_name, $series_name_kategorii = '', $series_name_wykresu = '', $nazwa = 'domyślne')
    {
        $seriesTable = [];
        $tags = [];
        foreach ($dane_db as $rowValue) {
            // przypisanie danych z bazy
            $rowValue = (array)$rowValue; // DB zwraca object, a potrzebny array
            $srednia = $rowValue[$column_srednia];
            $kategoria = $rowValue[$category_column_name]; // wartość z kolumny kategorii, np . 'A', 'szkola'

            $series_name = $this->getSeriesName($rowValue, $series_name_kategorii);

            $tags[$series_name] = $series_name;
            $tags[$kategoria] = $kategoria;

            // wartości serii wykresu
            $seriesTable[$series_name]['data'][$kategoria] = $srednia;
            $seriesTable[$series_name]['name'] = $series_name;
            $seriesTable[$series_name]['dataLabels'] = [
                'enabled' => true,
                'format' => '{point.y:.2f}'
            ];

        }
        $kategorie = array_keys($seriesTable[$series_name]['data']);
        $series = array_values($seriesTable);
        $chart = [];
        $chart['categories'] = $kategorie;
        $chart['series'] = $series;
        $chart['tags'] = $tags;
        $chart['name'] = 'Średnia klasy '.$this->getChartNameFromColumnName($series_name_kategorii);
        $this->addNewChart($chart);
    }
    protected function mapuj($dane_db, $column_srednia, $category_column_name, $series_name_kategorii = '', $series_name_wykresu = '', $nazwa = 'domyślne')
    {
        $seriesTable = [];
        $tags = [];
        foreach ($dane_db as $rowValue) {
            // przypisanie danych z bazy
            $rowValue = (array)$rowValue; // DB zwraca object, a potrzebny array
            $srednia = $rowValue[$column_srednia];
            $kategoria = $rowValue[$category_column_name]; // wartość z kolumny kategorii, np . 'A', 'szkola'

            $series_name = $this->getSeriesName($rowValue, $series_name_kategorii);

            $tags[$series_name] = $series_name;
            $tags[$kategoria] = $kategoria;

            // wartości serii wykresu
            $seriesTable[$series_name]['data'][$kategoria] = $srednia;
            $seriesTable[$series_name]['name'] = $series_name;
            $seriesTable[$series_name]['dataLabels'] = [
                'enabled' => true,
                'format' => '{point.y:.2f}'
            ];

        }
        $kategorie = array_keys($seriesTable[$series_name]['data']);
        $series = array_values($seriesTable);
        $chart = [];
        $chart['categories'] = $kategorie;
        $chart['series'] = $series;
        $chart['tags'] = $tags;
        $chart['name'] = 'Średnia klasy '.$this->getChartNameFromColumnName($series_name_kategorii);
        $this->addNewChart($chart);
    }

    protected function getChartNameFromColumnName($column_name)
    {
        if (!$column_name) return 'całość';
        if ($column_name == self::COLUMN_NAME_DYSLEKSJA) return 'wg dysleksji';
        if ($column_name == self::COLUMN_NAME_LOKALIZACJA) return 'wg lokalizacji';
        if ($column_name == self::COLUMN_NAME_PLEC) return 'wg płci';
        if ($column_name == self::COLUMN_NAME_DYSLEKSJA) return 'dysleksja';
    }
    protected function mapuj_czestosc($dane_db, $column_srednia, $category_column_name, $podzial_kategorii = '', $podzial_wykresu = '', $name)
    {
        $dataset = [];
        $kategorie = [];
        foreach ($dane_db as $row) {

            // przypisanie danych z bazy
            $row = (array)$row; // DB zwraca object, a potrzebny array
            $srednia = $row[$column_srednia];
            $kategoria = $row[$category_column_name];
            $podzial = $podzial_kategorii ? $row[$podzial_kategorii] : 'wszystko';
            $wykres_podzial = $podzial_wykresu ? $row[$podzial_wykresu] : '';

            $kategorie[$wykres_podzial][$kategoria] = $kategoria;

            $dataset[$wykres_podzial][$podzial][] = $srednia;
        }
        var_dump($dataset);
        var_dump($kategorie);
        // tworzenie serii
//        foreach ($dataset as $wykresy_podzial => $dane) {
//            $kategoria = $kategorie[$wykresy_podzial];
//            $wynik = $this->prepareSeries($dane);
//
//            $wynik['name'] = $this->generateChartName($name, $wykresy_podzial, $wynik['name']);
//            $wynik['categories'] = array_keys($kategoria);
//            $this->addNewChart($wynik);
//        }
    }

    protected function mapuj_umiejetnosc($dane_db, $column_srednia, $category_column_name,
                                       $podzial_kategorii = '', $podzial_wykresu = '', $podzial_wykresu2 = '', $nazwa = 'domyślne')
    {
        $dataset = [];
        $kategorie = [];
        foreach ($dane_db as $row) {

            // przypisanie danych z bazy
            $row = (array)$row; // DB zwraca object, a potrzebny array
            $srednia = $row[$column_srednia];
            $kategoria = $row[$category_column_name];
            $podzial = $podzial_kategorii ? $row[$podzial_kategorii] : 'wszystko';
            $wykres_podzial = $podzial_wykresu ? $row[$podzial_wykresu] : '';
            $wykres_podzial2 = $podzial_wykresu2 ? $row[$podzial_wykresu2] : '';

            $kategorie[$wykres_podzial][$kategoria] = $kategoria;

            $dataset[$wykres_podzial][$wykres_podzial2][$podzial][] = $srednia;
        }
        var_dump($dataset);
        var_dump($kategorie);
        // tworzenie serii
//        foreach ($dataset as $wykresy_podzial => $dane) {
//            $kategoria = $kategorie[$wykresy_podzial];
//            foreach ($dane as $wykresy_podzial2 => $dane2) {
//                $series = [];
//                $tagi = [];
//                foreach ($dane2 as $podzial1 => $dane3) {
//                    $nazwa_podzialu = $this->getSeriesName($podzial1);
//                    $tagi[] = $nazwa_podzialu;
//                    $series[] = [
//                        'name' => $nazwa_podzialu,
//                        'data' => $dane3,
//                        'dataLabels' => [
//                            'enabled' => true,
//                            'format' => '{point.y:.2f}'
//                        ]
//                    ];
//                }
//                $this->wynik[] = [
//                    'name' => 'Umiejętność wyników klasa ' . $wykresy_podzial2 . ' ' . $podzial1,
//                    'categories' => array_keys($kategoria),
//                    'series' => $series,
//                    'tags' => $tagi
//                ];
//            }
//        }
    }

    protected function generateChartName($name, $wykresy_podzial, $podzial_kategorii)
    {
        return $name. ' '.$this->mapName($wykresy_podzial). ' '. $podzial_kategorii;
    }

    protected function mapName($wykresy_podzial)
    {
        if (!$wykresy_podzial) return '';
        if ($wykresy_podzial == 'szkola') return 'szkoła';
        else return 'klasa '.$wykresy_podzial;
    }

    protected function mapuj_nazwe_do_wykresu($podzial) {
        $nazwa_podzial = '';
        if (in_array($podzial, array_keys($this->mapowanie_nazw_podzialu))) {
            $nazwa_podzial = $this->mapowanie_nazw_podzialu[$podzial];
        }
        return $nazwa_podzial;
    }

    protected function getSeriesName($rowValue, $series_name_kategorii)
    {
        $nazwa_podzial = 'wszystko';
        $podzial = isset($rowValue[$series_name_kategorii])? $rowValue[$series_name_kategorii] : $nazwa_podzial;
        if (in_array($podzial, array_keys($this->mapowanie_podzialu_wykresu))) {
            $nazwa_podzial = $this->mapowanie_podzialu_wykresu[$podzial];
        }
        return $nazwa_podzial;
    }
}