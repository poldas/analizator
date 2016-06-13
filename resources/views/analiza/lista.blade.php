@extends('layouts.app')

@section('htmlheader_title')
    Zarządzaj
@endsection

@section('contentheader_title')
    zarządzaj
@endsection
@section('main-content')
    <div class="container spark-screen">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Przeglądaj</div>
                    @include('partials.flashmessage')
                    <div class="panel-body">
                        @if(!$analizy->isEmpty())
                        <div class="table-responsive">
                            <table id="dattab" class="table table-hover table-striped tablesorter">
                                <thead>
                                <tr>
                                    <th class="header headerSortUp">ID</th>
                                    <th class="header">Nazwa</th>
                                    <th class="header">Akcja</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($analizy as $analiza)
                                    <tr>
                                        <td>{{$analiza->id}}</td>
                                        <td>{{$analiza->nazwa}}</td>
                                        <td>
                                            <a href="{{ route('analiza.delete', ['id' =>  $analiza->id]) }}">usuń</a> |
                                            <a href="{{ route('analiza.show', ['id' =>  $analiza->id]) }}">pokaż</a> |
                                            <a href="{{ route('analiza.wykresy', ['id' =>  $analiza->id]) }}">wykresy</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                            <h2>Nie ma wyników.</h2>
                            <h3><a href="{{ route('analiza.create') }}">Dodaj nową analizę</a></h3>
                        @endif
                    </div>
                </div>
            </div>
        </div>
@endsection
