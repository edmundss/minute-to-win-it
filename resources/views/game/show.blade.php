@extends('layouts.main')

@section('content')
	<div class="row">
		<div class="col-lg-8">
			<div class="box box-warning box-solid">
				<div class="box-header with-border">
					<h1 class="box-title">Raundi</h1>
				</div>
				<table class="table table-striped">
					<thead>
						<tr>
							<th>Raunds</th>
							<th>Uzdevums</th>
							<th>Player 1</th>
							<th>Palyer 2</th>
							<th>Likmju vērtība</th>
						</tr>
					</thead>
					<tbody>
						@foreach($game->rounds()->orderBy('round')->get() as $r)
						<tr class="link-row" data-url="{{route('round.show', $r->id)}}">
							<td>{{$r->round}}</td>
							<td>{{$r->challenge->title}}</td>
							<td>{{$r->player1->name}} @if($r->winner_id == $r->player1_id)<i class="fa fa-trophy"></i>@endif</td>
							<td>{{$r->player2->name}} @if($r->winner_id == $r->player2_id)<i class="fa fa-trophy"></i>@endif</td>
							<td>{{$r->bets()->sum('bet')}}</td>
						</tr>
						@endforeach
					</tbody>
				</table>
				<div class="box-footer">
				@if(policy($game)->delete($session_owner, $game))
					<a href="{{route('game.delete', ['game_id'=>$game->id])}}" class="btn btn-danger">Dzēst spēli</a>
				@endif
				@if(policy($game)->update($session_owner, $game))
					<div class="pull-right">
						<a href="{{route('game.deactivate', ['game_id'=>$game->id])}}" class="btn btn-danger">Beigt</a>
						<a href="{{route('round.create', ['game_id'=>$game->id])}}" class="btn btn-success">{{($game->rounds()->count() < 1)?'Sākam!':'Nākamais raunds!'}}</a>
					</div>
				@endif
				</div>
			</div>
		</div>

		<div class="col-lg-4">
			<div class="box box-solid box-info">
				<div class="box-header with-border">
					<h1 class="box-title">Spēlētāji</h1>
						<button type="button" class="pull-right btn btn-xs btn-danger" data-toggle="modal" data-target="#player-form">Pievienot</button>
						@if(policy($game)->update($session_owner, $game))
					@endif
				</div>
				<div class="box-body">
					@foreach($game->players->chunk(4) as $players)
						<div class="row">
							@foreach($players as $p)
							<div class="col-lg-3 text-center" >
								<img style="width: 100%;max-width: 45px;" src="{{$p->getAvatar('thumb')}}" class="img-circle" alt="Player Image" /><br>
								{{$p->name}}
							</div>
							@endforeach
						</div>
					@endforeach
				</div>
			</div>
			@if($game->challenges()->count() > 0)
			<div>
	            <!-- Progress bars -->
	            <div class="clearfix">
	                <span class="pull-left">Spēles progress</span>
	                <small class="pull-right">{{$game->rounds()->count()/$game->challenges()->count()*100}}%</small>
	            </div>
	            <div class="progress {{($game->active)?'active':''}}">
	                <div class="progress-bar progress-bar-success progress-bar-striped" style="width: {{$game->rounds()->count()/$game->challenges()->count()*100}}%;"></div>
	            </div>
			</div>
			@endif

				<div class="box box-solid box-info">
					<div class="box-header with-border">
						<h1 class="box-title">izaicinājumi</h1>
						<button type="button" class="pull-right btn btn-xs btn-danger" data-toggle="modal" data-target="#challenge-toggle-modal">Pievienot</button>
					</div>
					<div class="box-body">
						<ul>
							@foreach ($game->challenges as $c)
								<li @if(in_array($c->id, $done_challenges)) style="text-decoration: line-through" @endif>
									{{$c->title}}
								</li>
							@endforeach
						</ul>
					</div>
				</div>
		</div>
	</div>
@stop

@section('modals')
	<div class="modal" id="player-form">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Modal Default</h4>
                  </div>
                  {{Form::open(['url'=> route('player.store'), 'method' => 'POST', 'files' => true])}}
                  	{{Form::hidden('game_id', $game->id)}}
	                  <div class="modal-body">
	                  	<div class="form-group">
	                  		{{Form::label('name', 'Vārds')}}
	                    	{{Form::text('name', null, ['class' => 'form-control'])}}
	                  	</div>
	                  	<div class="form-group">
	                  		{{Form::label('picture', 'Bilde')}}
	                    	{{Form::file('picture', null, ['class' => 'form-control'])}}
	                  	</div>
	                  	<div class="alert alert-warning alert-dismissable hidden">
		                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		                    <h4><i class="icon fa fa-warning"></i> Attēls ir pārāk liels!</h4>
		                    Max atļautais augšupielādes apjoms ir 2MB!
		                </div>
	                  </div>
	                  <div class="modal-footer">
	                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
	                  	{{Form::submit('Pievienot', ['class'=>"btn btn-primary", 'id'=>'submit-player'])}}
	                  </div>
                  {{Form::close()}}
                </div><!-- /.modal-content -->
              </div><!-- /.modal-dialog -->
            </div>
				<div class="modal" id="challenge-toggle-modal">
			              <div class="modal-dialog">
			                <div class="modal-content">
			                  <div class="modal-header">
			                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
			                    <h4 class="modal-title">Izaicinājumu slēgāšana</h4>
			                  </div>
			                  <div class="modal-body">
								  <table class="table" id="izaicinajumu-katalogs">
			  						<thead>
			  							<th>Nosaukums</th>
			  							<th></th>
			  						</thead>
			  						<tbody>
			  							@foreach (App\Challenge::orderBy('title')->get() as $c)
			  								<tr>
			  									<td>
			  										<input type="checkbox" value="{{$c->id}}" class="challenge-checkbox" @if(in_array($c->id, $selected_challenges)) checked @endif/>
			  										<span data-yt-code="{{$c->video_link}}">{{$c->title}}</span>
			  									</td>
			  								</tr>
			  							@endforeach
			  						</tbody>
			  					</table>


			                  </div>
			                  <div class="modal-footer">
			                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
			                  </div>
			                </div><!-- /.modal-content -->
			              </div><!-- /.modal-dialog -->
			            </div>
@stop

@section('scripts')


	    <script type="text/javascript">
	        $(function(){
	            $('.challenge-checkbox').change(function(){
					console.log($(this).val());
	                $.get("{{route('game.toggle_challenge', $game)}}", {
	                    challenge_id : $(this).val(),
	                    add : $(this).prop( "checked" ),
	                }, function (data){

/*
					    if(data.add) {
	                        $('#discount-labels').append('<span class="label label-danger" id="category-'+data.category.id+'">'+data.category.name+'</span>')
	                    } else {
	                        $('#category-'+data.category.id).remove();

	                        console.log($('#category-'+data.category.id))
	                    }

						*/

	                    console.log(data)
	                })

	            })
	        })
	    </script>

<script type="text/javascript">
	$(document).ready(function() {
	  $('#picture').on('change', function(evt) {
	    if(this.files[0].size > 2000000)
    	{
    		$('.alert').removeClass('hidden');
    		$('#submit-player').attr('disabled', 'disabled')

    	} else {
    		$('.alert').addClass('hidden');
    		$('#submit-player').removeAttr('disabled')

    	}
	  });

		$(".link-row").click(function() {
	        window.location = $(this).data("url");
	    });
	});
</script>
@stop
