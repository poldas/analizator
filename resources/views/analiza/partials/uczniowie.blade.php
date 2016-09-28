<div id="tabela-uczniowie" class="table-responsive">
    <table id="dattab" class="table table-hover table-striped tablesorter">
        <thead>
        <tr>
            <th class="header">L.p.</th>
            <th class="header headerSortUp">Nr ucznia</th>
            <th class="header">Kod ucznia</th>
            <th class="header">Klasa</th>
            <th class="header">Płeć</th>
            <th class="header">Dysleksja</th>
            <th class="header">Lokalizacja</th>
        </tr>
        </thead>
        <tbody>
        @foreach($uczniowie as $uczen)
            <tr>
                <td>{{ $loop->iteration }}</td>
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