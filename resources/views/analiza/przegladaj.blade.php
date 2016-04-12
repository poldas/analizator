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
                    <div class="panel-heading">Praeglądaj</div>
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
                        <ul class="list-group">
                            @foreach($analizy as $analiza)
                                <li class="list-group-item">
                                    <a href = "{{ route('analiza.show', ['id' =>  $analiza->id]) }}" class = "list-group-item">
                                        <h4 class = "list-group-item-heading">
                                            <p>{{ $analiza->nazwa }}</p>
                                        </h4>
                                    </a>
                                    <a href="{{ route('analiza.delete', ['id' =>  $analiza->id]) }}">usuń</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
