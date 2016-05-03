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
                    <div class="panel-heading">Zarzadzaj analizą: <b>{{ $analiza->nazwa }}</b></div>
                    @include('partials.flashmessage')
                    <div class="panel-body">
                        <a href="{{ route('analiza.delete', ['id' =>  $analiza->id]) }}">usuń</a> |
                        <a href="{{ route('analiza.konfiguruj', ['id' =>  $analiza->id]) }}">konfiguruj</a> |
                        <a href="{{ route('analiza.parsuj', ['id' =>  $analiza->id]) }}">parsuj</a>

                        <a href = "{{ route('analiza.show', ['id' =>  $analiza->id]) }}" class = "list-group-item">
                        <h4 class = "list-group-item-heading">
                            <p>{{ $analiza->nazwa }}</p>
                            <p>{{ $analiza->file_path }}</p>
                        </h4>
                        </a>
                        @if(!$analiza->obszary->isEmpty())
                            @include('analiza.partials.obszary', ['obszary' => $analiza->obszary])
                        @else
                            <h3>Nie masz skonfigurowanych danych obszarów. Kliknij w link <a href="{{ route('analiza.konfiguruj', ['id' =>  $analiza->id]) }}">konfiguruj obszary</a> aby kontynuować.</h3>
                        @endif

                        @if(!$analiza->wyniki->isEmpty())
                            @include('analiza.partials.wyniki', ['wyniki' => $analiza->wyniki])
                        @else
                            <h3>Nie masz skonfigurowanych danych wyników egzaminu. Kliknij w link <a href="{{ route('analiza.konfiguruj', ['id' =>  $analiza->id]) }}">konfiguruj wyniki</a> aby kontynuować.</h3>
                        @endif

                        @if(!$analiza->uczniowie->isEmpty())
                            @include('analiza.partials.uczniowie', ['uczniowie' => $analiza->uczniowie])
                        @else
                            <h3>Nie masz skonfigurowanych danych uczniów. Kliknij w link <a href="{{ route('analiza.konfiguruj', ['id' =>  $analiza->id]) }}">konfiguruj uczniów</a> aby kontynuować.</h3>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
