@extends('layouts.app')

@section('htmlheader_title')
    Analiza testów - Dodaj nowy egzamin
@endsection

@section('contentheader_title')
    Dodaj nowy egzamin
@endsection
@section('main-content')
    <div class="container spark-screen">
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
            @if (!empty($success))
                <div class="alert alert-success">
                    <ul>
                        @foreach ($success->all() as $msg)
                            <li>{{ $msg }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        <div class="row">
            <div class="col-md-10">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <a href="{{ route('analiza.lista') }}">Powrót do listy</a>
                    </div>
                    <div class="panel-body">
                        {!! Form::open(['route' => 'analiza.store', 'method' => 'post', 'files' => true]) !!}
                        <div class="form-group">
                            {!! Form::label('nazwa', "Nazwa egzaminu", ['class' => 'control-label']) !!}
                            {!! Form::text('nazwa', null, ['class'=>'form-control']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('file', "Dodaj dane csv", ['class' => 'control-label']) !!}
                            {!! Form::file('file', ['class' => 'form-control']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::submit('Dodaj', ['class'=>'btn btn-primary']) !!}
                        </div>
{{--                        {!! Form::file($name, $attributes = array()) !!}--}}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
