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
    <app></app>
@endsection

@section('scripts')
    @parent
    <script src="/js/app.js"></script>
@endsection