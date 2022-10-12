@extends('layouts.main')

@section('content')
<div class="row">
  <div class="col-lg-4">
    <div class="box box-solid">
      <div class="box-header with-border">
        <h1 class="box-title">Uzdevumi</h1>
        <div class="pull-right">
                  <button class="btn btn-flat btn-xs btn-primary" type="button" data-toggle="modal" data-target="#modal">New</button>
        </div>
      </div>
            <div class="task-list">
                    @include('tasks.partials._list')
              
            </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="box box-solid">
      <div class="box-header with-border">
        <h1 class="box-title">Pieprasījumi</h1>
        <div class="pull-right">
                  <button class="btn btn-flat btn-xs btn-primary" type="button" data-toggle="modal" data-target="#modal">New</button>
        </div>
      </div>
            <div class="task-list">
                    @include('tasks.partials._requests')
              
            </div>
    </div>
  </div>
</div>
@stop

@section('css')
	<link href="{{asset('plugins/datepicker/datepicker3.css')}}" rel="stylesheet" />
	<link href="{{asset('plugins/fullcalendar/fullcalendar.min.css')}}" rel="stylesheet" />
	<link href="{{asset('plugins/select2/select2.min.css')}}" rel="stylesheet" />
	<style type="text/css">
		rect {
			opacity: 0.5;
		}
	</style>
@stop

@section('scripts')
	<script src="{{asset('plugins/fullcalendar-scheduler/lib/moment.min.js')}}"></script>
    <script src="{{asset('plugins/fullcalendar/fullcalendar.min.js')}}"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
    <script src="{{asset('plugins/datepicker/bootstrap-datepicker.js')}}"></script>
    <script src="{{asset('plugins/select2/select2.js')}}"></script>


	<script type="text/javascript">
	//AJAX PAGINATION

	$(window).on('hashchange', function() {
        updatelist();
    });


	 $(document).ready(function() {
        $(document).on('click', '.pagination a', function (e) {
            getTasks($(this).attr('href').split('page=')[1]);
            e.preventDefault();
        });
    });

	function updatelist() {
		if (window.location.hash) {
            var page = window.location.hash.replace('#', '');
            if (page == Number.NaN || page <= 0) {
                return false;
            } else {
                getTasks(page);
            }
        } else {
        	getTasks(1);
        }
	}

	 function getTasks(page) {
        $.ajax({
            url : '?page=' + page,
            dataType: 'json',
        }).done(function (data) {
            $('.task-list').html(data);
            location.hash = page;
			initializeTaskList();
        }).fail(function () {
            $.notify({
              // options
              message: "Nevaru ielādēt uzdevumus!" 
            },{
              // settings
              type: 'danger'
            });
        });
    }

	</script>

	<script type="text/javascript">
		var date = new Date();

		$(function(){
			$('input[name=deadline]').datepicker({
			    format: 'yyyy-mm-dd'
			});


		});
	</script>




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

	$('input[type=checkbox]').on('change', function(event){

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
@stop



@section('modals')
	<div id="modal" class="modal">
      	<div class="modal-dialog">
        	<div class="modal-content">
          		<div class="modal-header">
            		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            		<h4 class="modal-title">Add new task</h4>
          		</div>
            	{{Form::open(array('url'=>route('tasks.store')))}}
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