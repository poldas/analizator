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
                    <div class="flash-message">
                        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                            @if(Session::has('alert-' . $msg))
                                <p class="alert alert-{{ $msg }}">
                                    {{ Session::pull('alert-' . $msg) }}
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                </p>
                            @endif
                        @endforeach
                    </div> <!-- end .flash-message -->
                    <div class="panel-body">
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
                                            <a href="{{ route('analiza.delete', ['id' =>  $analiza->id]) }}">usuń</a>
                                            <a href="{{ route('analiza.show', ['id' =>  $analiza->id]) }}">pokaż</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection
