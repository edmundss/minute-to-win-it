@extends('layouts.main')

@section('content')
<div class="row">
	<div class="col-lg-4 col-lg-offset-8">
		<div class="panel panel-default">
			<div class="panel-body">
				<div style="text-align:center">
					<img style="width:128px;height:128px" class="img-circle" src="{{$employee->getAvatar('big')}}">
					<h1>{{$employee->name}}</h1>
				</div>
				<table class="table">
					<tr>
						<th>{{Lang::get('employees.phone_number')}}</th>
						<td><input name="phone" class="form-control" value="{{$employee->phone}}"></td>
					</tr>
					<tr>
						<th>{{Lang::get('employees.email_address')}}</th>
						<td>{{$employee->email}}</td>
					</tr>
					<tr>
						<th>
							{{Lang::get('employees.roles')}} 
							@permission('manage-user')
								<button id="hide-toggler" class="text-muted btn btn-default btn-xs off"><i class="fa fa-eye"></i></button>
							@endpermission
						</th>
						<td>
							<ul style="padding-left:0px">
								@foreach($roles as $r)
									@permission('manage-user')
										<li class="role @if(!$r->assigned) unused @endif" @if(!$r->assigned) style="display:none" @endif>
											{{Form::checkbox($r->id, null, null, array(($r->assigned)?'checked':''))}}
											<a href="{{route('roles.show', $r->id)}}"> {{$r->display_name}}</a>
										</li>
									@else
										@if($r->assigned)
										<li><a href="{{route('roles.show', $r->id)}}">{{$r->display_name}}</a></li>
										@endpermission
									@endif
								@endforeach
							</ul>
							
						</td>
					</tr>
				</table>
			</div>
		</div>
		<div class="panel panel-default">
			<div class="panel-heading">{{Lang::get('employees.current_tasks')}}</div>
			<div class="panel-body">
				<div class="list-group">
                    @if (count($tasks) > 0)
						@foreach ($tasks as $task)
							<a href="{{ $task->url }}" class="list-group-item">
                                <i class="fa @if (isset($task->icon)) {{$task->icon}} @else fa-tasks @endif fa-fw"></i> {{{ str_limit($task->title, 30) }}}
                                <span class="pull-right text-muted small">
                                	<em>
                                	@if (isset($task->deadline)) {{$task->deadline}} @else ASAP @endif
                                	</em>
                                </span>	
                            </a>
						@endforeach
					@else
						<li>no tasks for you today!</li>
					@endif
                </div>
			</div>
		</div>
	</div>
</div>
@stop

@section('scripts')
	<script type="text/javascript">
		
		// ADD/REMOVE ROLES
		$(document).ready(function(){

			$('#hide-toggler').click(function(){
				$(this).toggleClass('off');
				if($(this).hasClass('off')) {
					$('.unused').hide();
				} else {
					$('.unused').show();
				}
			});

			$('.role input').on('change', function(){
				$.ajax({
					url:"{{route('user.role_update')}}",
					data:{
						user_id : {{$employee->id}}, 
						role_id : $(this).attr('name'),
						assign : $(this).prop('checked'),
					},
					success:function(data){
						console.log(data);
						$.notify({
			              // options
			              message: data 
			            },{
			              // settings
			              type: 'success'
			            });
					}
				})
				.fail(function(){
						console.log(data);
						$.notify({
			              // options
			              message: 'KĻŪDA! Lomas izmaiņas neizdevās.' 
			            },{
			              // settings
			              type: 'danger'
			            });
					
				});
			});

		});
	</script>

	<script>
		//setup before functions
		var typingTimer;                //timer identifier
		var doneTypingInterval = 2000;  //time in ms, 5 second for example
		var $input = $('input[name=phone]');

		//on keyup, start the countdown
		$input.on('keyup', function () {
		  clearTimeout(typingTimer);
		  typingTimer = setTimeout(doneTyping, doneTypingInterval);
		});

		//on keydown, clear the countdown 
		$input.on('keydown', function () {
		  clearTimeout(typingTimer);
		});

		//user is "finished typing," do something
		function doneTyping () {
		  $.post(
		  	"{{route('user.update_phone', $employee->id)}}",
		  	{
		  		phone: $input.val(),
		  		_token: "{{csrf_token()}}"
		  	},
		  	function() {
		  		 $.notify({
		          // options
		          message: "Tālruņa numurs ir sekmīgi saglabāts" 
		        },{
		          // settings
		          type: 'info'
		        });
		  	}
		  )
		  .fail(function(){
		  		$.notify({
		          // options
		          message: "Neizdevās saglabāt tālruņa numuru." 
		        },{
		          // settings
		          type: 'danger'
		        });
		  });
		}
	</script>
@stop