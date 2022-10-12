<div class="box-body">
				<ul class="todo-list ui-sortable" style="overflow: visible;">
                    @foreach($personal_tasks as $t)
                    <li data-id="{{$t->id}}" @if($t->class == 'Task')class="plannable"@endif>
                    	<span class="handle ui-sortable-handle">
	                    	<i class="fa fa-ellipsis-v"></i>
	                    	<i class="fa fa-ellipsis-v"></i>
	                    </span>
                      <!-- checkbox -->
                      <input class="" data-id="{{$t->id}}" type="checkbox" value="" @if($t->status > 1) checked="checked" @endif>
                      <!-- todo text -->
                      <a href="{{route('tasks.show', $t->id)}}">
                        <span class="text">
                        {{str_limit($t->title, 25, "...")}}
                        @if($t->start) - {{date_format(date_create($t->start), 'd.m H:i')}} @endif</i>
                        </span></a>
                      <!-- General tools such as edit or delete-->
                      <div class="tools">
                        @if($t->author_id == $session_owner->id)
                        <a href="{{route('tasks.delete', $t->id)}}">
                        <i class="fa fa-trash-o"></i>
                        </a>
                        @endif
                      </div>
                    </li>
                    @endforeach
                  </ul>
			</div>
			<div class="box-footer">
				<div class="box-tools pull-right">
					{{ $personal_tasks->links()}}
                </div>
			</div>
