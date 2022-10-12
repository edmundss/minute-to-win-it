@extends('layouts.main')
@section('content')
<div class="box box-solid">
	<div class="box-header">
		<a href="{{route('admin.permissions.create')}}" class="btn btn-xs btn-primary btn-flat pull-right">Pievienot</a>
	</div>
	<table class="table">
		<thead>
			<tr>
				<th>Īsais nosaukums</th>
				<th>Pilnais nosaukums</th>
				<th>Apraksts</th>
			</tr>
		</thead>
		<tbody>
		@if(count($permissions)>0)
			@foreach($permissions as $p)
			<tr>
				<td>{{$p->name}}</td>
				<td>{{$p->display_name}}</td>
				<td>{{$p->description}}</td>
			</tr>
			@endforeach
		@else
			<tr><td>No permissions</td></tr>
		@endif
		</tbody>

	</table>
</div>
@stop