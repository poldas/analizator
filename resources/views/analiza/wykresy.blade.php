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
                    <div class="panel-body">
                        <div class="panel-heading">
                            <a href="{{ route('analiza.show', ['id' =>  $analiza->id]) }}">powrót</a>
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
                    </div>

                    <div class="panel-body" id="app">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="http://localhost:8000/js/manifest.js"></script>
    <script src="http://localhost:8000/js/vendor.js"></script>
    <script src="http://localhost:8000/js/wykresy.js"></script>
@endsection
