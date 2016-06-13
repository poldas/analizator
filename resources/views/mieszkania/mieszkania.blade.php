@extends('layouts.app')

@section('htmlheader_title')
	Panel Mieszkań
@endsection

@section('contentheader_title')
	Panel mieszkań
@endsection
@section('main-content')
	<div class="container spark-screen">
		<div class="row">
			<div class="col-md-10 col-md-offset-1">
				<div class="panel panel-default">
					<div class="panel-heading">Mieszkania</div>

					<div class="panel-body">
						{!! Form::open(['route' => 'analiza.store', 'method' => 'post', 'name' => 'roiroe']) !!}
						<div class="form-group col-2">
							{!! Form::label('kapital', "Kapitał", ['class' => 'control-label col-2']) !!}
							{!! Form::text('kapital', null, ['class'=>'form-control']) !!}
						</div>
						<div class="form-group col-2">
							{!! Form::label('cashflow', "Roczny cashflow", ['class' => 'control-label col-2']) !!}
							{!! Form::text('cashflow', null, ['class'=>'form-control']) !!}
						</div>
						<div class="form-group col-2">
							{!! Form::label('roi', "ROI", ['class' => 'control-label col-2']) !!}
							{!! Form::text('roi', null, ['class'=>'form-control']) !!}
						</div>
						<div class="form-group">
							{!! Form::submit('Przelicz', ['class'=>'btn btn-primary']) !!}
						</div>
						{{--                        {!! Form::file($name, $attributes = array()) !!}--}}
						{!! Form::close() !!}
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
@section('scripts')
	@parent
	<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
	<script type="text/javascript">
		$('form[name="roiroe"]').submit(function(e, data) {
			e.preventDefault();
			console.log(e);
		});
	</script>
@endsection