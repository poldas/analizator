<?php
namespace App;

class ParseAnalizaFileData {

    public $kolejnosc_naglowka = [ 0 => 'nazwy_zadan', 1 => 'obszar', 2 => 'umiejetnosc', 3 => 'max_punktow'];
    public $dane = [];
    public $dane_obszar = [];
    public $dane_uczniowie = [];
    public $dane_wyniki_egzaminu = [];
    public $analiza_id = null;

    public function __construct($opcje)
    {
        $this->analiza_id = $opcje['analiza_id'];
    }

    public function parsuj_dane($dane_do_sparsowania) {
        $dane = [];
        foreach ($this->kolejnosc_naglowka as $id => $name) {
            $nazwy_zadan = array_shift($dane_do_sparsowania);
            array_shift($nazwy_zadan);
            array_pop($nazwy_zadan);
            array_pop($nazwy_zadan);
            array_pop($nazwy_zadan);
            $dane[$name] = $nazwy_zadan;
        }

        $this->dane_analizy = $dane;
        $this->dane = $dane_do_sparsowania;
    }

    protected function pobierzInfoUcznia(&$tablica_wiersz)
    {
        $kod_ucznia = array_shift($tablica_wiersz);
        $lokalizacja = array_pop($tablica_wiersz);
        $dysleksja = array_pop($tablica_wiersz);
        $plec = array_pop($tablica_wiersz);
        $nr_ucznia = preg_replace("/[^0-9]/", '', $kod_ucznia);
        $klasa = preg_replace("/[0-9]/", '', $kod_ucznia);

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

    protected function dodajWyniki(&$dane_zapytania_sql, $tablica_wiersz, $info) {
        foreach ($tablica_wiersz as $i => $liczba_punktow) {
            $dane_do_inserta = [];
            $nr_zadania = trim($this->dane_analizy['nazwy_zadan'][$i]);
            $dane_do_inserta['id_analiza'] = $this->analiza_id;
            $dane_do_inserta['klasa'] = $info['klasa'];
            $dane_do_inserta['kod_ucznia'] = $info['kod_ucznia'];
            $dane_do_inserta['nr_zadania'] = $nr_zadania;
            $dane_do_inserta['liczba_punktow'] = $liczba_punktow;
            $dane_do_inserta['max_punktow'] = $this->dane_analizy['max_punktow'][$i];
            $dane_zapytania_sql[] = $dane_do_inserta;
        }
    }

    public function generuj_wyniki_egzaminu() {
        $dane_zapytania_sql = $dane_zapytania_sql_uczniowie = [];
        foreach ($this->dane as $id => $tablica_wiersz) {
            $info = $this->pobierzInfoUcznia($tablica_wiersz);
            $dane_zapytania_sql_uczniowie[] = $info;
            $this->dodajWyniki($dane_zapytania_sql, $tablica_wiersz, $info);
        }
        $dane_zapytania_sql = array_filter($dane_zapytania_sql);
        $this->dane_wyniki_egzaminu = $dane_zapytania_sql;
        $this->dane_uczniowie = $dane_zapytania_sql_uczniowie;
    }

    public function generuj_obszary() {
        $liczba_elementow = count($this->dane_analizy['umiejetnosc']);
        $dane_do_inserta = array();
        for ($i = 0; $i < $liczba_elementow; $i++) {
            $obszar = trim($this->dane_analizy['obszar'][$i]);
            $zadanie = trim($this->dane_analizy['nazwy_zadan'][$i]);
            $umiejetnosci = explode('/', trim($this->dane_analizy['umiejetnosc'][$i]));
            foreach ($umiejetnosci as $umiejetnosc) {
                $tmp = [];
                $tmp['id_analiza'] = $this->analiza_id;
                $tmp['obszar'] = $obszar;
                $tmp['umiejetnosc'] = $umiejetnosc;
                $tmp['nr_zadania'] = $zadanie;
                $dane_do_inserta[] = $tmp;
            };
        }
        $this->dane_obszar = $dane_do_inserta;
    }
}