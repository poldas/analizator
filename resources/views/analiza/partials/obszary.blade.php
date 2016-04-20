<h3>Obszary
    <a href="{{ route('analiza.obszar.delete', ['id_analiza' =>  $analiza->id]) }}">usuń obszar</a> |
    <a href="#tabela-obszary" data-toggle="collapse">+/-</a>
</h3>
<div id="tabela-obszary" class="table-responsive collapse">
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