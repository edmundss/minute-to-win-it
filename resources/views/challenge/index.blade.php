@extends('layouts.main')

@section('content')
	<div class="box box-danger">
		<div class="box-header">
			<div class="pull-right">
				<a href="{{route('challenge.create')}}" class="btn btn-success btn-xs">Jauns</a>
			</div>
		</div>
		<table class="table">
			<thead>
				<tr>
					<th>Datums</th>
					<th>Nosaukums</th>
					<th>Izveidoja</th>
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
          ajax: "{{route('challenge.datatable')}}",
          columns: [
              { data: 'created_at', name: 'challenges.created_at'},
              { data: 'title', name: 'challenges.title'},
              { data: 'user', name: 'users.name'},
          ]
        });
    });
</script>
@stop