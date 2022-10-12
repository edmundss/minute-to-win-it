@extends('layouts.main')
@section('content')
<div class="row">
	<div class="col-lg-4">
		<div class="box box-solid">
			<div class="box-header with-border">
				<h1 class="box-title">Forma</h1>
			</div>
			{{Form::open(['url' => route('admin.permissions.store'), 'method' => 'POST'])}}
			<div class="box-body">
					<div class="form-group">
						{{Form::label('name', 'Sistēmas nosaukums (viens-vārds)')}}
						{{Form::text('name', null, ['class' => 'form-control'])}}
					</div>
					<div class="form-group">
						{{Form::label('display_name', 'Ekrāna nosaukums')}}
						{{Form::text('display_name', null, ['class' => 'form-control'])}}
					</div>
					<div class="form-group">
						{{Form::label('description', 'Apraksts')}}
						{{Form::textarea('description', null, ['class' => 'form-control'])}}
					</div>
			</div>
			<div class="box-footer">
				{{Form::submit('Pievienot', ['class' => 'btn btn-success'])}}
			</div>
			{{Form::close()}}
		</div>
	</div>
</div>
@stop

