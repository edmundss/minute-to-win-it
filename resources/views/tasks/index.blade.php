@extends('layouts.main')

@section('content')
<div class="row">
	<div class="col-lg-12">
		<div class="box box-solid">
			<div class="box-header with-border">
				<h1 class="box-title" id="task-table-title">
					Aktīvie uzdevumi
				</h1>
				<div class="box-tools">
                    <button class="btn btn-primary btn-xs  btn-flat" onclick="completed_toggle()" id="show-completed-toggle">Rādīt izpildītos</button>
                    <button class="btn btn-primary btn-xs  btn-flat" type="button" data-toggle="modal" data-target="#modal">Jauns</button>
                  </div>
			</div>
			<table class="table" id="task-table">
				<thead>
					<tr>
						<th>Uzdevuma #</th>
						<th>Īss apraksts</th>
						<th>Termiņš</th>
						<th>Statuss</th>
						<th>Uzdeva</th>
						<th>Izpildītājs</th>
						<th></th>
					</tr>
				</thead>
			</table>
			
		</div>
	</div>
</div>
@stop

@section('css')
	<link href="{{asset('plugins/datepicker/datepicker3.css')}}" rel="stylesheet" />
	<link href="{{asset('plugins/select2/select2.min.css')}}" rel="stylesheet" />
	<style type="text/css">
		rect {
			opacity: 0.5;
		}
	</style>
@stop

@section('scripts')
    <script src="{{asset('plugins/datepicker/bootstrap-datepicker.js')}}"></script>
    <script src="{{asset('plugins/select2/select2.js')}}"></script>
	<script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
	<script src="{{asset('plugins/datatables/dataTables.bootstrap.min.js')}}"></script>


<script type="text/javascript">
	var show_completed = false;
	var table;

	var completed_toggle = function() {
		show_completed = !show_completed;

		if(show_completed)
		{
			console.log(show_completed);
			$('#show-completed-toggle').html('Slēpt izpildītos')
			$('#task-table-title').html('Visi uzdevumi')
			table.ajax.url("{{route('tasks.datatable')}}?show_completed=" + show_completed).load();
		} else {
			console.log(show_completed);
			$('#show-completed-toggle').html('Rādīt izpildītos')
			$('#task-table-title').html('Aktīvie uzdevumi')
			table.ajax.url("{{route('tasks.datatable')}}?show_completed=" + show_completed).load();
		}

	}

	$(function () {
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

        table = $('#task-table').DataTable({
          	processing: true,
	        serverSide: true,
	        bfilter: false,
	        ajax: "{{route('tasks.datatable')}}?show_completed=" + show_completed,
	        columns: [
	            { data: 'id', name: 'tasks.id'},
	            { data: 'title', name: 'tasks.title'},
	            { data: 'deadline'},
	            { data: 'status'},
	            { data: 'author_name', name: 'authors.name'},
	            { data: 'responsible_name', name: 'responsibles.name'},
	            { data: 'buttons', searchable: false, sortable: false }
	        ]
        });

        $('input[name=deadline]').datepicker({
			format: 'yyyy-mm-dd'
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