@extends('layouts.main')

@section('content')
	<div class="row">
		<div class="col-lg-4">
			<div class="box box-solid">
				<div class="box-header with-border">
					{{Form::open(['url'=>route('roles.store')])}}
					<div class="input-group">
						{{Form::text('title', null, ['class' => 'form-control', 'placeholder' => 'Mērvienības nosaukums'])}}
						<span class="input-group-btn">
							{{Form::submit('Pievienot', ['class' => 'btn btn-flat btn-primary '])}}
						</span>
					</div>
					{{Form::close()}}
				</div>
				<div class="box-body">
					<ul>
						@foreach($roles as $r)
							<li>{{$r->title}}</li>
						@endforeach
					</ul>
				</div>
			</div>
		</div>
	</div>
@stop
				