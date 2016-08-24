<?php
namespace App\Logika\Analizator;

use Illuminate\Support\Facades\DB;

class AnalizaHelpers
{

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
    const COLUMN_NAME_SREDNIA_PKT = 'srednia_punktow';
    const COLUMN_NAME_ILOSC_WYNIKOW = 'ilosc_wynikow';
    const COLUMN_NAME_SUMA = 'suma';

    private $wynik = [];

    private $name_czestosc = 'Częstość wyników';
    private $name_srednia = 'Średnia';
    private $name_obszar = 'Obszar';

    private $mapowanie_podzialu_wykresu = [
        'T' => 'dysleksja',
        'N' => 'bez dysleksji',
        'm' => 'miasto',
        'w' => 'wieś',
        'K' => 'kobieta',
        'M' => 'mężczyzna',
        'wszystko' => 'całość'
    ];

    private $mapowanie_nazw_podzialu = [
        'T' => 'wg dyslektyków',
        'N' => 'wg dyslektyków',
        'm' => 'wg lokalizacji',
        'w' => 'wg lokalizacji',
        'K' => 'wg płci',
        'M' => 'wg płci',
        'wszystko' => ''
    ];

    private function mapuj_srednia($id_analiza)
    {
        $this->mapujSredniaCalosc($id_analiza);
        $this->mapujSredniaDysleksja($id_analiza);
        $this->mapujSredniaLokalizacja($id_analiza);
        $this->mapujSredniaPlec($id_analiza);

        $this->mapujSredniaGrupyCalosc($id_analiza);
        $this->mapujSredniaGrupyDysleksja($id_analiza);
        $this->mapujSredniaGrupyLokalizacja($id_analiza);
        $this->mapujSredniaGrupyPlec($id_analiza);
    }

    private function mapuj_srednia_obszar($id_analiza)
    {
        $this->mapujSredniaObszarCalosc($id_analiza);
        $this->mapujSredniaObszarDysleksja($id_analiza);
        $this->mapujSredniaObszarLokalizacja($id_analiza);
        $this->mapujSredniaObszarPlec($id_analiza);

        $this->mapujSredniaUmiejetnoscCalosc($id_analiza);
        $this->mapujSredniaUmiejetnoscDysleksja($id_analiza);
        $this->mapujSredniaUmiejetnoscLokalizacja($id_analiza);
        $this->mapujSredniaUmiejetnoscPlec($id_analiza);
    }

    private function mapuj_srednia_zadania($id_analiza)
    {
        $this->mapujSredniaZadaniaCalosc($id_analiza);
        $this->mapujSredniaZadaniaDysleksja($id_analiza);
        $this->mapujSredniaZadaniaLokalizacja($id_analiza);
        $this->mapujSredniaZadaniaPlec($id_analiza);
    }

    private function mapuj_czestosc_wynikow($id_analiza)
    {
//        $this->mapujCzestoscWynikowCalosc($id_analiza);
//        $this->mapujCzestoscWynikowPlec($id_analiza);
//        $this->mapujCzestoscWynikowLokalizacja($id_analiza);
//        $this->mapujCzestoscWynikowDysleksja($id_analiza);
    }


    private function mapuj_umiejetnosc($dane_db, $column_srednia, $column_x,
                                       $podzial_kategorii = '', $podzial_wykresu = '', $podzial_wykresu2 = '', $nazwa = 'domyślne')
    {
        $dataset = [];
        $kategorie = [];
        foreach ($dane_db as $row) {

            // przypisanie danych z bazy
            $row = (array)$row; // DB zwraca object, a potrzebny array
            $srednia = $row[$column_srednia];
            $kategoria = $row[$column_x];
            $podzial = $podzial_kategorii ? $row[$podzial_kategorii] : 'wszystko';
            $wykres_podzial = $podzial_wykresu ? $row[$podzial_wykresu] : '';
            $wykres_podzial2 = $podzial_wykresu2 ? $row[$podzial_wykresu2] : '';

            $kategorie[$wykres_podzial][$kategoria] = $kategoria;

            $dataset[$wykres_podzial][$wykres_podzial2][$podzial][] = $srednia;
        }
        // tworzenie serii
        foreach ($dataset as $wykresy_podzial => $dane) {
            $kategoria = $kategorie[$wykresy_podzial];
            foreach ($dane as $wykresy_podzial2 => $dane2) {
                $series = [];
                $tagi = [];
                foreach ($dane2 as $podzial1 => $dane3) {
                    $nazwa_podzialu = $this->mapuj_nazwe_podzialu($podzial1);
                    $tagi[] = $nazwa_podzialu;
                    $series[] = [
                        'name' => $nazwa_podzialu,
                        'data' => $dane3,
                        'dataLabels' => [
                            'enabled' => true,
                            'format' => '{point.y:.2f}'
                        ]
                    ];
                }
                $this->wynik[] = [
                    'name' => 'Umiejętność wyników klasa ' . $wykresy_podzial2 . ' ' . $podzial1,
                    'categories' => array_keys($kategoria),
                    'series' => $series,
                    'tags' => $tagi
                ];
            }
        }
    }

    private function mapuj_czestosc($dane_db, $column_srednia, $column_x, $podzial_kategorii = '', $podzial_wykresu = '', $name)
    {
        $dataset = [];
        $kategorie = [];
        foreach ($dane_db as $row) {

            // przypisanie danych z bazy
            $row = (array)$row; // DB zwraca object, a potrzebny array
            $srednia = $row[$column_srednia];
            $kategoria = $row[$column_x];
            $podzial = $podzial_kategorii ? $row[$podzial_kategorii] : 'wszystko';
            $wykres_podzial = $podzial_wykresu ? $row[$podzial_wykresu] : '';

            $kategorie[$wykres_podzial][$kategoria] = $kategoria;

            $dataset[$wykres_podzial][$podzial][] = $srednia;
        }
        // tworzenie serii
        foreach ($dataset as $wykresy_podzial => $dane) {
            $kategoria = $kategorie[$wykresy_podzial];
            $wynik = $this->prepareSeries($dane);

            $wynik['name'] = $this->generateChartName($name, $wykresy_podzial, $wynik['name']);
            $wynik['categories'] = array_keys($kategoria);
            $this->addNewChart($wynik);
        }
    }




    private function mapujSredniaCalosc($id_analiza)
    {
        $dane_db = DB::select(ZapytaniaSql::SREDNIA_CALOSC_KLASA, [$id_analiza, $id_analiza]);
        $this->mapuj($dane_db, self::COLUMN_NAME_SREDNIA_PKT, self::COLUMN_NAME_KLASA, null, null, 'Średnia');
    }

    private function mapujSredniaDysleksja($id_analiza)
    {
        $dane_db = DB::select(ZapytaniaSql::SREDNIA_CALOSC_KLASA_DYSLEKSJA, [$id_analiza, $id_analiza]);
        $this->mapuj($dane_db, self::COLUMN_NAME_SREDNIA_PKT, self::COLUMN_NAME_KLASA, self::COLUMN_NAME_DYSLEKSJA, null, 'Średnia');
    }

    private function mapujSredniaLokalizacja($id_analiza)
    {
        $dane_db = DB::select(ZapytaniaSql::SREDNIA_CALOSC_KLASA_LOKALIZACJA, [$id_analiza, $id_analiza]);
        $this->mapuj($dane_db, self::COLUMN_NAME_SREDNIA_PKT, self::COLUMN_NAME_KLASA, self::COLUMN_NAME_LOKALIZACJA, null, 'Średnia');
    }

    private function mapujSredniaPlec($id_analiza)
    {
        $dane_db = DB::select(ZapytaniaSql::SREDNIA_CALOSC_KLASA_PLEC, [$id_analiza, $id_analiza]);
        $this->mapuj($dane_db, self::COLUMN_NAME_SREDNIA_PKT, self::COLUMN_NAME_KLASA, self::COLUMN_NAME_PLEC, null, 'Średnia');
    }

    private function mapujSredniaGrupyCalosc($id_analiza)
    {
        $dane_db = DB::select(ZapytaniaSql::SREDNIA_PUNKTOW_GRUPY_CALOSC, [$id_analiza, $id_analiza]);
        $this->mapuj($dane_db, self::COLUMN_NAME_SREDNIA_PKT, self::COLUMN_NAME_KLASA, null, null, 'Średnia w pkt. ');
    }

    private function mapujSredniaGrupyDysleksja($id_analiza)
    {
        $dane_db = DB::select(ZapytaniaSql::SREDNIA_PUNKTOW_GRUPY_DYSLEKSJA, [$id_analiza, $id_analiza]);
        $this->mapuj($dane_db, self::COLUMN_NAME_SREDNIA_PKT, self::COLUMN_NAME_KLASA, self::COLUMN_NAME_DYSLEKSJA, null, 'Średnia w pkt. ');
    }

    private function mapujSredniaGrupyLokalizacja($id_analiza)
    {
        $dane_db = DB::select(ZapytaniaSql::SREDNIA_PUNKTOW_GRUPY_LOKALIZACJA, [$id_analiza, $id_analiza]);
        $this->mapuj($dane_db, self::COLUMN_NAME_SREDNIA_PKT, self::COLUMN_NAME_KLASA, self::COLUMN_NAME_LOKALIZACJA, null, 'Średnia w pkt. ');
    }

    private function mapujSredniaGrupyPlec($id_analiza)
    {
        $dane_db = DB::select(ZapytaniaSql::SREDNIA_PUNKTOW_GRUPY_PLEC, [$id_analiza, $id_analiza]);
        $this->mapuj($dane_db, self::COLUMN_NAME_SREDNIA_PKT, self::COLUMN_NAME_KLASA, self::COLUMN_NAME_PLEC, null, 'Średnia w pkt. ');
    }








    private function mapujSredniaObszarCalosc($id_analiza)
    {
        $dane_db = DB::select(ZapytaniaSql::SREDNIA_OBSZAR_CALOSC, [$id_analiza, $id_analiza]);
        $chartData = $this->mapuj($dane_db, self::COLUMN_NAME_SREDNIA_PKT, self::COLUMN_NAME_KLASA, self::COLUMN_NAME_OBSZAR, null, null, 'obszar całość');
        return $chartData;
    }

    private function mapujSredniaObszarDysleksja($id_analiza)
    {
        $dane_db = DB::select(ZapytaniaSql::SREDNIA_OBSZAR_DYSLEKSJA, [$id_analiza, $id_analiza]);
        $chartData = $this->mapuj($dane_db, self::COLUMN_NAME_SREDNIA_PKT, self::COLUMN_NAME_OBSZAR, self::COLUMN_NAME_DYSLEKSJA, self::COLUMN_NAME_KLASA, 'obszar dysleksja');
        return $chartData;
    }

    private function mapujSredniaObszarLokalizacja($id_analiza)
    {
        $dane_db = DB::select(ZapytaniaSql::SREDNIA_OBSZAR_LOKALIZACJA, [$id_analiza, $id_analiza]);
        $chartData = $this->mapuj($dane_db, self::COLUMN_NAME_SREDNIA_PKT, self::COLUMN_NAME_OBSZAR, self::COLUMN_NAME_LOKALIZACJA, self::COLUMN_NAME_KLASA, 'obszar lokalizacja');
        return $chartData;
    }

    private function mapujSredniaObszarPlec($id_analiza)
    {
        $dane_db = DB::select(ZapytaniaSql::SREDNIA_OBSZAR_PLEC, [$id_analiza, $id_analiza]);
        $chartData = $this->mapuj($dane_db, self::COLUMN_NAME_SREDNIA_PKT, self::COLUMN_NAME_OBSZAR, self::COLUMN_NAME_PLEC, self::COLUMN_NAME_KLASA, 'obszar plec');
        return $chartData;
    }

    private function mapujSredniaUmiejetnoscCalosc($id_analiza)
    {
        $dane_db = DB::select(ZapytaniaSql::SREDNIA_OBSZAR_UMIEJETNOSC_CALOSC, [$id_analiza, $id_analiza, $id_analiza, $id_analiza]);
        $chartData = $this->mapuj_umiejetnosc($dane_db, self::COLUMN_NAME_SREDNIA_PKT, self::COLUMN_NAME_UMIEJETNOSC, null, self::COLUMN_NAME_OBSZAR, self::COLUMN_NAME_KLASA, 'Umiejętności');
        return $chartData;
    }

    private function mapujSredniaUmiejetnoscDysleksja($id_analiza)
    {
        $dane_db = DB::select(ZapytaniaSql::SREDNIA_OBSZAR_UMIEJETNOSC_DYSLEKSJA, [$id_analiza, $id_analiza, $id_analiza, $id_analiza]);
        $chartData = $this->mapuj_umiejetnosc($dane_db, self::COLUMN_NAME_SREDNIA_PKT, self::COLUMN_NAME_UMIEJETNOSC, self::COLUMN_NAME_DYSLEKSJA, self::COLUMN_NAME_OBSZAR, self::COLUMN_NAME_KLASA, 'Umiejętności');
        return $chartData;
    }

    private function mapujSredniaUmiejetnoscLokalizacja($id_analiza)
    {
        $dane_db = DB::select(ZapytaniaSql::SREDNIA_OBSZAR_UMIEJETNOSC_LOKALIZACJA, [$id_analiza, $id_analiza, $id_analiza, $id_analiza]);
        $chartData = $this->mapuj_umiejetnosc($dane_db, self::COLUMN_NAME_SREDNIA_PKT, self::COLUMN_NAME_UMIEJETNOSC, self::COLUMN_NAME_LOKALIZACJA, self::COLUMN_NAME_OBSZAR, self::COLUMN_NAME_KLASA, 'Umiejętności');
        return $chartData;
    }

    private function mapujSredniaUmiejetnoscPlec($id_analiza)
    {
        $dane_db = DB::select(ZapytaniaSql::SREDNIA_OBSZAR_UMIEJETNOSC_PLEC, [$id_analiza, $id_analiza, $id_analiza, $id_analiza]);
        $chartData = $this->mapuj_umiejetnosc($dane_db, self::COLUMN_NAME_SREDNIA_PKT, self::COLUMN_NAME_UMIEJETNOSC, self::COLUMN_NAME_PLEC, self::COLUMN_NAME_OBSZAR, self::COLUMN_NAME_KLASA, 'Umiejętności');
        return $chartData;
    }






    private function mapujSredniaZadaniaCalosc($id_analiza)
    {
        $dane_db = DB::select(ZapytaniaSql::SREDNIA_ZADANIA_CALOSC, [$id_analiza, $id_analiza]);
        $chartData = $this->mapuj($dane_db, self::COLUMN_NAME_SREDNIA_PKT, self::COLUMN_NAME_NR_ZADANIA, null, self::COLUMN_NAME_KLASA, 'Zadania');

        return $chartData;
    }

    private function mapujSredniaZadaniaDysleksja($id_analiza)
    {
        $dane_db = DB::select(ZapytaniaSql::SREDNIA_ZADANIA_DYSLEKSJA, [$id_analiza, $id_analiza]);
        $chartData = $this->mapuj($dane_db, self::COLUMN_NAME_SREDNIA_PKT, self::COLUMN_NAME_NR_ZADANIA, self::COLUMN_NAME_DYSLEKSJA, self::COLUMN_NAME_KLASA, 'Zadania');

        return $chartData;
    }

    private function mapujSredniaZadaniaLokalizacja($id_analiza)
    {
        $dane_db = DB::select(ZapytaniaSql::SREDNIA_ZADANIA_LOKALIZACJA, [$id_analiza, $id_analiza]);
        $chartData = $this->mapuj($dane_db, self::COLUMN_NAME_SREDNIA_PKT, self::COLUMN_NAME_NR_ZADANIA, self::COLUMN_NAME_LOKALIZACJA, self::COLUMN_NAME_KLASA, 'Zadania');

        return $chartData;
    }

    private function mapujSredniaZadaniaPlec($id_analiza)
    {
        $dane_db = DB::select(ZapytaniaSql::SREDNIA_ZADANIA_PLEC, [$id_analiza, $id_analiza]);
        $chartData = $this->mapuj($dane_db, self::COLUMN_NAME_SREDNIA_PKT, self::COLUMN_NAME_NR_ZADANIA, self::COLUMN_NAME_PLEC, self::COLUMN_NAME_KLASA, 'Zadania');

        return $chartData;
    }





    private function mapujCzestoscWynikowCalosc($id_analiza)
    {
        $dane_db = DB::select(ZapytaniaSql::CZESTOSC_WYNIKOW_CALOSC, [$id_analiza, $id_analiza]);
        $chartData = $this->mapuj_czestosc($dane_db, self::COLUMN_NAME_ILOSC_WYNIKOW, self::COLUMN_NAME_SUMA, null, self::COLUMN_NAME_KLASA, $this->name_czestosc);

        return $chartData;
    }

    private function mapujCzestoscWynikowPlec($id_analiza)
    {
        $dane_db = DB::select(ZapytaniaSql::CZESTOSC_WYNIKOW_PLEC, [$id_analiza, $id_analiza]);
//        var_dump($dane_db);
        $chartData = $this->mapuj_czestosc($dane_db, self::COLUMN_NAME_ILOSC_WYNIKOW, self::COLUMN_NAME_SUMA, self::COLUMN_NAME_PLEC, self::COLUMN_NAME_KLASA, $this->name_czestosc);

        return $chartData;
    }

    private function mapujCzestoscWynikowLokalizacja($id_analiza)
    {
        $dane_db = DB::select(ZapytaniaSql::CZESTOSC_WYNIKOW_LOKALIZACJA, [$id_analiza, $id_analiza]);
        $chartData = $this->mapuj_czestosc($dane_db, self::COLUMN_NAME_ILOSC_WYNIKOW, self::COLUMN_NAME_SUMA, self::COLUMN_NAME_LOKALIZACJA, self::COLUMN_NAME_KLASA, $this->name_czestosc);

        return $chartData;
    }

    private function mapujCzestoscWynikowDysleksja($id_analiza)
    {
        $dane_db = DB::select(ZapytaniaSql::CZESTOSC_WYNIKOW_DYSLEKSJA, [$id_analiza, $id_analiza]);
        $chartData = $this->mapuj_czestosc($dane_db, self::COLUMN_NAME_ILOSC_WYNIKOW, self::COLUMN_NAME_SUMA, self::COLUMN_NAME_DYSLEKSJA, self::COLUMN_NAME_KLASA, $this->name_czestosc);

        return $chartData;
    }

    private function addNewChart($data)
    {
        $this->wynik[] = [
            'name' => $data['name'],
            'categories' => $data['categories'],
            'series' => $data['series'],
            'tags' => $data['tags']
        ];
    }

    public function getChartsData()
    {
        return $this->wynik;
    }

    public function addChartsData($id_analiza, $typ)
    {
        switch ($typ) {
            case self::TYP_SREDNIA:
                $this->mapuj_srednia($id_analiza);
                break;
            case self::TYP_OBSZAR:
                $this->mapuj_srednia_obszar($id_analiza);
                break;
            case self::TYP_ZADANIE:
                $this->mapuj_srednia_zadania($id_analiza);
                break;
            case self::TYP_CZESTOSC:
                $this->mapuj_czestosc_wynikow($id_analiza);
                break;
            default:
                break;
        }
        return $this;
    }

    private function mapuj($dane_db, $column_srednia, $column_x, $podzial_kategorii = '', $podzial_wykresu = '', $nazwa = 'domyślne')
    {
        $dataset = [];
        $kategorie = [];
        foreach ($dane_db as $row) {

            // przypisanie danych z bazy
            $row = (array)$row; // DB zwraca object, a potrzebny array
            $srednia = $row[$column_srednia];
            $kategoria = $row[$column_x];

            $kategorie[$kategoria] = $kategoria;
            $podzial = $podzial_kategorii ? $row[$podzial_kategorii] : 'wszystko';
            $wykres_podzial = $podzial_wykresu ? $row[$podzial_wykresu] : '';
            $dataset[$wykres_podzial][$podzial][] = $srednia;
        }

        // tworzenie serii
        foreach ($dataset as $wykresy => $dane) {
            $wynik = $this->prepareSeries($dane);

            $chartName = $this->generateChartName($nazwa, '', $wynik['name']);
            $wynik['name'] = $chartName;
            $wynik['tags'][] = $nazwa;
            $wynik['categories'] = array_keys($kategorie);
            $this->addNewChart($wynik);
        }
    }

    private function prepareSeries($dane)
    {
        $series = [];
        $tags = [];
        foreach ($dane as $podzial1 => $dane3) {
            $nazwa_podzialu = $this->mapuj_nazwe_podzialu($podzial1);
            $tags[] = $nazwa_podzialu;
            $series[] = [
                'name' => $nazwa_podzialu,
                'data' => $dane3,
                'dataLabels' => [
                    'enabled' => true,
                    'format' => '{point.y:.2f}'
                ]
            ];
        }
        $nazwa_wykresu = $this->mapuj_nazwe_do_wykresu($podzial1);
        $tags[] = $nazwa_wykresu;
        return [
            'name' => $nazwa_wykresu,
            'series' => $series,
            'tags' => $tags
        ];
    }

    private function generateChartName($name, $wykresy_podzial, $podzial_kategorii)
    {
        return $name. ' '.$this->mapName($wykresy_podzial). ' '. $podzial_kategorii;
    }

    private function mapName($wykresy_podzial)
    {
        if (!$wykresy_podzial) return '';
        if ($wykresy_podzial == 'szkola') return 'szkoła';
        else return 'klasa '.$wykresy_podzial;
    }

    private function mapuj_nazwe_do_wykresu($podzial) {
        $nazwa_podzial = '';
        if (in_array($podzial, array_keys($this->mapowanie_nazw_podzialu))) {
            $nazwa_podzial = $this->mapowanie_nazw_podzialu[$podzial];
        }
        return $nazwa_podzial;
    }

    private function mapuj_nazwe_podzialu($podzial)
    {
        $nazwa_podzial = '';
        if (in_array($podzial, array_keys($this->mapowanie_podzialu_wykresu))) {
            $nazwa_podzial = $this->mapowanie_podzialu_wykresu[$podzial];
        }
        return $nazwa_podzial;
    }
}