@extends('layouts.main')

@section('content')
		<div class="row">
			<div class="col-lg-6">
				<div class="box box-solid">
						{{Form::model($task, array('url'=>route('tasks.update', $task->id), 'method' => 'PUT'))}}
					<div class="box-body">
						@include('tasks.partials._form')
					</div>
					<div class="box-footer">
						{{Form::submit('Save', array('class' => 'btn btn-success btn-flat'))}}
						{{Form::reset('Reset', array('class' => 'btn btn-warning btn-flat'))}}
					</div>
						{{Form::close()}}
				</div>
			</div>
		</div>
@stop

@section('scripts')
<script src="{{asset('plugins/datepicker/bootstrap-datepicker.js')}}"></script>
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



  $('input[name=deadline]').datepicker({
    format: 'yyyy-mm-dd'
  });
});

</script>

@stop



@section('css')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('plugins/select2/select2.min.css')}}">
  <link href="{{asset('plugins/datepicker/datepicker3.css')}}" rel="stylesheet" />
@stop