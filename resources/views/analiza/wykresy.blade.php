@extends('layouts.app')

@section('htmlheader_title')
@endsection

@section('contentheader_title')
    Wykresy
@endsection

@section('layouts.partials.contentheader')
    @include('analiza.partials.singleanalizamenu', ['menu' => [], 'analiza' => $id_analiza])
@endsection

@section('main-content')
    <div class="container spark-screen">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Powrót do <a href="{{ route('analiza.show', ['id' => $id_analiza]) }}">Panel sterowania</a></div>
                    <div class="panel-body">
                        <div id="wykres" class="wykres-area"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @parent
    <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script type="text/javascript">
        var $ = jQuery;
        function initCharts(chartItem, elem) {
            var chart = $(chartItem).highcharts({
                chart: {
                    type: 'column'
                },
                title: {
                    text: elem.name
                },
                subtitle: {
                    text: 'Klasy pierwsze 2016'
                },
                series: elem.series,
                xAxis: {
                    categories: elem.categories,
                    crosshair: true
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Średnia'
                    }
                },
                legend: {
                    enabled: true
                },
                tooltip: {
                    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y:.2f}</b></td></tr>',
                    footerFormat: '</table>',
                    shared: true,
                    useHTML: true
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                }
            });
        }
        $(function() {
            var id_analiza = location.pathname.split('/').reverse()[0];
            var wykresArea = $('#wykres');
            $.ajax({
                'url': '/analiza/parsuj/'+id_analiza,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: "POST",
            }).done(function(dane) {
                console.log(dane);
                dane.forEach(function(elem) {
                    var newElement = document.createElement('div');
                    newElement.style.className = 'wykres';
                    initCharts(newElement, elem);
                    wykresArea.append(newElement);
                })
            });
        });
    </script>
@endsection