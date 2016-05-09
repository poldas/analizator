<div id="tabela-wyniki" class="table-responsive">
    <table class="table table-hover table-striped tablesorter">
        <thead>
        <tr>
            <th class="header headerSortUp">Klasa</th>
            <th class="header">kod ucznia</th>
            <th class="header">nr zadania</th>
            <th class="header">liczba punktów</th>
            <th class="header">max punktów</th>
        </tr>
        </thead>
        <tbody>
        @foreach($wyniki as $wynik)
            <tr>
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