@extends('layouts.main')

@section('content')
<div class="row">
	<div class="col-lg-4">
		<div class="box box-solid">
			<div class="box-header with-border">
				<h3 class="box-title">Task info</h3>

        @if($session_owner->id == $task->author_id)
        <div class="pull-right">
          <a href="{{route('tasks.edit', $task->id)}}" class="btn btn-xs btn-flat btn-primary">Edit</a>
          <a href="" class="btn btn-xs btn-flat btn-danger">Cancel</a>
        </div>
        @endif
			</div>
      <table class="table">
        <tr>
          <th>Nosaukums</th>
          <td>{{$task->title}}</td>
        </tr>
        <tr>
          <th>Apraksts</th>
          <td>
            @if($task->description)
              {{$task->description}}</td>
            @else
              Not specified
            @endif
          </td>
        </tr>
        @if($task->parent_id)
        <tr>
          <th>Parent</th>
          <td><a href="{{route(snake_case($task->parent_class).'s.show', $task->parent_id)}}">{{$task->parent_class}} #{{$task->parent_id}}</a></td>
        </tr>
        @endif
        <tr>
          <th>Termiņš</th>
          <td>
              @if($task->deadline)
                {{$task->deadline}}</td>
              @else
                Not specified
              @endif
        </tr>
        <tr>
          <th>Uzdeva</th>
          <td><a href="{{route('user.show', $task->author_id)}}">{{$task->author->name}}</a></td>
        </tr>
        <tr>
          <th>Izpildītājs</th>
          <td>
            @if($task->responsible)
            <a href="{{route('user.show', $task->responsible_id)}}">{{$task->responsible->name}}</a></td>
            @else
            Not assigned
            @endif
        </tr>
        <tr>
          <th>Status</th>
          <td>{{$taskStatuses[$task->status]}}</td>
        </tr>
      </table>
      <div class="box-footer">

          @if($session_owner->id == $task->responsible_id && $task->status == 1)
        <!--ACCEPT OR REJECT TASK-->
        <div class="pull-right">
          <a href="{{route('tasks.update_status') .'?id='.$task->id.'&done=true'}}" class="btn btn-flat btn-success">Done</a>
          <a href="{{route('tasks.update_status') .'?id='.$task->id.'&rejected_assignment=true'}}" class="btn btn-flat btn-danger">Reject</a>
        </div>
          @endif
          
          @if($session_owner->id == $task->author_id && $task->status == 2)
        <!--CONFIRM OR REJECT COMPLETION-->
        <div class="pull-right">
          <a href="{{route('tasks.update_status') .'?id='.$task->id.'&done=true'}}" class="btn btn-flat btn-success">Close</a>
          <a href="{{route('tasks.update_status') .'?id='.$task->id.'&rejected_completion=true'}}" class="btn btn-flat btn-danger">Reject</a>
        </div>
          @endif
      </div>
		</div>
	</div>
	<div class="col-lg-8">
		<div class="row">
			<div class="col-lg-12">
				<div class="box box-primary">
          <div class="box-header with-norder">
            <h1 class="box-title">Subtasks</h1>
            <button class="pull-right btn btn-xs btn-primary" data-toggle="modal" data-target="#modal">New</button>
          </div>
          @if(count($subtasks) > 0)
            <table class="table">
              <thead>
                <tr>
                  <th>Task</th>
                  <th>Responsible</th>
                  <th>Status</th>
                  <th>Deadline</th>
                  <th>Start</th>
                  <th>Finish</th>
                  <th><i class="fa fa-comments"></i></th>
                  <th><i class="fa fa-file"></i></th>
                </tr>
              </thead>
              <tbody>
                @foreach($subtasks as $t)
                  <tr>
                    <td><a href="{{route('tasks.show', $t->id)}}">{{$t->title}}</a></td>
                    <td>{{str_limit($t->responsible->firstname, 1, '.') . $t->responsible->lastname}}</td>
                    <td>{{$taskStatuses[$t->status]}}</td>
                    <td>{{$t->deadline}}</td>
                    <td>{{$t->start}}</td>
                    <td>{{$t->finish}}</td>
                    <td>{{$t->comments()->count()}}</td>
                    <td>{{$t->attachments()->count()}}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          @else
  					<div class="box-body">
              This task doesn't have any subtasks
  					</div>
          @endif
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-6">
				@include('layouts.partials._comments')
			</div>
			<div class="col-lg-6">
				@include('layouts.partials._attachments')
				
			</div>
		</div>
	</div>
</div>
@stop


@section('script_css')
  <link rel="stylesheet" href="{{asset('plugins/select2/select2.min.css')}}">
@stop


@section('scripts')
<script src="{{asset('plugins/select2/select2.min.js')}}"></script>

<script type="text/javascript">
$(function(){
  $("select[name=responsible_id]").select2({
    placeholder: "select one",
    ajax: {
      dataType: 'json',
      url: "{{route('user.json')}}",
      delay: 250,
      selectOnBlur: true,
      data: function(params) {
        return {q: params.term};
      },
      processResults: function(data)
      {
        return { results: data }
      },
    }
  });
});

</script>
@stop


@section('modals')
  <div id="modal" class="modal">
        <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Add new subtask</h4>
              </div>
              {{Form::open(array('url'=>route('tasks.store')))}}
                <div class="modal-body">
                  {{Form::hidden('parent_class', 'Task')}}    
                  {{Form::hidden('parent_id', $task->id)}}    
                  @include('tasks.partials._form')
                </div>
                <div class="modal-footer">
                  {{Form::submit('submit', array('class'=>'btn btn-primary'))}}
                </div>
        {{Form::close()}}
          </div><!-- /.modal-dialog -->
      </div><!-- /.modal -->
    </div>
@stop