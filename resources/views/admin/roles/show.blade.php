@extends('layouts.main')

@section('content')
<div class="row">
	<div class="col-lg-4">
		<div class="box box-solid">
			<div class="box-header">
				<h1 class="box-title">Par lomu</h1>
				<a href="{{route('admin.roles.edit', $role->id)}}" class="btn btn-primary btn-xs btn-flat pull-right">Labot</a>
			</div>
			<table class="table">
				<tr>
					<th>Sistēmas nosaukums</th>
					<td>{{$role->name}}</td>
				</tr>
				<tr>
					<th>Ekrāna nosaukums</th>
					<td>{{$role->display_name}}</td>
				</tr>
				<tr>
					<th>Apraksts</th>
					<td>{{$role->description}}</td>
				</tr>
			</table>
		</div>
	</div>
	<div class="col-lg-4">
		<div class="box box-solid">
			<div class="box-header">
				<h1 class="box-title">Tiesības</h1>
			</div>
			<div class="box-body">
				@foreach($permissions as $p)
					{{Form::checkbox($p->name, $p->id, $role->permissions->contains($p->id), ['class' => 'permission'])}}
					{{$p->display_name}}<br>
				@endforeach
			</div>
		</div>
	</div>
	<div class="col-lg-4">
		<div class="box box-solid">
			<div class="box-header">
				<h1 class="box-title">Darbinieki</h1>
			</div>
		</div>
	</div>
</div>
@stop

@section('scripts')
	<script type="text/javascript">
		$(function(){
			$('.permission').click(function(){
				$.get(
				'{{route('roles.update_permissions')}}',
				{
					role: {{$role->id}},
					assigned: $(this).is(':checked'),
					permission: $(this).val()
				},
				function(data){
					console.log(data);
					$.notify({
		              // options
		              message: data 
		            },{
		              // settings
		              type: 'success'
		            });
				})
				.fail(function(){
					$.notify({
		              // options
		              message: "Tiesību piešķiršana neizdevās." 
		            },{
		              // settings
		              type: 'danger'
		            });
				})
			});
		});
	</script>
@stop