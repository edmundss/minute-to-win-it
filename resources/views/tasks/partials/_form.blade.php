					<div class="form-group">
						{{Form::label('responsible_id', 'Atbildīgais')}}
						{{Form::select('responsible_id', array($session_owner->id => $session_owner->name), null, array('class'=>'form-control'))}}
					</div>
					<div class="form-group">
						{{Form::label('title', 'Uzdevuma nosaukums')}}
						{{Form::text('title', null, array('class'=>'form-control'))}}
					</div>
					<div class="form-group">
						{{Form::label('description', 'Uzdevuma apraksts')}}
						{{Form::textarea('description', null, array('class'=>'form-control', 'rows'=>3))}}
					</div>
					<div class="form-group">
						{{Form::label('deadline', 'Termiņš')}}
						{{Form::text('deadline', null, array('class'=>'form-control datepicker-control'))}}
					</div>