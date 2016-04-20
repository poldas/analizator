<h3>Wyniki
    <a href="{{ route('analiza.wyniki.delete', ['id_analiza' =>  $analiza->id]) }}">usuń wyniki</a> |
    <a href="#tabela-wyniki" data-toggle="collapse">+/-</a>
</h3>
<div id="tabela-wyniki" class="table-responsive collapse">
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