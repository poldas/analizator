<div id="tabela-wyniki" class="table-responsive">
    <table class="table table-hover table-striped tablesorter">
        <thead>
        <tr>
            <th class="header">L.p.</th>
            <th class="header headerSortUp">Klasa</th>
            <th class="header">Kod ucznia</th>
            <th class="header">Zadanie</th>
            <th class="header">Liczba uzyskanych punktów</th>
            <th class="header">Max ilość punktów</th>
        </tr>
        </thead>
        <tbody>
        @foreach($wyniki as $wynik)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $wynik->klasa }}</td>
                <td>{{ $wynik->kod_ucznia }}</td>
                <td>{{ $wynik->nr_zadania }}</td>
                <td>{{ $wynik->liczba_punktow }}</td>
                <td>{{ $wynik->max_punktow }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>