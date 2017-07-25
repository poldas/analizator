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
                        wykresy
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<script src="http://localhost:8000/js/manifest.js"></script>
<script src="http://localhost:8000/js/vendor.js"></script>
<script src="http://localhost:8000/js/wykresy.js"></script>