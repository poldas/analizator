<?php
namespace App\Logika\Analizator;
use App\Logika\Analizator\AnalizaDanychCore;
use App\Logika\Highchart;

class AnalizaDanych extends AnalizaDanychCore {

    protected $dane_zmapowane = array();

    protected $dane_typ = array();

    protected $dane_typ_grupy = array();

    protected $dane_typ_obszar = array();

    protected $dane_zadania = array();

    protected $dane_czestosc_wynikow = array();

    protected $klasy = array();

    protected $obszary = array();

    protected $umiejetnosci = array();

    protected $czestosc_wynikow = array();

    protected $komentarze = array();

    const PLEC_MEZCZYZNA = 'M';
    const DYSLEKSJA = 'N';
    const LOKALIZACJA_MIASTO= 'm';

    protected $konfiguracja_typ_wykresu = array(
        self::POROWNANIE_CALOSC => array('całość'),
        self::POROWNANIE_DYSLEKSJA => array("bez dysleksji", "dysleksja"),
        self::POROWNANIE_LOKALIZACJA => array("miasto", "wieś"),
        self::POROWNANIE_PLEC => array("chłopcy", "dziewczyny"),
    );

    public function dodaj_komentarz($dane) {
        $id_wykresu = $dane['id_wykresu'];
        $opis = htmlspecialchars($dane['opis']);
        $czy_wyswietlac = $dane['czy_wyswietlac'];
        $this->dodaj_wpis_komentarza($id_wykresu, $opis, $czy_wyswietlac);
        return 1;
    }

    protected function pobierz_komentarze() {
        $komentarze = $this->pobierz_dane_db ( self::KOMENTARZE);
        foreach ($komentarze as $key => $value) {
            $id_wykresu = $value['id_wykresu'];
            $opis = $value['opis'];
            $czy_wyswietlac = $value['czy_wyswietlac'];
            $this->komentarze[$id_wykresu] = array(
                'opis' => htmlspecialchars_decode($opis),
                'czy_wyswietlac' => $czy_wyswietlac
            );

        }
    }

    public function pobierz() {
        $srednia = $this->pobierz_srednia_highchart();
        $srednia_grupy = $this->pobierz_srednia_grupy_highchart();
//        $obszary = $this->pobierz_obszar_highchart();
        $zadania = $this->pobierz_zadania_highchart();
        $czestosc = $this->pobierz_czestosc_highchart();
        $wynik = array_merge($srednia, $srednia_grupy, $czestosc, $zadania);
        return json_encode($wynik);

    }

    public function pobierz_obszar_highchart() {
        $this->pobierz_komentarze();
        $dane_db = $this->pobierz_dane_db ( self::UNION_ALL_SREDNIA_OBSZAR_UMIEJETNOSC);
        return $dane_db;

        foreach ($dane_db as $wiersz_danych) {
            $this->mapuj_dane_obszar($wiersz_danych);
        }
        $dane_do_js = array();
        $highchart = new Highchart();
        $highchart->ustaw_obszary($this->obszary)
            ->ustaw_klasy($this->klasy)
            ->ustaw_dane($this->dane_typ_obszar)
            ->ustaw_komentarz($this->komentarze)
            ->ustaw_konfiguracje($this->konfiguracja_typ_wykresu);

        return $highchart->pobierz_obszar_highchart();
    }

    public function pobierz_srednia_highchart() {
        $dane_db = $this->pobierz_dane_db ( self::UNION_ALL_SREDNIA);
        return $dane_db;

        foreach ($dane_db as $wiersz_danych) {
            $this->mapuj_srednia($wiersz_danych);
        }
        return $this->dane;

        $highchart = new Highchart();
        $highchart->ustaw_klasy($this->klasy)
            ->ustaw_dane($this->dane_typ)
            ->ustaw_komentarz($this->komentarze)
            ->ustaw_konfiguracje($this->konfiguracja_typ_wykresu);
        return $highchart->pobierz_srednia_highchart();
    }

    public function pobierz_srednia_grupy_highchart() {
//        $this->pobierz_komentarze();
        $dane_db = $this->pobierz_dane_db ( self::UNION_SREDNIA_PUNKTOW_GRUPY);
        return $dane_db;

        foreach ($dane_db as $wiersz_danych) {
            $this->mapuj_srednia_grupy($wiersz_danych);
        }
        return $this->dane_typ_grupy;


        $dane_do_js = array();
        $highchart = new Highchart();
        $highchart->ustaw_klasy($this->klasy)
            ->ustaw_dane($this->dane_typ_grupy)
            ->ustaw_komentarz($this->komentarze)
            ->ustaw_konfiguracje($this->konfiguracja_typ_wykresu);
        return $highchart->pobierz_srednia_highchart_grupy();
    }

    public function pobierz_zadania_highchart() {
//        $this->pobierz_komentarze();
        $dane_db = $this->pobierz_dane_db ( self::UNION_ALL_SREDNIA_ZADANIA);
        return $dane_db;

        uasort($dane_db, array($this, 'sort_zadania')); // sortowanie wg numerów zadań
        foreach ($dane_db as $wiersz_danych) {
            $this->mapuj_dane_zadania($wiersz_danych);
        }


        $dane_do_js = array();
        $highchart = new Highchart();
        $highchart->ustaw_zadania($this->zadania)
            ->ustaw_klasy($this->klasy)
            ->ustaw_dane($this->dane_zadania)
            ->ustaw_komentarz($this->komentarze)
            ->ustaw_konfiguracje($this->konfiguracja_typ_wykresu);

        return $highchart->pobierz_zadania_highchart();
    }

    protected function sort_zadania($a, $b) {
        return $a['nr_zadania'] >= $b['nr_zadania'];
    }
    public function pobierz_czestosc_highchart() {
//        $this->pobierz_komentarze();
        $dane_db = $this->pobierz_dane_db ( self::UNION_CZESTOSC_WYNIKOW);
        return $dane_db;

        foreach ($dane_db as $wiersz_danych) {
            $this->mapuj_dane_czestosc($wiersz_danych);
        }

        $highchart = new Highchart();
        $highchart->ustaw_czestosc_wynikow($this->czestosc_wynikow)
            ->ustaw_dane($this->dane_czestosc_wynikow)
            ->ustaw_klasy($this->klasy)
            ->ustaw_komentarz($this->komentarze)
            ->ustaw_konfiguracje($this->konfiguracja_typ_wykresu);

        return $highchart->pobierz_czestosc_wynikow_highchart();
    }

    protected function mapuj_dane_czestosc($wiersz_danych) {
        $klasa = strtoupper($wiersz_danych['klasa']);
        if($klasa == 'SZKOLA') {
            $klasa = 'Szkoła';
        }
        $this->klasy[$klasa] = $klasa;
        $ilosc_wynikow = $wiersz_danych['ilosc_wynikow'];
        $suma = $wiersz_danych['suma'];
        $this->czestosc_wynikow[$suma] = $suma;

        if (!is_null($wiersz_danych[self::POROWNANIE_DYSLEKSJA])) {
            $dysleksja = $this->pobierz_dysleksja($wiersz_danych);
            $this->dane_czestosc_wynikow[self::POROWNANIE_DYSLEKSJA][$suma][$dysleksja][$klasa] = $ilosc_wynikow;
        } else if (!is_null($wiersz_danych[self::POROWNANIE_LOKALIZACJA])) {
            $lokalizacja = $this->pobierz_lokalizacja($wiersz_danych);
            $this->dane_czestosc_wynikow[self::POROWNANIE_LOKALIZACJA][$suma][$lokalizacja][$klasa] = $ilosc_wynikow;
        } else if (!is_null($wiersz_danych[self::POROWNANIE_PLEC])) {
            $plec = $this->pobierz_plec($wiersz_danych);
            $this->dane_czestosc_wynikow[self::POROWNANIE_PLEC][$suma][$plec][$klasa] = $ilosc_wynikow;
        } else {
            $this->dane_czestosc_wynikow[self::POROWNANIE_CALOSC][$suma]['całość'][$klasa] = $ilosc_wynikow;
        }
    }

    protected function mapuj_dane_zadania($wiersz_danych) {
        $klasa = strtoupper($wiersz_danych['klasa']);
        if($klasa == 'SZKOLA') {
            $klasa = 'Szkoła';
        }
        $this->klasy[$klasa] = $klasa;
        $srednia = $this->zaokraglij($wiersz_danych['srednia_punktow']);
        $nr_zadania = $wiersz_danych['nr_zadania'];
        $this->zadania[$nr_zadania] = $nr_zadania;

        if (!is_null($wiersz_danych[self::POROWNANIE_DYSLEKSJA])) {
            $dysleksja = $this->pobierz_dysleksja($wiersz_danych);
            $this->dane_zadania[self::POROWNANIE_DYSLEKSJA][$nr_zadania][$dysleksja][$klasa] = $srednia;
        } else if (!is_null($wiersz_danych[self::POROWNANIE_LOKALIZACJA])) {
            $lokalizacja = $this->pobierz_lokalizacja($wiersz_danych);
            $this->dane_zadania[self::POROWNANIE_LOKALIZACJA][$nr_zadania][$lokalizacja][$klasa] = $srednia;
        } else if (!is_null($wiersz_danych[self::POROWNANIE_PLEC])) {
            $plec = $this->pobierz_plec($wiersz_danych);
            $this->dane_zadania[self::POROWNANIE_PLEC][$nr_zadania][$plec][$klasa] = $srednia;
        } else {
            $this->dane_zadania[self::POROWNANIE_CALOSC][$nr_zadania]['całość'][$klasa] = $srednia;
        }
    }

    protected function mapuj_dane_obszar($wiersz_danych) {
        $klasa = strtoupper($wiersz_danych['klasa']);
        if($klasa == 'SZKOLA') {
            $klasa = 'Szkoła';
        }
        $this->klasy[$klasa] = $klasa;
        $srednia = $this->zaokraglij($wiersz_danych['srednia_punktow']);
        $obszar = $wiersz_danych['obszar'];
        $umiejetnosc = !is_null($wiersz_danych['umiejetnosc']) ? $wiersz_danych['umiejetnosc'] : 'całość';
        $this->obszary[$obszar][$umiejetnosc] = $umiejetnosc;

        if (!is_null($wiersz_danych[self::POROWNANIE_DYSLEKSJA])) {
            $dysleksja = $this->pobierz_dysleksja($wiersz_danych);
            $this->dane_typ_obszar[self::POROWNANIE_DYSLEKSJA][$obszar][$umiejetnosc][$dysleksja][$klasa] = $srednia;
        } else if (!is_null($wiersz_danych[self::POROWNANIE_LOKALIZACJA])) {
            $lokalizacja = $this->pobierz_lokalizacja($wiersz_danych);
            $this->dane_typ_obszar[self::POROWNANIE_LOKALIZACJA][$obszar][$umiejetnosc][$lokalizacja][$klasa] = $srednia;
        } else if (!is_null($wiersz_danych[self::POROWNANIE_PLEC])) {
            $plec = $this->pobierz_plec($wiersz_danych);
            $this->dane_typ_obszar[self::POROWNANIE_PLEC][$obszar][$umiejetnosc][$plec][$klasa] = $srednia;
        } else {
            $this->dane_typ_obszar[self::POROWNANIE_CALOSC][$obszar][$umiejetnosc]['całość'][$klasa] = $srednia;
        }
    }

    protected function mapuj_srednia_grupy($wiersz_danych) {
        $klasa = strtoupper($wiersz_danych['klasa']);
        if($klasa == 'SZKOLA') {
            $klasa = 'Szkoła';
        }
        $this->klasy[$klasa] = $klasa;
        $srednia = $this->zaokraglij($wiersz_danych['srednia_punktow']);
        if (!is_null($wiersz_danych[self::POROWNANIE_DYSLEKSJA])) {
            $dysleksja = $this->pobierz_dysleksja($wiersz_danych);
            $this->dane_typ_grupy[self::POROWNANIE_DYSLEKSJA][$dysleksja][$klasa] = $srednia;
        } else if (!is_null($wiersz_danych[self::POROWNANIE_LOKALIZACJA])) {
            $lokalizacja = $this->pobierz_lokalizacja($wiersz_danych);
            $this->dane_typ_grupy[self::POROWNANIE_LOKALIZACJA][$lokalizacja][$klasa] = $srednia;
        } else if (!is_null($wiersz_danych[self::POROWNANIE_PLEC])) {
            $plec = $this->pobierz_plec($wiersz_danych);
            $this->dane_typ_grupy[self::POROWNANIE_PLEC][$plec][$klasa] = $srednia;
        } else {
            $this->dane_typ_grupy[self::POROWNANIE_CALOSC]['całość'][$klasa] = $srednia;
        }
    }

    protected function mapuj_srednia($wiersz_danych) {
        $wiersz_danych->klasa;
        $klasa = strtoupper($wiersz_danych->klasa);
        if($klasa == 'SZKOLA') {
            $klasa = 'Szkoła';
        }
        $this->klasy[$klasa] = $klasa;
        $srednia = $this->zaokraglij($wiersz_danych->srednia_punktow);
        if (!is_null($wiersz_danych[self::POROWNANIE_DYSLEKSJA])) {
            $dysleksja = $this->pobierz_dysleksja($wiersz_danych);
            $this->dane_typ[self::POROWNANIE_DYSLEKSJA][$dysleksja][$klasa] = $srednia;
        } else if (!is_null($wiersz_danych[self::POROWNANIE_LOKALIZACJA])) {
            $lokalizacja = $this->pobierz_lokalizacja($wiersz_danych);
            $this->dane_typ[self::POROWNANIE_LOKALIZACJA][$lokalizacja][$klasa] = $srednia;
        } else if (!is_null($wiersz_danych[self::POROWNANIE_PLEC])) {
            $plec = $this->pobierz_plec($wiersz_danych);
            $this->dane_typ[self::POROWNANIE_PLEC][$plec][$klasa] = $srednia;
        } else {
            $this->dane_typ[self::POROWNANIE_CALOSC]['całość'][$klasa] = $srednia;
        }

    }

    protected function pobierz_dysleksja($wiersz_danych) {
        $dysleksja = $wiersz_danych[self::POROWNANIE_DYSLEKSJA] == self::DYSLEKSJA? 'bez dysleksji' : 'dysleksja';
        return $dysleksja;
    }
    protected function pobierz_lokalizacja($wiersz_danych) {
        $lokalizacja = $wiersz_danych[self::POROWNANIE_LOKALIZACJA] == self::LOKALIZACJA_MIASTO? 'miasto' : 'wieś';
        return $lokalizacja;
    }
    protected function pobierz_plec($wiersz_danych) {
        $plec = $wiersz_danych[self::POROWNANIE_PLEC] == self::PLEC_MEZCZYZNA? 'chłopcy' : 'dziewczyny';
        return $plec;
    }
}
