<?php
namespace App\Logika;
use App\Logika\AnalizaDanychSql;
use Illuminate\Support\Facades\DB;

abstract class AnalizaDanychCore implements AnalizaDanychSql {

    protected $dbhandler = null;
    protected $dane_db = array();
    protected $dane = array();
    protected $dane_zadania = array();
    protected $dane_obszar = array();
    protected $klasy = array();
    protected $obszary = array();
    protected $zadania = array();

    /**
     * Pobiera dane z zapytania sql
     * @param string $sql
     */
    protected function pobierz_dane_db2($sql, $pdo_opt = null) {
        $statement = $this->dbhandler->prepare ( $sql );
        $statement->execute ();
        if(!$pdo_opt) {
            $pdo_opt = PDO::FETCH_ASSOC;
        }
        $dane_db = $statement->fetchAll ($pdo_opt);
        return $dane_db;
    }

    protected function pobierz_dane_db($sql)
    {
        $dane_db = DB::select($sql);
        return $dane_db;
    }
    protected function dodaj_wpis_komentarza($id_wykresu, $opis, $czy_wyswietlac) {
        $statement = $this->dbhandler->prepare(
            "INSERT INTO komentarz(id_wykresu, opis, czy_wyswietlac) VALUES(:id_wykresu, :opis, :czy_wyswietlac)
                ON DUPLICATE KEY UPDATE id_wykresu= :id_wykresu, opis= :opis, czy_wyswietlac= :czy_wyswietlac");
        $state = $statement->execute(
            array(
                "id_wykresu" => $id_wykresu,
                "opis" => $opis,
                "czy_wyswietlac" => (int)$czy_wyswietlac,
            ));
        return $state;
    }

    /**
     * Zaokrągla wynik
     * @param number $liczba
     */
    protected function zaokraglij($liczba) {
        $precyzja = 2;
        return round($liczba, $precyzja);
    }
}