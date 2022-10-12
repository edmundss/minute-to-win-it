    <div class="box-title">
        <h2>Apakšuzdevumi</h2>
    </div>
		<div class="box">
      <div class="box-header with-border">
        <button class="btn btn-flat btn-xs btn-primary pull-right" type="button" data-toggle="modal" data-target="#modal">Jauns</button>
      </div>
            <!-- Most Viewed Courses Title -->
			<div class="box-body">
				<div class="row">
					<div class="box-body">
            <ul class="todo-list">
              @if(count($tasks)>0)
              @foreach($tasks as $t)
              <li data-id="{{$t->id}}" class="">
                <!-- checkbox -->
                <input data-id="{{$t->id}}" class="task-checkbox" type="checkbox" value="" @if($t->status > 1) checked="checked" @endif @if($session_owner->id != $t->responsible_id) disabled="disabled" @endif>
                <!-- todo text -->
                <a href="{{route('tasks.show', $t->id)}}"><span class="text">{{str_limit($t->title, 30, "...")}}</span></a>
                <!-- General tools such as edit or delete-->
                <div class="tools">
                  <a href="{{route('user.show', $t->responsible_id)}}">@if($t->responsible_id) {{str_limit( explode(' ', $t->responsible->name)[0], 1, '. ') . explode(' ', $t->responsible->name)[1]}} @endif</a>
                </div>
              </li>
              @endforeach
              @else
              <li>Nav neviena apakšuzdevuma</li>
              @endif
            </ul>
          </div><!-- /.chat -->
				</div>
			</div>
		</div>




@section('task_modal')
  <div id="modal" class="modal">
        <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Add new task</h4>
              </div>
              {{Form::open(array('url'=>route('tasks.store')))}}
                {{Form::hidden('parent_class', $parent_class)}}
                {{Form::hidden('parent_id', $parent_id)}}
              <div class="modal-body">
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

@section('task_css')
<link rel="stylesheet" href="{{asset('plugins/select2/select2.min.css')}}">
@stop

@section('task_scripts')
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

  $('.task-checkbox').on('change', function(event){

    $id = $(this).attr('data-id');
    $done = $(this).is(':checked');

    $.get(
      "{{route('tasks.update_status')}}",
      {
        id: $id,
        done: $done,
      },
      function(data) {
        
        $.notify({
          // options
          message: "Uzdevuma statuss ir veiksmīgi saglabāts!" 
        },{
          // settings
          type: 'success'
        });
      }
    );
  });
});

</script>

<script src="{{asset('plugins/datepicker/bootstrap-datepicker.js')}}"></script>
  <script type="text/javascript">
    $(document).ready(function() {

        $('.datepicker-control').datepicker({
            format: 'yyyy-mm-dd',
        });
    });
  </script>
@stop