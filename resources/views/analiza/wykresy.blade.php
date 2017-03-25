@extends('layouts.app')

@section('htmlheader_title')
@endsection
@section('htmlheader_links')
    <link href='https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons' rel="stylesheet" type="text/css">
    {{--<link href="https://unpkg.com/vuetify/dist/vuetify.min.css" rel="stylesheet" type="text/css">--}}
{{--    <link rel="stylesheet" href="{{asset('css/chart-app-styles.css')}}">--}}
@endsection
@section('scripts')
    @parent
    <script src="/js/vendor.js"></script>
    <script src="/js/chart-app.min.js"></script>
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
                    <div class="panel-heading"><a href="{{ route('analiza.show', ['id' => $id_analiza]) }}">Powrót</a></div>
                    <div class="panel-body">
                        <div id="wykresy">Tu się wyrenderuje aplikacja</div>
                        <div id="tabela-wyniki" class="table-responsive">
                            <table class="table table-hover table-striped tablesorter">
                                <thead>
                                <tr>
                                    <th class="header">L.p.</th>
                                    <th class="header headerSortUp">Name</th>
                                    <th class="header">Tags</th>
                                    <th class="header">Series</th>
                                    <th class="header">Opis</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($wykresy as $wynik)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $wynik->name }}</td>
                                        <td>
                                        @foreach($wynik->tags as $tag)
                                            <span>{{$tag}}</span>,
                                        @endforeach
                                        </td>
                                        <td>
                                            @foreach($wynik->series as $seria)
                                            <span>{{ $loop->iteration }}</span>
                                            @endforeach
                                        </td>
                                        <td>{{ $wynik->opis }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection