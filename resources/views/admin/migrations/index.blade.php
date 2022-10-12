@extends('layouts.admin')

@section('content')
	<div class="">
		<div class="col-lg-4">
			<div class="box box-primary">
				<div class="box-header">
					<h1 class="box-title">Root migrations</h1>
					<a href="{{route('migrations.install')}}" class="btn btn-xs btn-danger pull-right">Run</a>
				</div>
				<div class="box-body">
					<table class="table">
						<thead>
							<tr>
								<th>Name</th>
								<th>Batch</th>
							</tr>
						</thead>
						<tbody>
							@foreach($root_migrations as $m)
							<tr @if(!$m['installed']) class="danger" @endif>
								<td>{{$m['title']}}</td>
								<td>{{$m['batch']}}</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
		@foreach($namespace_migrations as $namespace => $migrations)


		<div class="col-lg-4">
			<div class="box box-primary">
				<div class="box-header">
					<h1 class="box-title">{{$namespace}} migrations</h1>
					<a href="{{route('migrations.install')}}?namespace={{$namespace}}" class="btn btn-xs btn-danger pull-right">Run</a>
				</div>
				<div class="box-body">
					<table class="table">
						<thead>
							<tr>
								<th>Name</th>
								<th>Batch</th>
							</tr>
						</thead>
						<tbody>
							@foreach($migrations as $m)
							<tr @if(!$m['installed']) class="danger" @endif>
								<td>{{$m['title']}}</td>
								<td>{{$m['batch']}}</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>

		@endforeach
	</div>
@stop