@extends('layouts.main')

@section('content')
<div class="row">
	<div class="col-lg-4">
		<div class="box box-solid box-info">
			<div class="box-header">
				<h1 class="box-title">Par izaicinājumu</h1>
			</div>
			{{Form::open(['url' => route('challenge.store'), 'method' => 'POST'])}}
			<div class="box-body">
				<div class="form-group">
					{{Form::label('title', 'Nosaukums')}}
					{{Form::text('title', null, ['class'=>'form-control'])}}
				</div>
				<div class="form-group">
					{{Form::label('video_link', 'YouTube adrese')}}
					{{Form::text('video_link', null, ['class'=>'form-control'])}}
				</div>
			</div>
			<div class="box-footer">
				{{Form::submit('Pievienot', ['class' => 'btn btn-info pull-right'])}}
			</div>
			{{Form::close()}}
		</div>
	</div>
</div>
@stop