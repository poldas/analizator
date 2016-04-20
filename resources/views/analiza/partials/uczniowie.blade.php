<h3>Uczniowie
    <a href="{{ route('analiza.uczniowie.delete', ['id_analiza' =>  $analiza->id]) }}">usuń uczniów</a> |
    <a href="#tabela-uczniowie" data-toggle="collapse">+/-</a>
</h3>
<div id="tabela-uczniowie" class="table-responsive collapse">
    <table id="dattab" class="table table-hover table-striped tablesorter">
        <thead>
        <tr>
            <th class="header headerSortUp">nr ucznia</th>
            <th class="header">kod ucznia</th>
            <th class="header">klasa</th>
            <th class="header">płeć</th>
            <th class="header">dysleksja</th>
            <th class="header">lokalizacja</th>
        </tr>
        </thead>
        <tbody>
        @foreach($uczniowie as $uczen)
            <tr>
                <td>{{ $uczen->nr_ucznia }}</td>
                <td>{{ $uczen->kod_ucznia }}</td>
                <td>{{ $uczen->klasa }}</td>
                <td>{{ $uczen->plec }}</td>
                <td>{{ $uczen->dysleksja }}</td>
                <td>{{ $uczen->lokalizacja }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>