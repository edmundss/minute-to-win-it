@extends('layouts.main')

@section('content')
<div class="row">
	<div class="col-lg-12">
		@foreach($user_array as $first_char => $users)
		<div class="row">
			<div class="col-lg-12">
				<div  style="background:#C40000; width:20px; height:20px; color:white;font-weight:bold;text-align:center; margin-bottom:15px">
					{{$first_char = $first_char}}
				</div>
			</div>
		</div>
		<div class="row">
			@foreach($users as $u)
			<div class="col-lg-4">
				<div class="box box-default">
					<div class="box-header">
						<div class="pull-left">
							<img style="width: 100%;max-width: 45px;" src="{{$u->getAvatar('thumb')}}" class="img-circle" alt="User Image" />
						</div>
						<div class="pull-left" style="margin-left:10px">
							<a class="box-title" href="{{route('user.show', $u->id)}}">{{$u->name}} </a>
							@if($u->fake)
							<strong>*</strong>
							@endif
							<br>
							<small>
							@foreach($u->roles as $r)
							{{$r->title}},
							@endforeach
							</small>
						</div>
					</div>
					<div class="box-body">
						<table class="table">
							<tr>
								<th>{{Lang::get('employees.phone')}}</th>
								<td>{{$u->phone}}</td>
							</tr>
							<tr>
								<th>{{Lang::get('employees.email')}}</th>
								<td>{{$u->email}}</td>
							</tr>
						</table>
					</div>
				</div>
			</div>
			@endforeach
		</div>
		@endforeach
	</div>
	@if($session_owner->can('manage-user'))
	<div class="col-lg-4">
		<div class="panel panel-default">
			<div class="panel-heading">
				{{Lang::get('employees.add_employee')}}
			</div>
			<div class="panel-body">
				{{Form::open( array() )}}
					<div class="form-group">
						{{Form::text('firstname', null, array('placeholder' => Lang::get('employees.first_name'), 'class' => 'form-control'))}}
					</div>
					<div class="form-group">
						{{Form::text('lastname', null, array('placeholder' => Lang::get('employees.last_name'), 'class' => 'form-control') )}}
					</div>
					<div class="form-group">
						{{Form::text('email', null, array('placeholder' => Lang::get('employees.email_address'), 'class' => 'form-control') )}}
					</div>
					<div class="form-group">
						{{Form::text('phone', null, array('placeholder' => Lang::get('employees.phone_number'), 'class' => 'form-control') )}}
					</div>
					<!-- checkbox -->
                  <div class="form-group">
                    <label>
                      {{Form::checkbox('fake', null, array('checked'))}}
                      {{Lang::get('employees.he_will_not_use_application')}}
                    </label>
                  </div>
					{{Form::submit(Lang::get('employees.add'), array('class' => 'btn btn-primary'))}}
				{{Form::close()}}
			</div>
		</div>
	</div>
	@endif
</div>

@stop

@section('scripts')

<link href="{{asset('plugins/iCheck/polaris/polaris.css')}}" rel="stylesheet">
<script src="{{asset('plugins/iCheck/icheck.js')}}"></script>

<script>
$(document).ready(function(){
  $('input').iCheck({
    checkboxClass: 'icheckbox_polaris',
    radioClass: 'iradio_polaris',
    increaseArea: '-10%' // optional
  });
});
</script>

@stop