@extends('layouts.main')

@section('content')
	<div class="box box-danger">
		<div class="box-header">
			<div class="pull-right">
				<a href="{{route('game.create')}}" class="btn btn-success btn-xs">Jauna</a>
			</div>
		</div>
		<table class="table">
			<thead>
				<tr>
					<th>Datums</th>
					<th>Vadītajs</th>
					<th>Statuss</th>
				</tr>
			</thead>
		</table>
	</div>
@stop

@section('scripts')
<script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('plugins/datatables/dataTables.bootstrap.min.js')}}"></script>


<script type="text/javascript">
  $(function () {
        $('.table').DataTable({
            processing: true,
          serverSide: true,
          bfilter: false,
          ajax: "{{route('game.datatable')}}",
          columns: [
              { data: 'created_at', name: 'games.created_at'},
              { data: 'user', name: 'users.name'},
              { data: 'active', searchable: false},
          ]
        });
    });
</script>
@stop