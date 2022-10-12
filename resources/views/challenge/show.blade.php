@extends('layouts.main')

@section('content')
<div class="row">
	<div class="col-lg-8">
		<div class="bs-example" data-example-id="responsive-embed-16by9-iframe-youtube">
			<div class="embed-responsive embed-responsive-16by9"> 
				<iframe class="embed-responsive-item" src="//www.youtube.com/embed/{{$challenge->video_link}}?rel=0" allowfullscreen=""></iframe>
			</div>
		</div>
	</div>
	<div class="col-lg-4">
		<div class="box box-solid">
			<div class="box-header">
				<h1 class="box-title">
					Par izaicinājumu
				</h1>
			</div>
			<table class="table">
				<tr>
					<th>Nosaukums</th>
					<td>{{$challenge->title}}</td>
				</tr>
				<tr>
					<th>Autors</th>
					<td>{{$challenge->user->name}}</td>
				</tr>
				<tr>
					<th>Pievienots</th>
					<td>{{$challenge->created_at}}</td>
				</tr>
				<tr>
					<th>Reizes spēlēts</th>
					<td>{{$challenge->rounds()->count()}}</td>
				</tr>
			</table>
		</div>
	</div>
</div>
@stop