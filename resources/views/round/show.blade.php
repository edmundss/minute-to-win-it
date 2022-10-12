@extends('layouts.main')

@section('content')
<div class="row">
	<div class="col-lg-6">
		<div class="row">
			<div class="col-lg-12">
				<div class="col-lg-6 text-center ">
					<a href="{{route('round.set_winner', [$round->id, $player1->id])}}">
					<div class="row {{($round->winner_id == $player1->id)?'winner':'challenger'}}">
						<img style="width:100%" src="{{$player1->getAvatar('big')}}" class="" alt="Player Image" />
						<br>
						<h1>
									{{$player1->name}}

						</h1>

						<img src="{{asset('image/assets/winner.png')}}" class="prize">
					</div>
					</a>
				</div>
				<div class="col-lg-6 text-center">
						<a href="{{route('round.set_winner', [$round->id, $player2->id])}}">
					<div class="row {{($round->winner_id == $player2->id)?'winner':'challenger'}}">
							<img style="width:100%" src="{{$player2->getAvatar('big')}}" class="" alt="Player Image" />
						<br>
						<h1>
						{{$player2->name}}

						</h1>

						<img src="{{asset('image/assets/winner.png')}}" class="prize">
					</div>
						</a>
				</div>
			</div>
		</div>
		<div style="margin-left: 37%;  margin-top: -280px; position:absolute">
			<img src="{{asset('image/assets/versus.png')}}" style="width: 150px;">
		</div>
		<div class="box box-solid box-info">
			<div class="box-header">
				<h1 class="box-title">LIKMES</h1>
					<button class="btn btn-warning btn-xs pull-right" data-toggle="modal" data-target="#bet-form">Pievienot</button>
					@if(policy($round)->set_winner($session_owner, $round))
				@endif
			</div>
			<div class="box-body">
				<div class="col-lg-6">
					@foreach($player1_bets as $b)
						<ul class="bets todo-list">
							<li>
								<img src="{{$b->player->getAvatar('xs')}}" class="img-circle" alt="Player Image" />
								<strong>{{$b->player->name}}:</strong> {{$b->bet}}
								@if($round->winner_id == $player1->id)
									<i class="fa fa-arrow-right"></i>
									{{round(($player1_total_bets + $player2_total_bets) * $b->bet / $player1_total_bets)}}
								@endif
								@if(policy($round)->set_winner($session_owner, $round))
									<div class="tools">
			                        	<a href="{{route('bet.delete', $b->id)}}"><i class="fa fa-trash-o"></i></a>
			                      	</div>
			                    @endif
							</li>
						</ul>
					@endforeach
				</div>
				<div class="col-lg-6">
					@foreach($player2_bets as $b)
						<ul class="bets todo-list">
							<li>
								<img src="{{$b->player->getAvatar('xs')}}" class="img-circle" alt="Player Image" />
								<strong>{{$b->player->name}}:</strong> {{$b->bet}}
								@if($round->winner_id == $player2->id)
									<i class="fa fa-arrow-right"></i>
									{{round(($player1_total_bets + $player2_total_bets) * $b->bet / $player2_total_bets)}}
								@endif
								@if(policy($round)->set_winner($session_owner, $round))
									<div class="tools">
			                        	<a href="{{route('bet.delete', $b->id)}}"><i class="fa fa-trash-o"></i></a>
			                      	</div>
			                    @endif
							</li>
						</ul>
					@endforeach
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-6">
		<div class="nav-tabs-custom">
	        <!-- Tabs within a box -->
	        <ul class="nav nav-tabs">
	          <li class="active"><a href="#revenue-chart" data-toggle="tab">Uzdevums</a></li>
	          <li><a href="#sales-chart" data-toggle="tab">Mūsu izpildījums</a></li>
	        </ul>
	        <div class="tab-content no-padding">
	          <!-- Morris chart - Sales -->
	          <div class="chart tab-pane active" id="revenue-chart" style="">
				<div class="bs-example" data-example-id="responsive-embed-16by9-iframe-youtube">
					<div class="embed-responsive embed-responsive-16by9">
						<iframe class="embed-responsive-item" src="//www.youtube.com/embed/{{$challenge->video_link}}?rel=0" allowfullscreen=""></iframe>
					</div>
				</div>

	          </div>
	          <div class="chart tab-pane" id="sales-chart" style="">
	          	@if($round->video_link)
					<div class="bs-example" data-example-id="responsive-embed-16by9-iframe-youtube">
						<div class="embed-responsive embed-responsive-16by9">
							<iframe class="embed-responsive-item" src="//www.youtube.com/embed/{{$round->video_link}}?rel=0" allowfullscreen=""></iframe>
						</div>
					</div>
				@else
					<p>Video nav pievienots. Pievieno savu video!</p>
					{{Form::open(['url' => route('round.submit_video', $round->id)])}}
						<div class="col-lg-10">
							{{Form::text('video_link', null, ['class' => 'form-control', 'placeholder' => 'hipersaite uz youtube video'])}}
						 </div>
						{{Form::submit('Pievienot', ['class' => 'btn btn-success col-lg-2'])}}
					{{Form::close()}} <br>
				@endif
	          </div>
	        </div>
	    </div>
		<div class="col-lg-6 text-center">
			<h2>Favorīts</h2>
			<div class="chart " id="chart" style="position: relative; height: 300px;"></div>
		</div>
		<div class="col-lg-6 text-right">
			<a href="{{route('round.next', ['round_id'=>$round->id])}}">
				<h1>
				{{$round->round+1}}. raunds<br>
				<i class="fa fa-hand-o-right fa-6" style="font-size:5em"></i>
				</h1>
			</a>
		</div>
	</div>
</div>
@stop

@section('modals')
	<div class="modal" id="bet-form">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Dāmas  un kunģi, liekam likmes!</h4>
                  </div>
                  {{Form::open(['url'=> route('bet.store'), 'method' => 'POST', 'files' => true])}}
                  	{{Form::hidden('round_id', $round->id)}}
	                  <div class="modal-body">
	                  	<div class="form-group">
	                  		{{Form::label('player_id', 'Kas?')}}
	                    	{{Form::select('player_id', $players, null, ['class' => 'form-control'])}}
	                  	</div>
	                  	<div class="form-group">
	                  		{{Form::label('challenger_id', 'Par ko?')}}
	                    	{{Form::select('challenger_id', [$player1->id => $player1->name, $player2->id => $player2->name], null, ['class' => 'form-control'])}}
	                  	</div>
	                  	<div class="form-group">
	                  		{{Form::label('bet', 'Cik?')}}
	                    	{{Form::text('bet', null, ['class' => 'form-control'])}}
	                  	</div>
	                  </div>
	                  <div class="modal-footer">
	                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Aizvērt</button>
	                  	{{Form::submit('Pievienot', ['class'=>"btn btn-primary", 'id'=>'submit-player'])}}
	                  </div>
                  {{Form::close()}}
                </div><!-- /.modal-content -->
              </div><!-- /.modal-dialog -->
            </div>
@stop

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="{{asset('plugins/morris/morris.min.js')}}"></script>
	<script type="text/javascript">
	$(function() {
		var donut = new Morris.Donut({
	        resize: true,
	        element: 'chart',
	        colors: ["#3c8dbc", "#f56954", "#00a65a"],
	        data: [
	        	{
	        		label: "{{$player1->name}}",
	        		value: {{$player1_total_bets}}
       	        },
	        	{
	        		label: "{{$player2->name}}",
	        		value: {{$player2_total_bets}}
       	        }
       	    ],
	        hideHover: 'auto'
	      });
		}
	)
	</script>
@stop
