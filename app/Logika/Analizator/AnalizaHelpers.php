<?php
namespace App\Logika\Analizator;

use Illuminate\Support\Facades\DB;

class AnalizaHelpers
{

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

    private $srednia_calosc = '';
    private $srednia_dysleksja = '';
    private $srednia_lokalizacja = '';
    private $srednia_plec = '';

    private $wynik = [];

    public function getChartsData($id_analiza, $typ = 'zadanie')
    {
        switch ($typ) {
            case 'srednia':
                $this->mapuj_srednia($id_analiza);
                break;
            case 'obszar':
                $this->mapuj_srednia_obszar($id_analiza);
                break;
            case 'zadanie':
                $this->mapuj_srednia_zadania($id_analiza);
                break;
            case 'umiejetnosc':
                $this->mapuj_srednia_umiejetnosc($id_analiza);
                break;
            case 'czestosc':
                $this->mapuj_czestosc_wynikow($id_analiza);
                break;
            case 'srednia_grupy':
                $this->mapuj_srednia_grupy($id_analiza);
                break;
            default:
                $this->mapuj_srednia($id_analiza);
        }
        return $this->wynik;
    }

    private function mapuj_srednia($id_analiza)
    {
        $this->mapujSredniaCalosc($id_analiza);
        $this->mapujSredniaDysleksja($id_analiza);
        $this->mapujSredniaLokalizacja($id_analiza);
        $this->mapujSredniaPlec($id_analiza);
    }

    private function mapuj_srednia_obszar($id_analiza)
    {
        $this->mapujSredniaObszarCalosc($id_analiza);
        $this->mapujSredniaObszarDysleksja($id_analiza);
        $this->mapujSredniaObszarLokalizacja($id_analiza);
        $this->mapujSredniaObszarPlec($id_analiza);
    }

    private function mapuj_srednia_zadania($id_analiza)
    {
        $this->mapujSredniaZadaniaCalosc($id_analiza);
        $this->mapujSredniaZadaniaDysleksja($id_analiza);
        $this->mapujSredniaZadaniaLokalizacja($id_analiza);
        $this->mapujSredniaZadaniaPlec($id_analiza);
    }

    private function mapuj_srednia_umiejetnosc($id_analiza)
    {
        $this->mapujSredniaUmiejetnoscCalosc($id_analiza);
        $this->mapujSredniaUmiejetnoscDysleksja($id_analiza);
        $this->mapujSredniaUmiejetnoscLokalizacja($id_analiza);
        $this->mapujSredniaUmiejetnoscPlec($id_analiza);
    }

    private function mapuj_czestosc_wynikow($id_analiza)
    {
        $this->mapujCzestoscWynikowCalosc($id_analiza);
        $this->mapujCzestoscWynikowDysleksja($id_analiza);
        $this->mapujCzestoscWynikowLokalizacja($id_analiza);
        $this->mapujCzestoscWynikowPlec($id_analiza);
    }

    private function mapuj_srednia_grupy($id_analiza)
    {
        $this->mapujSredniaGrupyCalosc($id_analiza);
        $this->mapujSredniaGrupyDysleksja($id_analiza);
        $this->mapujSredniaGrupyLokalizacja($id_analiza);
        $this->mapujSredniaGrupyPlec($id_analiza);
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
            $series = [];
            foreach ($dane as $podzial => $dane2) {
                $series[] = [
                    'name' => $podzial,
                    'data' => $dane2,
                    'dataLabels' => [
                        'enabled' => true,
                        'format' => '{point.y:.2f}'
                    ]
                ];
            }
            $this->wynik[] = [
                'name' => $nazwa . ' ' . $wykresy,
                'categories' => array_keys($kategorie),
                'series' => $series
            ];

        }
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
                foreach ($dane2 as $podzial1 => $dane3) {
                    $series[] = [
                        'name' => $podzial1,
                        'data' => $dane3,
                        'dataLabels' => [
                            'enabled' => true,
                            'format' => '{point.y:.2f}'
                        ]
                    ];
                }
                $this->wynik[] = [
                    'name' => 'Częstość wyników klasa ' . $wykresy_podzial2 . ' ' . $podzial1,
                    'categories' => array_keys($kategoria),
                    'series' => $series
                ];
            }
        }

//        var_dump($this->wynik);
    }

    private function mapuj_czestosc($dane_db, $column_srednia, $column_x, $podzial_kategorii = '', $podzial_wykresu = '')
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
            $series = [];
            foreach ($dane as $podzial1 => $dane3) {
                $series[] = [
                    'name' => $podzial1,
                    'data' => $dane3,
                    'dataLabels' => [
                        'enabled' => true,
                    ]
                ];
            }
            $this->wynik[] = [
                'name' => 'Częstość wyników klasa ' . $wykresy_podzial . ' ' . $podzial1,
                'categories' => array_keys($kategoria),
                'series' => $series
            ];
        }

//        var_dump($this->wynik);
    }

    private function mapujSredniaCalosc($id_analiza)
    {
        $dane_db = DB::select(ZapytaniaSql::SREDNIA_CALOSC_KLASA, [$id_analiza, $id_analiza]);
        $this->srednia_calosc = $chartData = $this->mapuj($dane_db, self::COLUMN_NAME_SREDNIA_PKT, self::COLUMN_NAME_KLASA, null, null, 'Średnia cała');
        return $chartData;
    }

    private function mapujSredniaDysleksja($id_analiza)
    {
        $dane_db = DB::select(ZapytaniaSql::SREDNIA_CALOSC_KLASA_DYSLEKSJA, [$id_analiza, $id_analiza]);
        $this->srednia_dysleksja = $chartData = $this->mapuj($dane_db, self::COLUMN_NAME_SREDNIA_PKT, self::COLUMN_NAME_KLASA, self::COLUMN_NAME_DYSLEKSJA, null, 'dysleksja');
        return $chartData;
    }

    private function mapujSredniaLokalizacja($id_analiza)
    {
        $dane_db = DB::select(ZapytaniaSql::SREDNIA_CALOSC_KLASA_LOKALIZACJA, [$id_analiza, $id_analiza]);
        $this->srednia_lokalizacja = $chartData = $this->mapuj($dane_db, self::COLUMN_NAME_SREDNIA_PKT, self::COLUMN_NAME_KLASA, self::COLUMN_NAME_LOKALIZACJA, null, 'lokalizacja');
        return $chartData;
    }

    private function mapujSredniaPlec($id_analiza)
    {
        $dane_db = DB::select(ZapytaniaSql::SREDNIA_CALOSC_KLASA_PLEC, [$id_analiza, $id_analiza]);
        $this->srednia_plec = $chartData = $this->mapuj($dane_db, self::COLUMN_NAME_SREDNIA_PKT, self::COLUMN_NAME_KLASA, self::COLUMN_NAME_PLEC, null, 'plec');
        return $chartData;
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
        $chartData = $this->mapuj_umiejetnosc($dane_db, self::COLUMN_NAME_SREDNIA_PKT, self::COLUMN_NAME_UMIEJETNOSC, null, self::COLUMN_NAME_OBSZAR, self::COLUMN_NAME_KLASA, 'umiejetnosc całość');
        return $chartData;
    }

    private function mapujSredniaUmiejetnoscDysleksja($id_analiza)
    {
        $dane_db = DB::select(ZapytaniaSql::SREDNIA_OBSZAR_UMIEJETNOSC_DYSLEKSJA, [$id_analiza, $id_analiza, $id_analiza, $id_analiza]);
        $chartData = $this->mapuj_umiejetnosc($dane_db, self::COLUMN_NAME_SREDNIA_PKT, self::COLUMN_NAME_UMIEJETNOSC, self::COLUMN_NAME_DYSLEKSJA, self::COLUMN_NAME_OBSZAR, self::COLUMN_NAME_KLASA, 'umiejetnosc dysleksja');
        return $chartData;
    }

    private function mapujSredniaUmiejetnoscLokalizacja($id_analiza)
    {
        $dane_db = DB::select(ZapytaniaSql::SREDNIA_OBSZAR_UMIEJETNOSC_LOKALIZACJA, [$id_analiza, $id_analiza, $id_analiza, $id_analiza]);
        $chartData = $this->mapuj_umiejetnosc($dane_db, self::COLUMN_NAME_SREDNIA_PKT, self::COLUMN_NAME_UMIEJETNOSC, self::COLUMN_NAME_LOKALIZACJA, self::COLUMN_NAME_OBSZAR, self::COLUMN_NAME_KLASA, 'umiejetnosc lokalizacja');
        return $chartData;
    }

    private function mapujSredniaUmiejetnoscPlec($id_analiza)
    {
        $dane_db = DB::select(ZapytaniaSql::SREDNIA_OBSZAR_UMIEJETNOSC_PLEC, [$id_analiza, $id_analiza, $id_analiza, $id_analiza]);
        $chartData = $this->mapuj_umiejetnosc($dane_db, self::COLUMN_NAME_SREDNIA_PKT, self::COLUMN_NAME_UMIEJETNOSC, self::COLUMN_NAME_PLEC, self::COLUMN_NAME_OBSZAR, self::COLUMN_NAME_KLASA, 'umiejetnosc plec');
        return $chartData;
    }

    private function mapujSredniaZadaniaCalosc($id_analiza)
    {
        $dane_db = DB::select(ZapytaniaSql::SREDNIA_ZADANIA_CALOSC, [$id_analiza, $id_analiza]);
        $chartData = $this->mapuj($dane_db, self::COLUMN_NAME_SREDNIA_PKT, self::COLUMN_NAME_NR_ZADANIA, null, self::COLUMN_NAME_KLASA, 'zadanie calosc');

        return $chartData;
    }

    private function mapujSredniaZadaniaDysleksja($id_analiza)
    {
        $dane_db = DB::select(ZapytaniaSql::SREDNIA_ZADANIA_DYSLEKSJA, [$id_analiza, $id_analiza]);
        $chartData = $this->mapuj($dane_db, self::COLUMN_NAME_SREDNIA_PKT, self::COLUMN_NAME_NR_ZADANIA, self::COLUMN_NAME_DYSLEKSJA, self::COLUMN_NAME_KLASA, 'zadanie dysleksja');

        return $chartData;
    }

    private function mapujSredniaZadaniaLokalizacja($id_analiza)
    {
        $dane_db = DB::select(ZapytaniaSql::SREDNIA_ZADANIA_LOKALIZACJA, [$id_analiza, $id_analiza]);
        $chartData = $this->mapuj($dane_db, self::COLUMN_NAME_SREDNIA_PKT, self::COLUMN_NAME_NR_ZADANIA, self::COLUMN_NAME_LOKALIZACJA, self::COLUMN_NAME_KLASA, 'zadanie lokalizacja');

        return $chartData;
    }

    private function mapujSredniaZadaniaPlec($id_analiza)
    {
        $dane_db = DB::select(ZapytaniaSql::SREDNIA_ZADANIA_PLEC, [$id_analiza, $id_analiza]);
        $chartData = $this->mapuj($dane_db, self::COLUMN_NAME_SREDNIA_PKT, self::COLUMN_NAME_NR_ZADANIA, self::COLUMN_NAME_PLEC, self::COLUMN_NAME_KLASA, 'zadanie plec');

        return $chartData;
    }

    private function mapujCzestoscWynikowCalosc($id_analiza)
    {
        $dane_db = DB::select(ZapytaniaSql::CZESTOSC_WYNIKOW_CALOSC, [$id_analiza, $id_analiza]);
        $chartData = $this->mapuj_czestosc($dane_db, self::COLUMN_NAME_ILOSC_WYNIKOW, self::COLUMN_NAME_SUMA, null, self::COLUMN_NAME_KLASA);

        return $chartData;
    }

    private function mapujCzestoscWynikowPlec($id_analiza)
    {
        $dane_db = DB::select(ZapytaniaSql::CZESTOSC_WYNIKOW_PLEC, [$id_analiza, $id_analiza]);
        $chartData = $this->mapuj_czestosc($dane_db, self::COLUMN_NAME_ILOSC_WYNIKOW, self::COLUMN_NAME_SUMA, self::COLUMN_NAME_PLEC, self::COLUMN_NAME_KLASA);

        return $chartData;
    }

    private function mapujCzestoscWynikowLokalizacja($id_analiza)
    {
        $dane_db = DB::select(ZapytaniaSql::CZESTOSC_WYNIKOW_LOKALIZACJA, [$id_analiza, $id_analiza]);
        $chartData = $this->mapuj_czestosc($dane_db, self::COLUMN_NAME_ILOSC_WYNIKOW, self::COLUMN_NAME_SUMA, self::COLUMN_NAME_LOKALIZACJA, self::COLUMN_NAME_KLASA);

        return $chartData;
    }

    private function mapujCzestoscWynikowDysleksja($id_analiza)
    {
        $dane_db = DB::select(ZapytaniaSql::CZESTOSC_WYNIKOW_DYSLEKSJA, [$id_analiza, $id_analiza]);
        $chartData = $this->mapuj_czestosc($dane_db, self::COLUMN_NAME_ILOSC_WYNIKOW, self::COLUMN_NAME_SUMA, self::COLUMN_NAME_DYSLEKSJA, self::COLUMN_NAME_KLASA);

        return $chartData;
    }

    private function mapujSredniaGrupyCalosc($id_analiza)
    {
        $dane_db = DB::select(ZapytaniaSql::SREDNIA_PUNKTOW_GRUPY_CALOSC, [$id_analiza, $id_analiza]);
        $chartData = $this->mapuj($dane_db, self::COLUMN_NAME_SREDNIA_PKT, self::COLUMN_NAME_KLASA, null);

        return $chartData;
    }

    private function mapujSredniaGrupyDysleksja($id_analiza)
    {
        $dane_db = DB::select(ZapytaniaSql::SREDNIA_PUNKTOW_GRUPY_DYSLEKSJA, [$id_analiza, $id_analiza]);
        $chartData = $this->mapuj($dane_db, self::COLUMN_NAME_SREDNIA_PKT, self::COLUMN_NAME_KLASA, self::COLUMN_NAME_DYSLEKSJA);

        return $chartData;
    }

    private function mapujSredniaGrupyLokalizacja($id_analiza)
    {
        $dane_db = DB::select(ZapytaniaSql::SREDNIA_PUNKTOW_GRUPY_LOKALIZACJA, [$id_analiza, $id_analiza]);
        $chartData = $this->mapuj($dane_db, self::COLUMN_NAME_SREDNIA_PKT, self::COLUMN_NAME_KLASA, self::COLUMN_NAME_LOKALIZACJA);

        return $chartData;
    }

    private function mapujSredniaGrupyPlec($id_analiza)
    {
        $dane_db = DB::select(ZapytaniaSql::SREDNIA_PUNKTOW_GRUPY_PLEC, [$id_analiza, $id_analiza]);
        $chartData = $this->mapuj($dane_db, self::COLUMN_NAME_SREDNIA_PKT, self::COLUMN_NAME_KLASA, self::COLUMN_NAME_PLEC);

        return $chartData;
    }
}