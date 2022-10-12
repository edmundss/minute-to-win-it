    <div class="box-title">
        <h2>{{Lang::get('attachments.attachments')}}</h2>
    </div>
		<div class="box">
			<div class="box-body">
				<div class="row">
				<div class="col-lg-12">
            @if(isset($session_owner))

					{{ Form::open(array('route' => 'attachments.store', 'enctype'=>"multipart/form-data") ) }}
						<div class="form-group col-lg-6">
							{{ Form::hidden('parent_id', $parent_id) }}
							{{ Form::hidden('parent_class', $parent_class) }}
							{{ Form::file('attachment', null, array('class'=>'form-control', 'rows'=>5, 'placeholder'=>"Have anything to add? Some details or ideas? Write them here")) }}
							{{$errors->first('attachment', '<div class="alert alert-danger">:message</div>') }}
						</div>
						<div class="form-group col-lg-6">
							{{ Form::submit(Lang::get('attachments.upload'), array('class' => 'btn btn-primary', 'id'=> 'upload-btn')) }}
						</div>
					{{ Form::close() }}
				@endif
				</div>
					
				</div>
					<div class="alert alert-danger alert-dismissable hidden" id="attachment-warning">
	                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
	                    <h4><i class="icon fa fa-ban"></i> Datne ir pārāk liela!</h4>
	                    Maksimāli atļautais augšupielādējamo daņu izmērs ir 2MB.
	                </div>
				<div class="col-lg-12">
					@if (count($attachments)>0)
					<ul class="attachments">
					@foreach ($attachments as $a )
                        <li>
	                        <a href="{{route('attachments.show', $a->id)}}">{{$a->filename}}</a>
	                        <div class="tools">
	                            <a href="{{route('attachments.delete', $a->id)}}"><i class="fa fa-trash-o"></i></a>
	                        </div>
                        </li>
                    @endforeach
                    </ul>
                    @else
                    	<p>No attachments yet</p>
                    @endif
				</div>
			</div>
		</div>
