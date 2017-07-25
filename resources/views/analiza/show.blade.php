@extends('layouts.app')

@section('htmlheader_title')
    Zarządzaj
@endsection

@section('contentheader_title')

@endsection
{{--@section('layouts_partials_contentheader')--}}
    {{--@include('analiza.partials.singleanalizamenu', ['menu' => [], 'analiza' => $analiza])--}}
{{--@endsection--}}

@section('main-content')
    <div class="container spark-screen">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h1><b>{{ $analiza->nazwa }}</b></h1>
                        <em>dodano: {{ $analiza->created_at }}</em>
                    </div>
                    @include('partials.flashmessage')
                    @if( !empty($daneWykresu) )
                        <div class="panel-body">
                            <div class="alert alert-success alert-dismissable">
                                @foreach($daneWykresu as $item)
                                    {{ $item['name'] }}
                                @endforeach
                            </div>
                        </div>
                    @endif
                    <div class="panel-body">
                        <div class="panel-heading">
                            <a href="{{ route('analiza.lista') }}">powrót</a>
                            @if($analiza->obszary->isEmpty())
                                <a href="{{ route('analiza.konfiguruj', ['id' =>  $analiza->id]) }}"><span class="label label-warning">konfiguruj</span></a>
                            @else
                                <a href="{{ route('analiza.wykresy', ['id' =>  $analiza->id]) }}"><span class="label label-primary">wykresy</span></a>
                                <a href="{{ route('analiza.wykresyapi', ['id' =>  $analiza->id]) }}"><span class="label label-primary">wykresy API</span></a>
                            @endif
                            <a href="{{ route('analiza.delete', ['id' =>  $analiza->id]) }}"><span class="label label-danger">usuń analizę</span></a>
                        </div>
                        {{--<h4 class = "list-group-item-heading">--}}
                            {{--<p>{{ $analiza->nazwa }}</p>--}}
                            {{--<p>{{ $analiza->file_path }}</p>--}}
                        {{--</h4>--}}


                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#home">Obszary</a></li>
                            <li><a data-toggle="tab" href="#menu1">Wyniki</a></li>
                            <li><a data-toggle="tab" href="#menu2">Uczniowie</a></li>
                        </ul>

                        <div class="tab-content">
                            <div id="home" class="tab-pane fade in active">
                                <h3>Obszary</h3>
                                @if(!$analiza->obszary->isEmpty())
                                    @include('analiza.partials.obszary', ['obszary' => $analiza->obszary])
                                @else
                                    <h3>Nie masz skonfigurowanych danych obszarów. Kliknij w link <a href="{{ route('analiza.konfiguruj', ['id' =>  $analiza->id]) }}">konfiguruj obszary</a> aby kontynuować.</h3>
                                    @endif
                            </div>
                            <div id="menu1" class="tab-pane fade">
                                <h3>Wyniki</h3>
                                @if(!$analiza->wyniki->isEmpty())
                                    @include('analiza.partials.wyniki', ['wyniki' => $analiza->wyniki])
                                @else
                                    <h3>Nie masz skonfigurowanych danych wyników egzaminu. Kliknij w link <a href="{{ route('analiza.konfiguruj', ['id' =>  $analiza->id]) }}">konfiguruj wyniki</a> aby kontynuować.</h3>
                                @endif
                            </div>
                            <div id="menu2" class="tab-pane fade">
                                <h3>Uczniowie</h3>
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
        </div>
    </div>
@endsection
