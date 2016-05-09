<?php
namespace App\Logika;
class Highchart {

    private $obszary = array();
    private $klasy = array();
    private $dane = array();
    private $zadania = array();
    private $komentarz = array();
    private $dane_czestosc_wynikow = array();
    private $konfiguracja_typ_wykresu = array();
    private $czestosc_wynikow = array();

    private $tablica_sortowania_obszarIII = array(
        'treść' => 11,
        'segmentacja' => 12,
        'styl' => 13,
        'język' => 14,
        'ortografia' => 15,
        'interpunkcja' => 16,
        'całość' => 17
    );

    protected function sort_umiejetnosci($a, $b) {
        $a_map = strtolower($a);
        $b_map = strtolower($b);
        $a = isset($this->tablica_sortowania_obszarIII[$a_map])? $this->tablica_sortowania_obszarIII[$a_map] : (float)$a;
        $b = isset($this->tablica_sortowania_obszarIII[$b_map])? $this->tablica_sortowania_obszarIII[$b_map] : (float)$b;
        return $a >= $b;
    }

    public function pobierz_obszar_highchart() {
        $dane_do_js = array();
        foreach ($this->obszary as $obszar => $umiejetnosci) {
            $kategorie = array_keys($umiejetnosci);
            if ($obszar == 'III') {
                uasort($kategorie, array($this, 'sort_umiejetnosci'));
                $kategorie = array_values($kategorie);
            }
            foreach ($this->klasy as $key => $klasa) {
                foreach ($this->konfiguracja_typ_wykresu as $rodzaj_wykresu => $dane) {
                    $id_wykresu = "Obszar".$obszar."typ_".$rodzaj_wykresu."Klasa".$klasa;
                    $komentarz = $this->pobierz_komentarz($id_wykresu);
                    $typ_rodzaj_wykresu = $rodzaj_wykresu == 'całość'? '' : $rodzaj_wykresu;
                    if ($typ_rodzaj_wykresu == 'plec') {
                        $typ_rodzaj_wykresu = 'płeć';
                    }
                    $typ_klasa = $klasa == "SZKOLA"? " Szkoła" : " klasa ".$klasa;
                    $nazwa = "Obszar ".$obszar." ".$typ_rodzaj_wykresu.$typ_klasa;
                    $czy_wyswietlac = $this->czy_wyswietlac($id_wykresu);
                    $dane_do_js[] = array(
                        'series' => $this->mapuj_highchart_obszar($obszar, $rodzaj_wykresu, $dane, $kategorie, $klasa),
                        'categories' => $kategorie,
                        'id_wykres' => $id_wykresu,
                        'komentarz' => $komentarz,
                        'rodzaj_wykresu' => "Umiejętności",
                        'opisY' => 'Średnia',
                        'czy_wyswietlac' => $czy_wyswietlac,
                        'nazwa' => $nazwa,
                        'opcje' => array(
                            'max' => 1
                        )
                    );
                }
            }
        }
        return  $dane_do_js;
    }

    protected function mapuj_highchart_obszar($obszar, $rodzaj_wykresu, $dane, $kategorie, $klasa) {
        $wyjscie = array();
        foreach ($dane as $typ_danych) {
            $srednie = array();
            foreach ($kategorie as $umiejetnosc) {
            	$srednia_tmp = isset($this->dane[$rodzaj_wykresu][$obszar][$umiejetnosc][$typ_danych][$klasa])?
            		$this->dane[$rodzaj_wykresu][$obszar][$umiejetnosc][$typ_danych][$klasa] : 0;
                $srednie[] = $srednia_tmp;
            }
            $wyjscie[] = array(
                'name' => $typ_danych == 'całość'? "bez grup" : $typ_danych,
                'data' => $srednie
            );
        }
        return $wyjscie;
    }

    protected function czy_wyswietlac($id_wykresu) {
        $czy_wyswietlac = isset($this->komentarz[$id_wykresu]['czy_wyswietlac']) ?
             (int)$this->komentarz[$id_wykresu]['czy_wyswietlac'] : 0;
        return $czy_wyswietlac;
    }

    protected function pobierz_komentarz($id_wykresu) {
        $komentarz = isset($this->komentarz[$id_wykresu]['opis'])? $this->komentarz[$id_wykresu]['opis'] : array('opis' => '');
        return $komentarz;
    }

    public function pobierz_srednia_highchart() {
        $dane_do_js = array();
        $kategorie = array_keys($this->klasy);
        foreach ($this->konfiguracja_typ_wykresu as $rodzaj_wykresu => $dane) {
            $id_wykresu = "srednia_typ_".$rodzaj_wykresu."Klasy";
            $komentarz = $this->pobierz_komentarz($id_wykresu);
            $czy_wyswietlac = $this->czy_wyswietlac($id_wykresu);
            $typ_rodzaj_wykresu = $rodzaj_wykresu == 'całość'? '' : $rodzaj_wykresu;
            if ($typ_rodzaj_wykresu == 'plec') {
                $typ_rodzaj_wykresu = 'płeć';
            }
            $nazwa = "Średnia w % ".$typ_rodzaj_wykresu;
            $dane_do_js[] = array(
                'series' => $this->mapuj_srednia_obszar($rodzaj_wykresu, $dane, $kategorie),
                'categories' => $kategorie,
                'id_wykres' => $id_wykresu,
                'komentarz' => $komentarz,
                'rodzaj_wykresu' => "Klasy + szkoła",
                'opisY' => 'Średnia',
                'czy_wyswietlac' => $czy_wyswietlac,
                'nazwa' => $nazwa,
                'opcje' => array(
                    'max' => 1
                )
            );
        }
        return $dane_do_js;
    }

    protected function mapuj_srednia_obszar($rodzaj_wykresu, $dane, $kategorie) {
        $wyjscie = array();
        foreach ($dane as $typ_danych) {
            $srednie = array();
            foreach ($kategorie as $klasa) {
            	$srednia_tmp = isset($this->dane[$rodzaj_wykresu][$typ_danych][$klasa])? 
            		$this->dane[$rodzaj_wykresu][$typ_danych][$klasa] : 0;
                $srednie[] = $srednia_tmp;
            }
            $wyjscie[] = array(
                'name' => $typ_danych == 'całość'? "bez grup" : $typ_danych,
                'data' => $srednie
            );
        }
        return $wyjscie;
    }

    public function pobierz_srednia_highchart_grupy() {
        $dane_do_js = array();
        $kategorie = array_keys($this->klasy);
        foreach ($this->konfiguracja_typ_wykresu as $rodzaj_wykresu => $dane) {
            $id_wykresu = "srednia_grupy_typ_".$rodzaj_wykresu."Klasy";
            $komentarz = $this->pobierz_komentarz($id_wykresu);
            $czy_wyswietlac = $this->czy_wyswietlac($id_wykresu);
            $typ_rodzaj_wykresu = $rodzaj_wykresu == 'całość'? '' : $rodzaj_wykresu;
            if ($typ_rodzaj_wykresu == 'plec') {
                $typ_rodzaj_wykresu = 'płeć';
            }
            $nazwa = "Średnia w pkt. ".$typ_rodzaj_wykresu;
            $dane_do_js[] = array(
                'series' => $this->mapuj_srednia_obszar($rodzaj_wykresu, $dane, $kategorie),
                'categories' => $kategorie,
                'id_wykres' => $id_wykresu,
                'komentarz' => $komentarz,
                'rodzaj_wykresu' => "Klasy + szkoła",
                'opisY' => 'Średnia',
                'czy_wyswietlac' => $czy_wyswietlac,
                'nazwa' => $nazwa,
                'opcje' => array()
            );
        }
        return $dane_do_js;
    }

    public function pobierz_zadania_highchart() {
        $dane_do_js = array();
        $kategorie = array_keys($this->zadania);
        foreach ($this->klasy as $key => $klasa) {
            foreach ($this->konfiguracja_typ_wykresu as $rodzaj_wykresu => $dane) {
                $id_wykresu = "zadania_typ_".$rodzaj_wykresu."Klasa".$klasa;
                $komentarz = $this->pobierz_komentarz($id_wykresu);
                $typ_rodzaj_wykresu = $rodzaj_wykresu == 'całość'? '' : $rodzaj_wykresu;
                if ($typ_rodzaj_wykresu == 'plec') {
                    $typ_rodzaj_wykresu = 'płeć';
                }
                $nazwa = "Zadania ".$typ_rodzaj_wykresu." ".$klasa;
                $czy_wyswietlac = $this->czy_wyswietlac($id_wykresu);
                $dane_do_js[] = array(
                    'series' => $this->mapuj_highchart_zadania($rodzaj_wykresu, $dane, $kategorie, $klasa),
                    'categories' => $kategorie,
                    'id_wykres' => $id_wykresu,
                    'komentarz' => $komentarz,
                    'rodzaj_wykresu' => "Nr zadania",
                    'opisY' => 'Średnia',
                    'czy_wyswietlac' => $czy_wyswietlac,
                    'nazwa' => $nazwa,
                    'opcje' => array(
                        'max' => 1
                    )
                );
            }
        }
        return  $dane_do_js;
    }

    protected function mapuj_highchart_zadania($rodzaj_wykresu, $dane, $kategorie, $klasa) {
        $wyjscie = array();
        foreach ($dane as $typ_danych) {
            $srednie = array();
            foreach ($kategorie as $nr_zadania) {
                $srednia_tmp = isset($this->dane[$rodzaj_wykresu][$nr_zadania][$typ_danych][$klasa])?
                	$this->dane[$rodzaj_wykresu][$nr_zadania][$typ_danych][$klasa] : 0;
                $srednie[] = $srednia_tmp;
            }
            $wyjscie[] = array(
                'name' => $typ_danych == 'całość'? "bez grup" : $typ_danych,
                'data' => $srednie
            );
        }
        return $wyjscie;
    }

    public function pobierz_czestosc_wynikow_highchart() {
        $dane_do_js = array();
        $kategorie = array_keys($this->czestosc_wynikow);
        foreach ($this->klasy as $key => $klasa) {
            foreach ($this->konfiguracja_typ_wykresu as $rodzaj_wykresu => $dane) {
                $id_wykresu = "czestos_wynikow_typ_".$rodzaj_wykresu."Klasa".$klasa;
                $komentarz = $this->pobierz_komentarz($id_wykresu);
                $typ_rodzaj_wykresu = $rodzaj_wykresu == 'całość'? '' : $rodzaj_wykresu;
                if ($typ_rodzaj_wykresu == 'plec') {
                    $typ_rodzaj_wykresu = 'płeć';
                }
                $nazwa = "Częstość wyników ".$typ_rodzaj_wykresu." ".$klasa;
                $czy_wyswietlac = $this->czy_wyswietlac($id_wykresu);
                $dane_do_js[] = array(
                    'series' => $this->mapuj_highchart_czestosc_wynikow($rodzaj_wykresu, $dane, $kategorie, $klasa),
                    'categories' => $kategorie,
                    'id_wykres' => $id_wykresu,
                    'komentarz' => $komentarz,
                    'rodzaj_wykresu' => "Częstość wyników",
                    'opisY' => 'Liczba wystąpień',
                    'czy_wyswietlac' => $czy_wyswietlac,
                    'nazwa' => $nazwa,
                    'opcje' => array(
                        'max' => 15
                    )
                );
            }
        }
        return $dane_do_js;

    }

    protected function mapuj_highchart_czestosc_wynikow($rodzaj_wykresu, $dane, $kategorie, $klasa) {
        $wyjscie = array();
        foreach ($dane as $typ_danych) {
            $suma = array();
            foreach ($kategorie as $czestosc_wynikow) {
            	$suma_tmp = isset($this->dane[$rodzaj_wykresu][$czestosc_wynikow][$typ_danych][$klasa])?
            		(int)$this->dane[$rodzaj_wykresu][$czestosc_wynikow][$typ_danych][$klasa] : 0;
            	$suma[] = $suma_tmp;
            }
            $wyjscie[] = array(
                'name' => $typ_danych == 'całość'? "bez grup" : $typ_danych,
                'data' => $suma
            );
        }
        return $wyjscie;
    }

    public function ustaw_obszary($obszary) {
        $this->obszary = $obszary;
        return $this;
    }
    public function ustaw_klasy($klasy) {
        $this->klasy = $klasy;
        return $this;
    }
    public function ustaw_dane($dane) {
        $this->dane = $dane;
        return $this;
    }
    public function ustaw_zadania($zadania) {
        $this->zadania = $zadania;
        return $this;
    }
    public function ustaw_czestosc_wynikow($czestosc_wynikow) {
        $this->czestosc_wynikow = $czestosc_wynikow;
        return $this;
    }
    public function ustaw_konfiguracje($konfiguracja) {
        $this->konfiguracja_typ_wykresu = $konfiguracja;
        return $this;
    }
    public function ustaw_komentarz($komentarz) {
        $this->komentarz = $komentarz;
        return $this;
    }
}