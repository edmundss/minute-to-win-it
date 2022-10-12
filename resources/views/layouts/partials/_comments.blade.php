    <div class="box-title">
        <h2>{!! Lang::get('comments.comments_and_change_history') !!}</h2>
    </div>
		<div class="box">
            @if(isset($session_owner))
            <div class="box-header">
                {{Form::open(array('url' => route('comments.store')))}}
                {{ Form::hidden('parent_id', $parent_id) }}
							{{ Form::hidden('parent_class', $parent_class) }}
                    <div class="input-group">
                        {{Form::text('comment', null, array('class' => 'form-control', 'placeholder' => Lang::get('comments.type_a_message')))}}
                        <div class="input-group-btn">
                            <input type="submit" class="btn btn-success" VALUE="{{Lang::get('comments.submit')}}" />
                        </div>
                    </div>
                {{Form::close()}}
            </div>
            <hr style="margin-top:0px">
            @endif
            <!-- Most Viewed Courses Title -->
			<div class="box-body">
				<div class="row">
					<div class="box-body chat" id="chat-box">
                  @if(count($comments) > 0)
                  @foreach($comments as $c)
                  <!-- chat item -->
                  <div class="item">
                    <img src="{{$c->user->getAvatar('thumb')}}" alt="user image" class="online" />
                    <p class="message">
                      <a href="{{route('user.show', $c->user->id)}}" class="name">
                        <small class="text-muted pull-right"><i class="fa fa-clock-o"></i> {{date_format($c->created_at, "H:i")}}</small>
                        {{$c->user->name}}
                      </a>
                      {{$c->comment}}
                    </p>
                  </div><!-- /.item -->
                  @endforeach
                  @else
                  <p>No comments</p>
                  @endif
                </div><!-- /.chat -->
				</div>
			</div>
		</div>
