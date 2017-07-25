@extends('layouts.app')

@section('htmlheader_title')
    Zarządzaj
@endsection

@section('contentheader_title')
@endsection
@section('main-content')
    <div class="container spark-screen">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    @include('partials.flashmessage')
                    <div class="panel-body">
                        @if(!$analizy->isEmpty())
                        <div class="table-responsive">
                            <h3><a href="{{ route('analiza.create') }}">Dodaj nową analizę</a></h3>
                            <table id="dattab" class="table table-hover table-striped tablesorter">
                                <thead>
                                <tr>
                                    <th class="header">Nazwa</th>
                                    <th class="header">Data dodania</th>
                                    <th class="header">Data modyfikacji</th>
                                    <th class="header"></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($analizy as $analiza)
                                    <tr>
                                        <td>
                                            <a href="{{ route('analiza.show', ['id' =>  $analiza->id]) }}">
                                                {{$analiza->nazwa}}
                                            </a>
                                        </td>
                                        <td>
                                            {{$analiza->created_at}}
                                        </td>
                                        <td>
                                            {{$analiza->updated_at}}
                                        </td>
                                        <td><a href="{{ route('analiza.delete', ['id' =>  $analiza->id]) }}"><span class="label label-danger">usuń</span></a></td>
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
    </div>
@endsection
