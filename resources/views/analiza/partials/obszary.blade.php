<div id="tabela-obszary" class="table-responsive">
    <table class="table table-hover table-striped tablesorter">
        <thead>
        <tr>
            <th class="header headerSortUp">Nr analizy</th>
            <th class="header">Obszar</th>
            <th class="header">Umiejętność</th>
            <th class="header">nr zadania</th>
        </tr>
        </thead>
        <tbody>
        @foreach($analiza->obszary as $obszar)
            <tr>
                <td>{{ $obszar->id_analiza }}</td>
                <td>{{ $obszar->obszar }}</td>
                <td>{{ $obszar->umiejetnosc }}</td>
                <td>{{ $obszar->nr_zadania }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>