<?php
namespace App\Logika\Analizator;
class ZapytaniaSql {

    const SREDNIA_CALOSC_KLASA = "
            select sum(we.liczba_punktow)/sum(we.max_punktow) as srednia_punktow,
                'szkola' klasa
            from wyniki_egzaminu as we
            left join uczniowie as u
                ON u.kod_ucznia = we.kod_ucznia AND we.id_analiza = u.id_analiza
            WHERE we.id_analiza = ?
            UNION
            select sum(we.liczba_punktow)/sum(we.max_punktow) as srednia_punktow,
                u.klasa
            from wyniki_egzaminu as we
            left join uczniowie as u
                ON u.kod_ucznia = we.kod_ucznia AND we.id_analiza = u.id_analiza
            WHERE we.id_analiza = ?
            GROUP by u.klasa
            ORDER BY klasa
    ";

    const SREDNIA_CALOSC_KLASA_DYSLEKSJA = "
            select sum(we.liczba_punktow)/sum(we.max_punktow) as srednia_punktow,
                u.dysleksja,
                'szkola' klasa
            from wyniki_egzaminu as we
            left join uczniowie as u
                ON u.kod_ucznia = we.kod_ucznia AND we.id_analiza = u.id_analiza
            WHERE we.id_analiza = ?
            GROUP BY u.dysleksja
            UNION
            select sum(we.liczba_punktow)/sum(we.max_punktow) as srednia_punktow,
                u.dysleksja,
                we.klasa
            from wyniki_egzaminu as we
            left join uczniowie as u
                ON u.kod_ucznia = we.kod_ucznia AND we.id_analiza = u.id_analiza
            WHERE we.id_analiza = ?
            GROUP BY u.dysleksja, we.klasa
            
            ORDER BY klasa
    ";

    const SREDNIA_CALOSC_KLASA_LOKALIZACJA = "
            select sum(we.liczba_punktow)/sum(we.max_punktow) as srednia_punktow,
                u.lokalizacja,
                'szkola' klasa
            from wyniki_egzaminu as we
            left join uczniowie as u
                ON u.kod_ucznia = we.kod_ucznia AND we.id_analiza = u.id_analiza
            WHERE we.id_analiza = ?
            GROUP BY u.lokalizacja
            UNION
            select sum(we.liczba_punktow)/sum(we.max_punktow) as srednia_punktow,
                u.lokalizacja,
                we.klasa
            from wyniki_egzaminu as we
            left join uczniowie as u
                ON u.kod_ucznia = we.kod_ucznia AND we.id_analiza = u.id_analiza
            WHERE we.id_analiza = ?
            GROUP BY u.lokalizacja, we.klasa
            
            ORDER BY klasa
    ";

    const SREDNIA_CALOSC_KLASA_PLEC = "
            select sum(we.liczba_punktow)/sum(we.max_punktow) as srednia_punktow,
                u.plec,
                'szkola' klasa
            from wyniki_egzaminu as we
            left join uczniowie as u
                ON u.kod_ucznia = we.kod_ucznia AND we.id_analiza = u.id_analiza
            WHERE we.id_analiza = ?
            GROUP BY u.plec
            UNION
            select sum(we.liczba_punktow)/sum(we.max_punktow) as srednia_punktow,
                u.plec,
                we.klasa
            from wyniki_egzaminu as we
            left join uczniowie as u
                ON u.kod_ucznia = we.kod_ucznia AND we.id_analiza = u.id_analiza
            WHERE we.id_analiza = ?
            GROUP BY u.plec, we.klasa
            
            ORDER BY klasa
    ";

    const UNION_ALL_SREDNIA = "
            select sum(we.liczba_punktow)/sum(we.max_punktow) as srednia_punktow,
                null dysleksja,
                null lokalizacja,
                null plec,
                'szkola' klasa
            from wyniki_egzaminu as we
            left join uczniowie as u
                ON u.kod_ucznia = we.kod_ucznia AND we.id_analiza = u.id_analiza
            WHERE we.id_analiza = ?
            UNION
            select sum(we.liczba_punktow)/sum(we.max_punktow) as srednia_punktow,
                null dysleksja,
                null lokalizacja,
                null plec,
                u.klasa
            from wyniki_egzaminu as we
            left join uczniowie as u
                ON u.kod_ucznia = we.kod_ucznia AND we.id_analiza = u.id_analiza
            WHERE we.id_analiza = ?
            GROUP by u.klasa
            UNION
            select sum(we.liczba_punktow)/sum(we.max_punktow) as srednia_punktow,
                u.dysleksja,
                null lokalizacja,
                null plec,
                'szkola' klasa
            from wyniki_egzaminu as we
            left join uczniowie as u
                ON u.kod_ucznia = we.kod_ucznia AND we.id_analiza = u.id_analiza
            WHERE we.id_analiza = ?
            GROUP BY u.dysleksja
            UNION
            select sum(we.liczba_punktow)/sum(we.max_punktow) as srednia_punktow,
                null dysleksja,
                u.lokalizacja,
                null plec,
                'szkola' klasa
            from wyniki_egzaminu as we
            left join uczniowie as u
                ON u.kod_ucznia = we.kod_ucznia AND we.id_analiza = u.id_analiza
            WHERE we.id_analiza = ?
            GROUP BY u.lokalizacja
            UNION
            select sum(we.liczba_punktow)/sum(we.max_punktow) as srednia_punktow,
                null dysleksja,
                null lokalizacja,
                u.plec,
                'szkola' klasa
            from wyniki_egzaminu as we
            left join uczniowie as u
                ON u.kod_ucznia = we.kod_ucznia AND we.id_analiza = u.id_analiza
            WHERE we.id_analiza = ?
            GROUP BY u.plec
            UNION
            select sum(we.liczba_punktow)/sum(we.max_punktow) as srednia_punktow,
                u.dysleksja,
                null lokalizacja,
                null plec,
                we.klasa
            from wyniki_egzaminu as we
            left join uczniowie as u
                ON u.kod_ucznia = we.kod_ucznia AND we.id_analiza = u.id_analiza
            WHERE we.id_analiza = ?
            GROUP BY u.dysleksja, we.klasa
            UNION
            select sum(we.liczba_punktow)/sum(we.max_punktow) as srednia_punktow,
                null dysleksja,
                u.lokalizacja,
                null plec,
                we.klasa
            from wyniki_egzaminu as we
            left join uczniowie as u
                ON u.kod_ucznia = we.kod_ucznia AND we.id_analiza = u.id_analiza
            WHERE we.id_analiza = ?
            GROUP BY u.lokalizacja, we.klasa
            UNION
            select sum(we.liczba_punktow)/sum(we.max_punktow) as srednia_punktow, null dysleksja, null lokalizacja, u.plec, we.klasa
            from wyniki_egzaminu as we
            left join uczniowie as u
                ON u.kod_ucznia = we.kod_ucznia AND we.id_analiza = u.id_analiza
            WHERE we.id_analiza = ?
            GROUP BY u.plec, we.klasa
            ORDER BY klasa
            ";

    const SREDNIA_ZADANIA_CALOSC = "
          select sum(we.liczba_punktow)/sum(we.max_punktow) as srednia_punktow,
                we.nr_zadania,
                'szkola' klasa
            from wyniki_egzaminu as we
            left join uczniowie as u
                ON u.kod_ucznia = we.kod_ucznia AND we.id_analiza = u.id_analiza
            WHERE we.id_analiza = ?
            GROUP BY we.nr_zadania
            UNION
            select sum(we.liczba_punktow)/sum(we.max_punktow) as srednia_punktow,
                we.nr_zadania,
                we.klasa
            from wyniki_egzaminu as we
            left join uczniowie as u
                ON u.kod_ucznia = we.kod_ucznia AND we.id_analiza = u.id_analiza
            WHERE we.id_analiza = ?
            GROUP BY we.nr_zadania, we.klasa
            ORDER BY CAST(nr_zadania as DECIMAL(10,5))
            ";

    const SREDNIA_ZADANIA_DYSLEKSJA = "
          select sum(we.liczba_punktow)/sum(we.max_punktow) as srednia_punktow,
                we.nr_zadania,
                u.dysleksja,
                'szkola' klasa
            from wyniki_egzaminu as we
            left join uczniowie as u
                ON u.kod_ucznia = we.kod_ucznia AND we.id_analiza = u.id_analiza
            WHERE we.id_analiza = ?
            GROUP BY we.nr_zadania, u.dysleksja
            UNION
            select sum(we.liczba_punktow)/sum(we.max_punktow) as srednia_punktow,
                we.nr_zadania,
                u.dysleksja,
                we.klasa
            from wyniki_egzaminu as we
            left join uczniowie as u
                ON u.kod_ucznia = we.kod_ucznia AND we.id_analiza = u.id_analiza
            WHERE we.id_analiza = ?
            GROUP BY we.nr_zadania, u.dysleksja, we.klasa
            ORDER BY klasa, CAST(nr_zadania as DECIMAL(10,5))
            ";

    const SREDNIA_ZADANIA_LOKALIZACJA = "
          select sum(we.liczba_punktow)/sum(we.max_punktow) as srednia_punktow,
                we.nr_zadania,
                u.lokalizacja,
                'szkola' klasa
            from wyniki_egzaminu as we
            left join uczniowie as u
                ON u.kod_ucznia = we.kod_ucznia AND we.id_analiza = u.id_analiza
            WHERE we.id_analiza = ?
            GROUP BY we.nr_zadania, u.lokalizacja
            UNION
            select sum(we.liczba_punktow)/sum(we.max_punktow) as srednia_punktow,
                we.nr_zadania,
                u.lokalizacja,
                we.klasa
            from wyniki_egzaminu as we
            left join uczniowie as u
                ON u.kod_ucznia = we.kod_ucznia AND we.id_analiza = u.id_analiza
            WHERE we.id_analiza = ?
            GROUP BY we.nr_zadania, u.lokalizacja, we.klasa
            ORDER BY klasa, CAST(nr_zadania as DECIMAL(10,5))
            ";

    const SREDNIA_ZADANIA_PLEC = "
          select sum(we.liczba_punktow)/sum(we.max_punktow) as srednia_punktow,
                we.nr_zadania,
                u.plec,
                'szkola' klasa
            from wyniki_egzaminu as we
            left join uczniowie as u
                ON u.kod_ucznia = we.kod_ucznia AND we.id_analiza = u.id_analiza
            WHERE we.id_analiza = ?
            GROUP BY we.nr_zadania, u.plec
            UNION
            select sum(we.liczba_punktow)/sum(we.max_punktow) as srednia_punktow,
                we.nr_zadania,
                u.plec,
                we.klasa
            from wyniki_egzaminu as we
            left join uczniowie as u
                ON u.kod_ucznia = we.kod_ucznia AND we.id_analiza = u.id_analiza
            WHERE we.id_analiza = ?
            GROUP BY we.nr_zadania, u.plec, we.klasa
            ORDER BY klasa, CAST(nr_zadania as DECIMAL(10,5))
            ";


    const UNION_ALL_SREDNIA_ZADANIA = "
            select sum(we.liczba_punktow)/sum(we.max_punktow) as srednia_punktow,
                we.nr_zadania,
                null dysleksja,
                null lokalizacja,
                null plec,
                'szkola' klasa
            from wyniki_egzaminu as we
            left join uczniowie as u
                ON u.kod_ucznia = we.kod_ucznia AND we.id_analiza = u.id_analiza
            WHERE we.id_analiza = ?
             GROUP by we.nr_zadania
            UNION
            select sum(we.liczba_punktow)/sum(we.max_punktow) as srednia_punktow,
                we.nr_zadania,
                null dysleksja,
                null lokalizacja,
                null plec,
                u.klasa
            from wyniki_egzaminu as we
            left join uczniowie as u
                ON u.kod_ucznia = we.kod_ucznia AND we.id_analiza = u.id_analiza
            WHERE we.id_analiza = ?
            GROUP by we.nr_zadania, u.klasa
            UNION
            select sum(we.liczba_punktow)/sum(we.max_punktow) as srednia_punktow,
                we.nr_zadania,
                u.dysleksja,
                null lokalizacja,
                null plec,
                'szkola' klasa
            from wyniki_egzaminu as we
            left join uczniowie as u
                ON u.kod_ucznia = we.kod_ucznia AND we.id_analiza = u.id_analiza
            WHERE we.id_analiza = ?
            GROUP BY we.nr_zadania, u.dysleksja
            UNION
            select sum(we.liczba_punktow)/sum(we.max_punktow) as srednia_punktow,
                we.nr_zadania,
                null dysleksja,
                u.lokalizacja,
                null plec,
                'szkola' klasa
            from wyniki_egzaminu as we
            left join uczniowie as u
                on u.kod_ucznia = we.kod_ucznia
            GROUP BY we.nr_zadania, u.lokalizacja
            UNION
            select sum(we.liczba_punktow)/sum(we.max_punktow) as srednia_punktow,
                we.nr_zadania,
                null dysleksja,
                null lokalizacja,
                u.plec,
                'szkola' klasa
            from wyniki_egzaminu as we
            left join uczniowie as u
                on u.kod_ucznia = we.kod_ucznia
            GROUP BY we.nr_zadania, u.plec
            UNION
            select sum(we.liczba_punktow)/sum(we.max_punktow) as srednia_punktow,
                we.nr_zadania,
                u.dysleksja,
                null lokalizacja,
                null plec,
                we.klasa
            from wyniki_egzaminu as we
            left join uczniowie as u
                on u.kod_ucznia = we.kod_ucznia
            GROUP BY we.nr_zadania, u.dysleksja, we.klasa
            UNION
            select sum(we.liczba_punktow)/sum(we.max_punktow) as srednia_punktow,
                we.nr_zadania,
                null dysleksja,
                u.lokalizacja,
                null plec,
                we.klasa
            from wyniki_egzaminu as we
            left join uczniowie as u
                on u.kod_ucznia = we.kod_ucznia AND we.id_analiza = u.id_analiza
            WHERE we.id_analiza = ?
            GROUP BY we.nr_zadania, u.lokalizacja, we.klasa
            UNION
            select sum(we.liczba_punktow)/sum(we.max_punktow) as srednia_punktow,
                we.nr_zadania,
                null dysleksja,
                null lokalizacja,
                u.plec,
                we.klasa
            from wyniki_egzaminu as we
            left join uczniowie as u
                on u.kod_ucznia = we.kod_ucznia AND we.id_analiza = u.id_analiza
            WHERE we.id_analiza = ?
            GROUP BY we.nr_zadania, u.plec, we.klasa";

    const SREDNIA_OBSZAR_CALOSC = "
            select sum(we.liczba_punktow)/sum(we.max_punktow) as srednia_punktow,
                o.obszar,
                'szkola' klasa
            from wyniki_egzaminu as we
            left join uczniowie as u
                on u.kod_ucznia = we.kod_ucznia AND we.id_analiza = u.id_analiza
            left join obszary as o
                on o.nr_zadania = we.nr_zadania AND we.id_analiza = u.id_analiza
            WHERE we.id_analiza = ?
             GROUP by o.obszar
            UNION
            select sum(we.liczba_punktow)/sum(we.max_punktow) as srednia_punktow,
                o.obszar,
                we.klasa
            from wyniki_egzaminu as we
            left join uczniowie as u
                ON u.kod_ucznia = we.kod_ucznia AND we.id_analiza = u.id_analiza
            left join obszary as o
                on o.nr_zadania = we.nr_zadania AND we.id_analiza = u.id_analiza
            WHERE we.id_analiza = ?
            GROUP by o.obszar, we.klasa
            ORDER BY klasa
    ";

    const SREDNIA_OBSZAR_DYSLEKSJA = "
            select sum(we.liczba_punktow)/sum(we.max_punktow) as srednia_punktow,
                o.obszar,
                u.dysleksja,
                'szkola' klasa
            from wyniki_egzaminu as we
            left join uczniowie as u
                on u.kod_ucznia = we.kod_ucznia AND we.id_analiza = u.id_analiza
            left join obszary as o
                on o.nr_zadania = we.nr_zadania AND we.id_analiza = u.id_analiza
            WHERE we.id_analiza = ?
             GROUP by o.obszar, u.dysleksja
            UNION
            select sum(we.liczba_punktow)/sum(we.max_punktow) as srednia_punktow,
                o.obszar,
                u.dysleksja,
                we.klasa
            from wyniki_egzaminu as we
            left join uczniowie as u
                ON u.kod_ucznia = we.kod_ucznia AND we.id_analiza = u.id_analiza
            left join obszary as o
                on o.nr_zadania = we.nr_zadania AND we.id_analiza = u.id_analiza
            WHERE we.id_analiza = ?
            GROUP by o.obszar, we.klasa, u.dysleksja
            ORDER BY klasa
    ";

    const SREDNIA_OBSZAR_LOKALIZACJA = "
            select sum(we.liczba_punktow)/sum(we.max_punktow) as srednia_punktow,
                o.obszar,
                u.lokalizacja,
                'szkola' klasa
            from wyniki_egzaminu as we
            left join uczniowie as u
                ON u.kod_ucznia = we.kod_ucznia AND we.id_analiza = u.id_analiza
            left join obszary as o
                on o.nr_zadania = we.nr_zadania AND we.id_analiza = u.id_analiza
            WHERE we.id_analiza = ?
             GROUP by o.obszar, u.lokalizacja
            UNION
            select sum(we.liczba_punktow)/sum(we.max_punktow) as srednia_punktow,
                o.obszar,
                u.lokalizacja,
                we.klasa
            from wyniki_egzaminu as we
            left join uczniowie as u
                on u.kod_ucznia = we.kod_ucznia AND we.id_analiza = u.id_analiza
            left join obszary as o
                on o.nr_zadania = we.nr_zadania AND we.id_analiza = u.id_analiza
            WHERE we.id_analiza = ?
            GROUP by o.obszar, we.klasa, u.lokalizacja
            ORDER BY klasa
    ";

    const SREDNIA_OBSZAR_PLEC = "
            select sum(we.liczba_punktow)/sum(we.max_punktow) as srednia_punktow,
                o.obszar,
                u.plec,
                'szkola' klasa
            from wyniki_egzaminu as we
            left join uczniowie as u
                on u.kod_ucznia = we.kod_ucznia AND we.id_analiza = u.id_analiza
            left join obszary as o
                on o.nr_zadania = we.nr_zadania AND we.id_analiza = u.id_analiza
            WHERE we.id_analiza = ?
            GROUP by o.obszar, u.plec
            UNION
            select sum(we.liczba_punktow)/sum(we.max_punktow) as srednia_punktow,
                o.obszar,
                u.plec,
                we.klasa
            from wyniki_egzaminu as we
            left join uczniowie as u
                on u.kod_ucznia = we.kod_ucznia AND we.id_analiza = u.id_analiza
            left join obszary as o
                on o.nr_zadania = we.nr_zadania AND we.id_analiza = u.id_analiza
            WHERE we.id_analiza = ?
            GROUP by o.obszar, we.klasa, u.plec
            ORDER BY klasa
    ";




    const SREDNIA_OBSZAR_UMIEJETNOSC_CALOSC = "
            select sum(we.liczba_punktow)/sum(we.max_punktow) as srednia_punktow,
                o.obszar,
                o.umiejetnosc,
                'szkola' klasa
            from wyniki_egzaminu as we
            left join uczniowie as u
                on u.kod_ucznia = we.kod_ucznia AND we.id_analiza = u.id_analiza
            left join obszary as o
                on o.nr_zadania = we.nr_zadania AND we.id_analiza = o.id_analiza
            WHERE we.id_analiza = ?
            GROUP by o.obszar, o.umiejetnosc
            UNION
            select sum(we.liczba_punktow)/sum(we.max_punktow) as srednia_punktow,
                o.obszar,
                o.umiejetnosc,
                we.klasa
            from wyniki_egzaminu as we
            left join uczniowie as u
                on u.kod_ucznia = we.kod_ucznia AND we.id_analiza = u.id_analiza
            left join obszary as o
                on o.nr_zadania = we.nr_zadania AND we.id_analiza = o.id_analiza
            WHERE we.id_analiza = ?
            GROUP by o.obszar, o.umiejetnosc, we.klasa
            UNION
            select sum(we.liczba_punktow)/sum(we.max_punktow) as srednia_punktow,
                o.obszar,
                'cała umiejętność' umiejetnosc,
                'szkola' klasa
            from wyniki_egzaminu as we
            left join uczniowie as u
                on u.kod_ucznia = we.kod_ucznia AND we.id_analiza = u.id_analiza
            left join obszary as o
                on o.nr_zadania = we.nr_zadania AND we.id_analiza = u.id_analiza
            WHERE we.id_analiza = ?
            GROUP by o.obszar
            UNION
            select sum(we.liczba_punktow)/sum(we.max_punktow) as srednia_punktow,
                o.obszar,
                'cała umiejętność' umiejetnosc,
                we.klasa
            from wyniki_egzaminu as we
            left join uczniowie as u
                ON u.kod_ucznia = we.kod_ucznia AND we.id_analiza = u.id_analiza
            left join obszary as o
                on o.nr_zadania = we.nr_zadania AND we.id_analiza = u.id_analiza
            WHERE we.id_analiza = ?
            GROUP by o.obszar, we.klasa
    ";

    const SREDNIA_OBSZAR_UMIEJETNOSC_DYSLEKSJA = "
            select sum(we.liczba_punktow)/sum(we.max_punktow) as srednia_punktow,
                o.obszar,
                u.dysleksja,
                'cała umiejętność' umiejetnosc,
                'szkola' klasa
            from wyniki_egzaminu as we
            left join uczniowie as u
                on u.kod_ucznia = we.kod_ucznia AND we.id_analiza = u.id_analiza
            left join obszary as o
                on o.nr_zadania = we.nr_zadania AND we.id_analiza = o.id_analiza
            WHERE we.id_analiza = ?
             GROUP by o.obszar, u.dysleksja
            UNION
            select sum(we.liczba_punktow)/sum(we.max_punktow) as srednia_punktow,
                o.obszar,
                u.dysleksja,
                'cała umiejętność' umiejetnosc,
                we.klasa
            from wyniki_egzaminu as we
            left join uczniowie as u
                on u.kod_ucznia = we.kod_ucznia AND we.id_analiza = u.id_analiza
            left join obszary as o
                on o.nr_zadania = we.nr_zadania AND we.id_analiza = o.id_analiza
            WHERE we.id_analiza = ?
             GROUP by o.obszar, we.klasa, u.dysleksja
            UNION
            select sum(we.liczba_punktow)/sum(we.max_punktow) as srednia_punktow,
                o.obszar,
                u.dysleksja,
                o.umiejetnosc,
                'szkola' klasa
            from wyniki_egzaminu as we
            left join uczniowie as u
                on u.kod_ucznia = we.kod_ucznia AND we.id_analiza = u.id_analiza
            left join obszary as o
                on o.nr_zadania = we.nr_zadania AND we.id_analiza = o.id_analiza
            WHERE we.id_analiza = ?
             GROUP by o.obszar, o.umiejetnosc, u.dysleksja
            UNION
            select sum(we.liczba_punktow)/sum(we.max_punktow) as srednia_punktow,
                o.obszar,
                u.dysleksja,
                o.umiejetnosc,
                we.klasa
            from wyniki_egzaminu as we
            left join uczniowie as u
                on u.kod_ucznia = we.kod_ucznia AND we.id_analiza = u.id_analiza
            left join obszary as o
                on o.nr_zadania = we.nr_zadania AND we.id_analiza = o.id_analiza
            WHERE we.id_analiza = ?
             GROUP by o.obszar, we.klasa, o.umiejetnosc, u.dysleksja
    ";

    const SREDNIA_OBSZAR_UMIEJETNOSC_LOKALIZACJA = "
            select sum(we.liczba_punktow)/sum(we.max_punktow) as srednia_punktow,
                o.obszar,
                u.lokalizacja,
                'cała umiejętność' umiejetnosc,
                'szkola' klasa
            from wyniki_egzaminu as we
            left join uczniowie as u
                on u.kod_ucznia = we.kod_ucznia AND we.id_analiza = u.id_analiza
            left join obszary as o
                on o.nr_zadania = we.nr_zadania AND we.id_analiza = o.id_analiza
            WHERE we.id_analiza = ?
             GROUP by o.obszar, u.dysleksja
            UNION
            select sum(we.liczba_punktow)/sum(we.max_punktow) as srednia_punktow,
                o.obszar,
                u.lokalizacja,
                'cała umiejętność' umiejetnosc,
                we.klasa
            from wyniki_egzaminu as we
            left join uczniowie as u
                on u.kod_ucznia = we.kod_ucznia AND we.id_analiza = u.id_analiza
            left join obszary as o
                on o.nr_zadania = we.nr_zadania AND we.id_analiza = o.id_analiza
            WHERE we.id_analiza = ?
             GROUP by o.obszar, we.klasa, u.dysleksja
            UNION
            select sum(we.liczba_punktow)/sum(we.max_punktow) as srednia_punktow,
                o.obszar,
                u.lokalizacja,
                o.umiejetnosc,
                'szkola' klasa
            from wyniki_egzaminu as we
            left join uczniowie as u
                on u.kod_ucznia = we.kod_ucznia AND we.id_analiza = u.id_analiza
            left join obszary as o
                on o.nr_zadania = we.nr_zadania AND we.id_analiza = o.id_analiza
            WHERE we.id_analiza = ?
             GROUP by o.obszar, o.umiejetnosc, u.lokalizacja
            UNION
            select sum(we.liczba_punktow)/sum(we.max_punktow) as srednia_punktow,
                o.obszar,
                u.lokalizacja,
                o.umiejetnosc,
                we.klasa
            from wyniki_egzaminu as we
            left join uczniowie as u
                on u.kod_ucznia = we.kod_ucznia AND we.id_analiza = u.id_analiza
            left join obszary as o
                on o.nr_zadania = we.nr_zadania AND we.id_analiza = o.id_analiza
            WHERE we.id_analiza = ?
             GROUP by o.obszar, we.klasa, o.umiejetnosc, u.lokalizacja
    ";

    const SREDNIA_OBSZAR_UMIEJETNOSC_PLEC = "
            select sum(we.liczba_punktow)/sum(we.max_punktow) as srednia_punktow,
                o.obszar,
                u.plec,
                'cała umiejętność' umiejetnosc,
                'szkola' klasa
            from wyniki_egzaminu as we
            left join uczniowie as u
                on u.kod_ucznia = we.kod_ucznia AND we.id_analiza = u.id_analiza
            left join obszary as o
                on o.nr_zadania = we.nr_zadania AND we.id_analiza = o.id_analiza
            WHERE we.id_analiza = ?
             GROUP by o.obszar, u.dysleksja
            UNION
            select sum(we.liczba_punktow)/sum(we.max_punktow) as srednia_punktow,
                o.obszar,
                u.plec,
                'cała umiejętność' umiejetnosc,
                we.klasa
            from wyniki_egzaminu as we
            left join uczniowie as u
                on u.kod_ucznia = we.kod_ucznia AND we.id_analiza = u.id_analiza
            left join obszary as o
                on o.nr_zadania = we.nr_zadania AND we.id_analiza = o.id_analiza
            WHERE we.id_analiza = ?
             GROUP by o.obszar, we.klasa, u.dysleksja
            UNION
            select sum(we.liczba_punktow)/sum(we.max_punktow) as srednia_punktow,
                o.obszar,
                u.plec,
                o.umiejetnosc,
                'szkola' klasa
            from wyniki_egzaminu as we
            left join uczniowie as u
                on u.kod_ucznia = we.kod_ucznia AND we.id_analiza = u.id_analiza
            left join obszary as o
                on o.nr_zadania = we.nr_zadania AND we.id_analiza = o.id_analiza
            WHERE we.id_analiza = ?
             GROUP by o.obszar, o.umiejetnosc, u.plec
            UNION
            select sum(we.liczba_punktow)/sum(we.max_punktow) as srednia_punktow,
                o.obszar,
                u.plec,
                o.umiejetnosc,
                we.klasa
            from wyniki_egzaminu as we
            left join uczniowie as u
                on u.kod_ucznia = we.kod_ucznia AND we.id_analiza = u.id_analiza
            left join obszary as o
                on o.nr_zadania = we.nr_zadania AND we.id_analiza = o.id_analiza
            WHERE we.id_analiza = ?
             GROUP by o.obszar, we.klasa, o.umiejetnosc, u.plec
    ";



    const UNION_ALL_SREDNIA_OBSZAR_UMIEJETNOSC = "
            select sum(we.liczba_punktow)/sum(we.max_punktow) as srednia_punktow,
                o.obszar,
                null umiejetnosc,
                null dysleksja,
                null lokalizacja,
                null plec,
                'szkola' klasa
            from wyniki_egzaminu as we
            left join uczniowie as u
                on u.kod_ucznia = we.kod_ucznia
            left join obszary as o
                on o.nr_zadania = we.nr_zadania
             GROUP by o.obszar
            UNION
            select sum(we.liczba_punktow)/sum(we.max_punktow) as srednia_punktow,
                o.obszar,
                null umiejetnosc,
                null dysleksja,
                null lokalizacja,
                null plec,
                we.klasa
            from wyniki_egzaminu as we
            left join uczniowie as u
                on u.kod_ucznia = we.kod_ucznia
            left join obszary as o
                on o.nr_zadania = we.nr_zadania
             GROUP by o.obszar, we.klasa
            UNION
            select sum(we.liczba_punktow)/sum(we.max_punktow) as srednia_punktow,
                o.obszar,
                null umiejetnosc,
                u.dysleksja,
                null lokalizacja,
                null plec,
                'szkola' klasa
            from wyniki_egzaminu as we
            left join uczniowie as u
                on u.kod_ucznia = we.kod_ucznia
            left join obszary as o
                on o.nr_zadania = we.nr_zadania
             GROUP by o.obszar, u.dysleksja
            UNION
            select sum(we.liczba_punktow)/sum(we.max_punktow) as srednia_punktow,
                o.obszar,
                null umiejetnosc,
                null dysleksja,
                u.lokalizacja,
                null plec,
                'szkola' klasa
            from wyniki_egzaminu as we
            left join uczniowie as u
                on u.kod_ucznia = we.kod_ucznia
            left join obszary as o
                on o.nr_zadania = we.nr_zadania
             GROUP by o.obszar, u.lokalizacja
            UNION
            select sum(we.liczba_punktow)/sum(we.max_punktow) as srednia_punktow,
                o.obszar,
                null umiejetnosc,
                null dysleksja,
                null lokalizacja,
                u.plec,
                'szkola' klasa
            from wyniki_egzaminu as we
            left join uczniowie as u
                on u.kod_ucznia = we.kod_ucznia
            left join obszary as o
                on o.nr_zadania = we.nr_zadania
             GROUP by o.obszar, u.plec
            UNION
            select sum(we.liczba_punktow)/sum(we.max_punktow) as srednia_punktow,
                o.obszar,
                null umiejetnosc,
                u.dysleksja,
                null lokalizacja,
                null plec,
                we.klasa
            from wyniki_egzaminu as we
            left join uczniowie as u
                on u.kod_ucznia = we.kod_ucznia
            left join obszary as o
                on o.nr_zadania = we.nr_zadania
             GROUP by o.obszar, we.klasa, u.dysleksja
            UNION
            select sum(we.liczba_punktow)/sum(we.max_punktow) as srednia_punktow,
                o.obszar,
                null umiejetnosc,
                null dysleksja,
                u.lokalizacja,
                null plec,
                we.klasa
            from wyniki_egzaminu as we
            left join uczniowie as u
                on u.kod_ucznia = we.kod_ucznia
            left join obszary as o
                on o.nr_zadania = we.nr_zadania
             GROUP by o.obszar, we.klasa, u.lokalizacja
            UNION
            select sum(we.liczba_punktow)/sum(we.max_punktow) as srednia_punktow,
                o.obszar,
                null umiejetnosc,
                null dysleksja,
                null lokalizacja,
                u.plec,
                we.klasa
            from wyniki_egzaminu as we
            left join uczniowie as u
                on u.kod_ucznia = we.kod_ucznia
            left join obszary as o
                on o.nr_zadania = we.nr_zadania
             GROUP by o.obszar, we.klasa, u.plec
            UNION
            select sum(we.liczba_punktow)/sum(we.max_punktow) as srednia_punktow,
                o.obszar,
                o.umiejetnosc,
                null dysleksja,
                null lokalizacja,
                null plec,
                'szkola' klasa
            from wyniki_egzaminu as we
            left join uczniowie as u
                on u.kod_ucznia = we.kod_ucznia
            left join obszary as o
                on o.nr_zadania = we.nr_zadania
             GROUP by o.obszar, o.umiejetnosc
            UNION
            select sum(we.liczba_punktow)/sum(we.max_punktow) as srednia_punktow,
                o.obszar,
                o.umiejetnosc,
                null dysleksja,
                null lokalizacja,
                null plec,
                we.klasa
            from wyniki_egzaminu as we
            left join uczniowie as u
                on u.kod_ucznia = we.kod_ucznia
            left join obszary as o
                on o.nr_zadania = we.nr_zadania
             GROUP by o.obszar, o.umiejetnosc, we.klasa
            UNION
            select sum(we.liczba_punktow)/sum(we.max_punktow) as srednia_punktow,
                o.obszar,
                o.umiejetnosc,
                u.dysleksja,
                null lokalizacja,
                null plec,
                'szkola' klasa
            from wyniki_egzaminu as we
            left join uczniowie as u
                on u.kod_ucznia = we.kod_ucznia
            left join obszary as o
                on o.nr_zadania = we.nr_zadania
             GROUP by o.obszar, o.umiejetnosc, u.dysleksja
            UNION
            select sum(we.liczba_punktow)/sum(we.max_punktow) as srednia_punktow,
                o.obszar,
                o.umiejetnosc,
                null dysleksja,
                u.lokalizacja,
                null plec,
                'szkola' klasa
            from wyniki_egzaminu as we
            left join uczniowie as u
                on u.kod_ucznia = we.kod_ucznia
            left join obszary as o
                on o.nr_zadania = we.nr_zadania
             GROUP by o.obszar, o.umiejetnosc, u.lokalizacja
            UNION
            select sum(we.liczba_punktow)/sum(we.max_punktow) as srednia_punktow,
                o.obszar,
                o.umiejetnosc,
                null dysleksja,
                null lokalizacja,
                u.plec,
                'szkola' klasa
            from wyniki_egzaminu as we
            left join uczniowie as u
                on u.kod_ucznia = we.kod_ucznia
            left join obszary as o
                on o.nr_zadania = we.nr_zadania
             GROUP by o.obszar, o.umiejetnosc, u.plec
            UNION
            select sum(we.liczba_punktow)/sum(we.max_punktow) as srednia_punktow,
                o.obszar,
                o.umiejetnosc,
                u.dysleksja,
                null lokalizacja,
                null plec,
                we.klasa
            from wyniki_egzaminu as we
            left join uczniowie as u
                on u.kod_ucznia = we.kod_ucznia
            left join obszary as o
                on o.nr_zadania = we.nr_zadania
             GROUP by o.obszar, o.umiejetnosc, we.klasa, u.dysleksja
            UNION
            select sum(we.liczba_punktow)/sum(we.max_punktow) as srednia_punktow,
                o.obszar,
                o.umiejetnosc,
                null dysleksja,
                u.lokalizacja,
                null plec,
                we.klasa
            from wyniki_egzaminu as we
            left join uczniowie as u
                on u.kod_ucznia = we.kod_ucznia
            left join obszary as o
                on o.nr_zadania = we.nr_zadania
             GROUP by o.obszar, o.umiejetnosc, we.klasa, u.lokalizacja
            UNION
            select sum(we.liczba_punktow)/sum(we.max_punktow) as srednia_punktow,
                o.obszar,
                o.umiejetnosc,
                null dysleksja,
                null lokalizacja,
                u.plec,
                we.klasa
            from wyniki_egzaminu as we
            left join uczniowie as u
                on u.kod_ucznia = we.kod_ucznia
            left join obszary as o
                on o.nr_zadania = we.nr_zadania
             GROUP by o.obszar, o.umiejetnosc, we.klasa, u.plec
            ";

    const CZESTOSC_WYNIKOW_CALOSC = "
        select count(1) as ilosc_wynikow,
            b.suma,
            'szkola' klasa
        from (
            select sum(w.liczba_punktow) as suma, 
                w.kod_ucznia
              from wyniki_egzaminu w
              WHERE w.id_analiza = ?
              group by w.kod_ucznia) as b
        group by b.suma
        UNION
        select count(1) as ilosc_wynikow,
            b.suma,
            b.klasa
        from (
            select sum(w.liczba_punktow) as suma,
                w.kod_ucznia,
                u.klasa
                from wyniki_egzaminu w
                left join uczniowie u
                    on w.kod_ucznia = u.kod_ucznia
                WHERE w.id_analiza = ?
                group by w.kod_ucznia) as b
        group by b.suma, b.klasa
        ORDER BY klasa, suma
    ";

    const CZESTOSC_WYNIKOW_DYSLEKSJA = "
            select count(1) as ilosc_wynikow,
                b.suma,
                b.dysleksja,
                'szkola' klasa
            from (
                select sum(w.liczba_punktow) as suma,
                    u.dysleksja
                    from wyniki_egzaminu w
                    left join uczniowie u
                        on w.kod_ucznia = u.kod_ucznia
                    WHERE w.id_analiza = ?
                    group by w.kod_ucznia
            ) as b
            group by b.suma, b.dysleksja
            UNION
            select count(1) as ilosc_wynikow,
                b.suma,
                b.dysleksja,
                b.klasa
            from (
                  select sum(w.liczba_punktow) as suma,
                    u.dysleksja,
                    u.klasa
                    from wyniki_egzaminu w
                    left join uczniowie u
                      on w.kod_ucznia = u.kod_ucznia
                    WHERE w.id_analiza = ?
                    group by w.kod_ucznia, u.klasa
                    ) as b
            group by b.suma, b.dysleksja, b.klasa
            ORDER BY suma, dysleksja, klasa
    ";

    const CZESTOSC_WYNIKOW_LOKALIZACJA = "
            select count(1) as ilosc_wynikow,
                b.suma,
                b.lokalizacja,
                'szkola' klasa
            from (
                select sum(w.liczba_punktow) as suma,
                    u.lokalizacja
                    from wyniki_egzaminu w
                    left join uczniowie u
                        on w.kod_ucznia = u.kod_ucznia
                    WHERE w.id_analiza = ?
                    group by w.kod_ucznia
            ) as b
            group by b.suma, b.lokalizacja
            UNION
            select count(1) as ilosc_wynikow,
                b.suma,
                b.lokalizacja,
                b.klasa
            from (
                  select sum(w.liczba_punktow) as suma,
                    u.lokalizacja,
                    u.klasa
                    from wyniki_egzaminu w
                    left join uczniowie u
                      on w.kod_ucznia = u.kod_ucznia
                    WHERE w.id_analiza = ?
                    group by w.kod_ucznia, u.klasa
                    ) as b
            group by b.suma, b.lokalizacja, b.klasa
            ORDER BY klasa, suma, lokalizacja
    ";

    const CZESTOSC_WYNIKOW_PLEC = "
         select count(1) as ilosc_wynikow,
                b.suma,
                b.plec,
                'szkola' klasa
            from (
                select sum(w.liczba_punktow) as suma,
                    u.plec
                    from wyniki_egzaminu w
                    left join uczniowie u
                        on w.kod_ucznia = u.kod_ucznia
                    WHERE w.id_analiza = ?
                    group by w.kod_ucznia
            ) as b
            group by b.suma, b.plec
            UNION
            select count(1) as ilosc_wynikow,
                b.suma,
                b.plec,
                b.klasa
            from (
                  select sum(w.liczba_punktow) as suma,
                    u.plec,
                    u.klasa
                    from wyniki_egzaminu w
                    left join uczniowie u
                      on w.kod_ucznia = u.kod_ucznia
                    WHERE w.id_analiza = ?
                    group by w.kod_ucznia, u.klasa
                    ) as b
            group by b.suma, b.klasa, b.plec
            ORDER BY ilosc_wynikow, klasa, plec
    ";

    const UNION_CZESTOSC_WYNIKOW = "
            select count(1) as ilosc_wynikow,
                b.suma,
                null dysleksja,
                null lokalizacja,
                null plec,
                'szkola' klasa
            from (
                select sum(w.liczba_punktow) as suma, w.kod_ucznia
                    from wyniki_egzaminu w
                    group by w.kod_ucznia) as b
            group by b.suma
            UNION
            select count(1) as ilosc_wynikow,
                b.suma,
                null dysleksja,
                null lokalizacja,
                null plec,
                b.klasa
            from (
                select sum(w.liczba_punktow) as suma,
                    w.kod_ucznia,
                    u.klasa
                    from wyniki_egzaminu w
                    left join uczniowie u
                        on w.kod_ucznia = u.kod_ucznia
                    group by w.kod_ucznia) as b
            group by b.suma, b.klasa
            UNION
            select count(1) as ilosc_wynikow,
                b.suma,
                b.dysleksja,
                null lokalizacja,
                null plec,
                'szkola' klasa
            from (
                select sum(w.liczba_punktow) as suma,
                    u.dysleksja
                    from wyniki_egzaminu w
                    left join uczniowie u
                        on w.kod_ucznia = u.kod_ucznia
                    group by w.kod_ucznia, u.dysleksja) as b
            group by b.suma, b.dysleksja
            UNION
            select count(1) as ilosc_wynikow,
                b.suma,
                null dysleksja,
                b.lokalizacja,
                null plec,
                'szkola' klasa
            from (
                select sum(w.liczba_punktow) as suma,
                    u.lokalizacja
                    from wyniki_egzaminu w
                    left join uczniowie u
                        on w.kod_ucznia = u.kod_ucznia
                    group by w.kod_ucznia, u.lokalizacja) as b
            group by b.suma, b.lokalizacja
            UNION
            select count(1) as ilosc_wynikow,
                b.suma,
                null dysleksja,
                null lokalizacja,
                b.plec,
                'szkola' klasa
            from (
                select sum(w.liczba_punktow) as suma,
                    u.plec
                    from wyniki_egzaminu w
                    left join uczniowie u
                        on w.kod_ucznia = u.kod_ucznia
                    group by w.kod_ucznia, u.plec) as b
            group by b.suma,b.plec
            UNION
            select count(1) as ilosc_wynikow,
                b.suma,
                b.dysleksja,
                null lokalizacja,
                null plec,
                b.klasa
            from (
                select sum(w.liczba_punktow) as suma,
                    u.dysleksja,
                    u.klasa
                    from wyniki_egzaminu w
                    left join uczniowie u
                        on w.kod_ucznia = u.kod_ucznia
                    group by w.kod_ucznia, u.dysleksja) as b
            group by b.suma, b.dysleksja, b.klasa
            UNION
            select count(1) as ilosc_wynikow,
                b.suma,
                null dysleksja,
                b.lokalizacja,
                null plec,
                b.klasa
            from (
                select sum(w.liczba_punktow) as suma,
                    u.lokalizacja,
                    u.klasa
                    from wyniki_egzaminu w
                    left join uczniowie u
                        on w.kod_ucznia = u.kod_ucznia
                    group by w.kod_ucznia, u.lokalizacja) as b
            group by b.suma, b.lokalizacja, b.klasa
            UNION
            select count(1) as ilosc_wynikow,
                b.suma,
                null dysleksja,
                null lokalizacja,
                b.plec,
                b.klasa
            from (
                select sum(w.liczba_punktow) as suma,
                    u.plec,
                    u.klasa
                    from wyniki_egzaminu w
                    left join uczniowie u
                        on w.kod_ucznia = u.kod_ucznia
                    group by w.kod_ucznia, u.plec) as b
            group by b.suma, b.plec, b.klasa
            ";

    const SREDNIA_PUNKTOW_GRUPY_CALOSC = "
        select sum(we.liczba_punktow)/count(distinct we.kod_ucznia) as srednia_punktow,
            'szkola' klasa
        from wyniki_egzaminu as we
        WHERE we.id_analiza = ?
        UNION
        select sum(we.liczba_punktow)/count(distinct we.kod_ucznia) as srednia_punktow,
            u.klasa
        from wyniki_egzaminu as we
        left join uczniowie as u
            on u.kod_ucznia = we.kod_ucznia
        WHERE we.id_analiza = ?
        group by u.klasa
        ORDER BY klasa
    ";

    const SREDNIA_PUNKTOW_GRUPY_DYSLEKSJA = "
        select sum(we.liczba_punktow)/count(distinct we.kod_ucznia) as srednia_punktow,
            u.klasa,
            u.dysleksja
        from wyniki_egzaminu as we
        left join uczniowie as u
            on u.kod_ucznia = we.kod_ucznia
        WHERE we.id_analiza = ?
        group by u.klasa, u.dysleksja
        UNION
        select sum(we.liczba_punktow)/count(distinct we.kod_ucznia) as srednia_punktow,
            'szkola' klasa,
            u.dysleksja
        from wyniki_egzaminu as we
        left join uczniowie as u
            on u.kod_ucznia = we.kod_ucznia
        WHERE we.id_analiza = ?
        group by u.dysleksja
        ORDER BY klasa, srednia_punktow
    ";


    const SREDNIA_PUNKTOW_GRUPY_LOKALIZACJA = "
        select sum(we.liczba_punktow)/count(distinct we.kod_ucznia) as srednia_punktow,
            u.klasa,
            u.lokalizacja
        from wyniki_egzaminu as we
        left join uczniowie as u
            on u.kod_ucznia = we.kod_ucznia
        WHERE we.id_analiza = ?
        group by u.klasa, u.lokalizacja
        UNION
        select sum(we.liczba_punktow)/count(distinct we.kod_ucznia) as srednia_punktow,
            'szkola' klasa,
            u.lokalizacja
        from wyniki_egzaminu as we
        left join uczniowie as u
            on u.kod_ucznia = we.kod_ucznia
        WHERE we.id_analiza = ?
        group by u.lokalizacja
        ORDER BY klasa, srednia_punktow
    ";

    const SREDNIA_PUNKTOW_GRUPY_PLEC = "
        select sum(we.liczba_punktow)/count(distinct we.kod_ucznia) as srednia_punktow,
            u.klasa,
            u.plec
        from wyniki_egzaminu as we
        left join uczniowie as u
            on u.kod_ucznia = we.kod_ucznia
        WHERE we.id_analiza = ?
        group by u.klasa, u.plec
        UNION
        select sum(we.liczba_punktow)/count(distinct we.kod_ucznia) as srednia_punktow,
            'szkola' klasa,
            u.plec
        from wyniki_egzaminu as we
        left join uczniowie as u
            on u.kod_ucznia = we.kod_ucznia
        WHERE we.id_analiza = ?
        group by u.plec
        ORDER BY klasa, srednia_punktow
    ";

    const UNION_SREDNIA_PUNKTOW_GRUPY = "
    		select sum(we.liczba_punktow)/count(distinct we.kod_ucznia) as srednia_punktow,
                u.klasa,
    			null dysleksja,
    			null plec,
    			null lokalizacja
            from wyniki_egzaminu as we
            left join uczniowie as u
                on u.kod_ucznia = we.kod_ucznia
            group by u.klasa
            UNION
            select sum(we.liczba_punktow)/count(distinct we.kod_ucznia) as srednia_punktow,
	            'szkola' klasa,
	    		null dysleksja,
    			null plec,
    			null lokalizacja
            from wyniki_egzaminu as we
    		UNION
    		select sum(we.liczba_punktow)/count(distinct we.kod_ucznia) as srednia_punktow,
                u.klasa,
                u.dysleksja,
    			null plec,
    			null lokalizacja
            from wyniki_egzaminu as we
            left join uczniowie as u
                on u.kod_ucznia = we.kod_ucznia
            group by u.klasa, u.dysleksja
            UNION
            select sum(we.liczba_punktow)/count(distinct we.kod_ucznia) as srednia_punktow,
	            'szkola' klasa,
	            u.dysleksja,
	    		null plec,
	    		null lokalizacja
            from wyniki_egzaminu as we
            left join uczniowie as u
                on u.kod_ucznia = we.kod_ucznia
            group by u.dysleksja
    		UNION
            select sum(we.liczba_punktow)/count(distinct we.kod_ucznia) as srednia_punktow,
                u.klasa,
    			null dysleksja,
    			null plec,
                u.lokalizacja
            from wyniki_egzaminu as we
            left join uczniowie as u
                on u.kod_ucznia = we.kod_ucznia
            group by u.klasa, u.lokalizacja
            UNION
            select sum(we.liczba_punktow)/count(distinct we.kod_ucznia) as srednia_punktow,
	            'szkola' klasa,
	    		null dysleksja,
	    		null plec,
	            u.lokalizacja
            from wyniki_egzaminu as we
            left join uczniowie as u
                on u.kod_ucznia = we.kod_ucznia
            group by u.lokalizacja
    		UNION
    		select sum(we.liczba_punktow)/count(distinct we.kod_ucznia) as srednia_punktow,
                u.klasa,
    			null dysleksja,
                u.plec,
    			null lokalizacja
            from wyniki_egzaminu as we
            left join uczniowie as u
                on u.kod_ucznia = we.kod_ucznia
            group by u.klasa, u.plec
            UNION
            select sum(we.liczba_punktow)/count(distinct we.kod_ucznia) as srednia_punktow,
	            'szkola' klasa,
	    		null dysleksja,
	            u.plec,
	    		null lokalizacja
            from wyniki_egzaminu as we
            left join uczniowie as u
                on u.kod_ucznia = we.kod_ucznia
            group by u.plec
            ";
}