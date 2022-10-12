@extends('layouts.main')
@section('content')
<div class="box box-solid">
	<div class="box-header">
		<a href="{{route('admin.roles.create')}}" class="btn btn-xs btn-primary btn-flat pull-right">Pievienot</a>
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
		@if(count($roles)>0)
			@foreach($roles as $p)
			<tr>
				<td><a href="{{route('admin.roles.show', $p->id)}}">{{$p->name}}</a></td>
				<td>{{$p->display_name}}</td>
				<td>{{$p->description}}</td>
			</tr>
			@endforeach
		@else
			<tr><td>No roles</td></tr>
		@endif
		</tbody>

	</table>
</div>
@stop