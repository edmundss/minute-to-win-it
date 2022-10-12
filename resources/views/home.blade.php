@extends('layouts.main')

@section('content')
	<div class="row">
		<div class="col-lg-6">
	        <!-- Chat box -->
	        <div class="box box-solid">
	            <div class="box-header with-border"><h1 class="box-title">{{Lang::get('dashboard.shout_box')}}</h1></div>
	            {{Form::open(array('url' => route('comments.store')))}}
	                {{Form::hidden('parent_class', 'Dashboard')}}
	                {{Form::hidden('parent_id', '0')}}    
	                <textarea name="comment" placeholder="{{Lang::get('dashboard.type_message')}}" class="form-control no-border"></textarea>
	                <div class="box-footer">
	                    <div class="pull-right">
	                        <button class="btn btn-primary btn-sm">Submit</button>
	                    </div>
	                    <div class="pull-left">
	                        <!--button class="btn btn-sm"><i class="fa fa-file-o"></i></button-->
	                    </div>
	                </div>
	            {{Form::close()}}
	        </div>

	        <ul class="timeline">

	        </ul>

	        <div style="text-align:center; margin-bottom:20px;"><a href="javascript:comments_to_html()" class="btn btn-primary">Vēl...</a></div>
		</div>
        <div class="col-lg-6">

        <!--TASKS-->
        <div class="box box-default" name="tasks">
            <div class="box-header">
                <i class="fa fa-tasks fa-fw"></i> <h4 class="box-title">Servisa uzdevumi</h4>
            </div>
            <!-- /.panel-heading -->
            <div class="box-body">
                <ul id="to-do" class="todo-list ui-sortable">
                    @if (count($service_tasks) > 0)
                        @foreach ($service_tasks as $task)
                            <li>
                              <!-- todo text -->
                              <a href="{{ route('tools.show', $task->id) }}"><span class="text">{{$task->inventory_code . ' - ' . [2=>'Instruments ir HILTI servisā, neaizmisrti izņemt', 3=>'Instruments sarementotēts, izņem no servisa', 4=>'Instruments bojāts, piestādi klientam rēķinu.', 7=>'Instruments nolietots. Norakstīt un izsniegt klientam jaunu'][$task->status]}}</span></a>
                              <div class="tools">
                                <a href="{{route('service_cases.task_done', ['record_id' => $task->record_id])}}"><i class="fa fa-trash-o"></i></a>
                              </div>
                            </li>
                        @endforeach
                    @else
                        <li>Nav servisa uzdevumu!</li>
                    @endif
                </ul>
                <!-- /.list-group -->
            </div>
            <!-- /.panel-body -->
        </div>
        <!--END tasks-->

        <!--TASKS-->
        <div class="box box-default" name="tasks">
            <div class="box-header">
                <i class="fa fa-tasks fa-fw"></i> <h4 class="box-title">{{Lang::get('dashboard.your_tasks')}}</h4>
            </div>
            <!-- /.panel-heading -->
            <div class="box-body">
                <ul id="to-do" class="todo-list ui-sortable">
                    @if (count($tasks) > 0)
                        @foreach ($tasks as $task)
                            <li>
                              <!-- checkbox -->
                              <input type="checkbox" value="" name="" disabled>
                              <!-- todo text -->
                              <a href="{{ route('tasks.show', $task->id) }}"><span class="text">{{{ str_limit($task->title, 30) }}}</span></a>
                              <!-- Emphasis label -->
                              <small class="label label-danger"><i class="fa fa-clock-o"></i> @if (isset($task->deadline)) {{$task->deadline}} @else ASAP @endif </small>
                              <!-- General tools such as edit or delete-->
                              <div class="tools">
                                <i class="fa fa-trash-o"></i>
                              </div>
                            </li>
                        @endforeach
                    @else
                        <li>no tasks for you today!</li>
                    @endif
                </ul>
                <!-- /.list-group -->
            </div>
            <!-- /.panel-body -->
        </div>
        <!--END tasks-->
        </div>
	</div>
@endsection

@section('scripts')
    <script src="{{asset('plugins/jsrender/jsrender.min.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="{{asset('plugins/morris/morris.min.js')}}"></script>


<script id="itemTmpl" type="text/x-jsrender">
    <!-- timeline item -->
    <li>
        <!-- timeline icon -->
        <img src="~~:avatar~~" class="user-image"/>
        <div class="timeline-item">
            <span class="time"><i class="fa fa-clock-o"></i> ~~:time~~</span>

            <h3 class="timeline-header"><a href="{{asset('user')}}/~~:user_id~~">~~:name~~</a> ~~:action~~</h3>

            <div class="timeline-body">
                ~~:comment~~
            </div>
        </div>
    </li>
    <!-- END timeline item -->
</script>

<script id="dateTmpl" type="text/x-jsrender">
    <li class="time-label">
        <span class="bg-red">
            ~~:date~~
        </span>
    </li>
</script>

<script type="text/javascript">
    $.views.settings.delimiters("~~","~~");

    var skip = 0;
    var item_template = $.templates("#itemTmpl");
    var date_template = $.templates("#dateTmpl");
    var date;

    function comments_to_html(){
        var html_comments = "";

        var params = {
            skip : skip,
        };

        console.log(skip);

        $.getJSON("{{route('comments.load')}}", params, function(comments){
            for(c of comments){
                if(date != c.date){
                    var htmlOutput = date_template.render(c);
                    $(".timeline").append(htmlOutput);
                    
                    date = c.date;
                }

                var htmlOutput = item_template.render(c);
                $(".timeline").append(htmlOutput);
            }
        });
        skip += 5;
    };
    // END COMMENT SCRIPT
    $(function(){
        // COMMENT SCRIPTs


        //tiklīdz lapa ir gatava ielādē pirmo komentāru porciju
        comments_to_html();
    });
</script>
	
@stop

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('plugins/morris/morris.css')}}">
<style type="text/css">
    .content-header{display:none;}
</style>
@stop